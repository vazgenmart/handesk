<?php

namespace App\Thrust;

use App\ThrustHelpers\Actions\AssignTickets;
use App\User;
use BadChoice\Thrust\Resource;
use BadChoice\Thrust\Fields\Date;
use BadChoice\Thrust\Fields\Link;
use App\ThrustHelpers\Fields\Rating;
use BadChoice\Thrust\Fields\Gravatar;
use App\Repositories\TicketsIndexQuery;
use App\ThrustHelpers\Actions\NewTicket;
use App\ThrustHelpers\Actions\ChangeStatus;
use App\ThrustHelpers\Actions\MergeTickets;
use App\ThrustHelpers\Filters\StatusFilter;
use App\ThrustHelpers\Actions\ChangePriority;
use App\ThrustHelpers\Filters\PriorityFilter;
use App\ThrustHelpers\Filters\EscalatedFilter;
use App\ThrustHelpers\Fields\TicketStatusField;
use App\Ticket as Tickets;
use BadChoice\Thrust\ResourceFilters\Filters;
use BadChoice\Thrust\ResourceFilters\Search;
use BadChoice\Thrust\ResourceFilters\Sort;
use http\Env\Request;

class Ticket extends Resource
{
    public static $model = \App\Ticket::class;
    public static $search = ['tickets.request_type', 'tickets.requester_id', 'teams.name', 'users.name', 'tickets.id', 'tickets.user_id'];
    public static $defaultSort = 'tickets.updated_at';
    public static $defaultOrder = 'tickets.desc';
    public static $request_type = ['Right of access by the data subject', ' Right to rectification', 'Right to erasure (‘right to be forgotten’)', ' Right to restriction of processing', 'Right to data portability', 'Right to object', 'Other comment or question'];
    protected $pagination = 500;

//    protected $pagination = 1;
    public function fields()
    {
        return [
            //Gravatar::make('requester.email')->withDefault('https://raw.githubusercontent.com/BadChoice/handesk/master/public/images/default-avatar.png'),
            TicketStatusField::make('id', ''),
            Link::make('title', __('ticket.subject'))->rowClass('notification')->displayCallback(function ($ticket) {
                return "#{$ticket->id} · " . $ticket->request_type;
            })->route('tickets.show')->sortable(),
            Link::make('requester.id', trans_choice('ticket.requester', 1))->displayCallback(function ($ticket) {
                return $ticket->requester->name ?? '--';
            })->link('tickets?requester_id={field}'),
            Link::make('tickets.view', __('Note'))->displayCallback(function ($ticket) {
                if (auth()->user()->admin == 1) {
                    if ($ticket->view != Tickets::ADMIN_SEEN && $ticket->view != Tickets::SEEN) {
                        return '<i class="fa fa-bell"></i>';
                    }
                } else {
                    if ($ticket->view != Tickets::USER_SEEN && $ticket->view != Tickets::SEEN) {
                        return '<i class="fa fa-bell"></i>';
                    }
                }

            }),
            Link::make('team.id', __('ticket.team'))->displayCallback(function ($ticket) {
                return $ticket->team->name ?? '--';
            })->link('tickets?team_id={field}'),
            Link::make('user.id', trans_choice('ticket.user', 1))->displayCallback(function ($ticket) {
                return $ticket->user->name ?? '--';
            })->link('tickets?user_id={field}'),
            Rating::make('rating', ''),
            Date::make('created_at', __('ticket.requested'))->showInTimeAgo()->sortable(),
            Date::make('updated_at', __('ticket.updated'))->showInTimeAgo()->sortable(),
        ];
    }

    public function query()
    {
        $user = new User();
        $teamsTickets = $user->userTeamIds();
        $teamsTickets = array_values((array)$teamsTickets);
        $query = $this->getBaseQuery();
        if (request('search')) {
            Search::apply($query, request('search'), static::$search);
            if (!auth()->user()->admin) {
//                $query->where('tickets.user_id', auth()->user()->id)->orWhereIn('tickets.team_id', $teamsTickets[0]);
                $query->where(function ($query) use ($teamsTickets) {
                $query->where('tickets.user_id', auth()->user()->id)->orWhereIn('tickets.team_id', $teamsTickets[0]);
                });
            }

        }

        if (static::$sortable) {
            Sort::apply($query, static::$sortField, 'ASC');
        } else if (request('sort')) {
            Sort::apply($query, request('sort'), request('sort_order'));
        } else {
            Sort::apply($query, static::$defaultSort, static::$defaultOrder);
        }

        if (request('filters')) {
            Filters::applyFromRequest($query, request('filters'));
        }
        return $query;
    }

    protected function getBaseQuery()
    {

        return TicketsIndexQuery::get()->
        select('tickets.id as id', 'request_type', 'requester_id', 'tickets.team_id', 'tickets.user_id', 'tickets.created_at as created_at', 'tickets.updated_at as updated_at', 'tickets.status', 'tickets.priority', 'tickets.view', 'tickets.updated_by')->
        leftjoin('users', 'users.id', '=', 'tickets.user_id')->
        leftjoin('teams', 'teams.id', '=', 'tickets.team_id')->
        leftjoin('requesters', 'requesters.id', '=', 'tickets.requester_id')->with($this->getWithFields());
    }

    protected function getBaseQuerySearch()
    {

        $query = TicketsIndexQuery::get()->
        select('tickets.id as id', 'request_type', 'requester_id', 'tickets.team_id', 'tickets.user_id', 'tickets.created_at as created_at', 'tickets.updated_at as updated_at', 'tickets.status', 'tickets.priority', 'tickets.view', 'tickets.updated_by')->
        leftjoin('users', 'users.id', '=', 'tickets.user_id')->
        leftjoin('teams', 'teams.id', '=', 'tickets.team_id')->
        leftjoin('requesters', 'requesters.id', '=', 'tickets.requester_id');
        if (auth()->user()->admin) {
            $query->with($this->getWithFields());
        } else {
            $query->where('tickets.team_id', auth()->user()->id)->with($this->getWithFields());
        }
        return $query;
    }

    public function update($id, $newData)
    {
        return parent::update($id, array_except($newData, ['created_at', 'updated_at']));
    }

    public function mainActions()
    {
        return [
            new NewTicket,
        ];
    }

    public function actions()
    {
        return [
            new MergeTickets,
            new ChangeStatus,
            new ChangePriority,
            new AssignTickets,
        ];
    }

    public function filters()
    {
        return [
            new StatusFilter,
            new PriorityFilter,
//            new EscalatedFilter,
        ];
    }

}
