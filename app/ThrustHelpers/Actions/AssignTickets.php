<?php
/**
 * Created by PhpStorm.
 * User: vazge
 * Date: 3/21/2019
 * Time: 6:08 PM
 */

namespace App\ThrustHelpers\Actions;

use App\Team;
use App\Ticket;
use App\User;
use BadChoice\Thrust\Fields\Integer;
use BadChoice\Thrust\Fields\Select;
use Illuminate\Support\Collection;
use BadChoice\Thrust\Actions\Action;

class AssignTickets extends Action
{

    public function fields()
    {
        $teams = Team::get()->pluck('name', 'id')->toArray();

        $users = User::get()->pluck('name', 'id')->toArray();
        return [
            Select::make('team_id', 'Team')->options(
                $teams, true

            ),
            Select::make('user_id', 'Agent')->options(
                $users, true


            ),
        ];

    }

    public function handle(Collection $objects)
    {
        $ids = explode(',', request('ids'));
        $team = request('team_id');
        $model = Ticket::whereIn('id', $ids)->findOrFail($ids);
        foreach ($model as $item) {
            $item->assign(auth()->user(), $item, $team);
        }
    }


}

;