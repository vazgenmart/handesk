<?php

namespace App;

use Carbon\Carbon;
use App\Authenticatable\Admin;
use App\Services\IssueCreator;
use App\Events\TicketCommented;
use App\Notifications\RateTicket;
use App\Authenticatable\Assistant;
use App\Events\TicketStatusUpdated;
use Illuminate\Support\Facades\App;
use App\Notifications\TicketCreated;
use App\Notifications\TicketAssigned;
use App\Notifications\TicketEscalated;
use App\Services\TicketLanguageDetector;
use Illuminate\Database\Eloquent\SoftDeletes;
use function PHPSTORM_META\type;

class Ticket extends BaseModel
{
    use SoftDeletes, Taggable, Assignable, Subscribable, Rateable;

    const STATUS_NEW = 1;
    const STATUS_OPEN = 2;
    const STATUS_PENDING = 3;
    const STATUS_SOLVED = 4;
    const STATUS_CLOSED = 5;
    const STATUS_MERGED = 6;
    const STATUS_SPAM = 7;

    const ADMIN_SEEN = 1;
    const USER_SEEN = 2;
    const NOT_SEEN = 3;
    const SEEN = 0;

    const PRIORITY_LOW = 1;
    const PRIORITY_NORMAL = 2;
    const PRIORITY_HIGH = 3;
    const PRIORITY_BLOCKER = 4;
    const TYPE = ['Right of access by the data subject', 'Right to rectification', 'Right to erasure (‘right to be forgotten’)', 'Right to restriction of processing', 'Right to data portability', 'Right to object', 'Other comment or question'];
    const COUNTRY = ['Austria', 'Belgium', 'Bulgaria', 'Croatia', 'Republic of Cyprus', 'Czech Republic', 'Denmark', 'Estonia', 'Finland', 'France', 'Germany', 'Greece', 'Hungary', 'Ireland', 'Italy', 'Latvia', 'Lithuania', 'Luxembourg', 'Malta', 'Netherlands', 'Poland', 'Portugal', 'Romania', 'Slovakia', 'Slovenia', 'Spain', 'Sweden', 'United Kingdom', 'Other'];

    const REQUEST_TYPE = ['Right of access by the data subject', 'Right to rectification', 'Right to erasure (‘right to be forgotten’)', 'Right to restriction of processing', 'Right to data portability', 'Right to object', 'Other comment or question'];

    public static function createAndNotify($requester, $title, $body, $tags, $imageNamesAddress, $imageNames)
    {
        $requester = Requester::findOrCreate($requester['name'] ?? 'Unknown', $requester['email'] ?? null);
        $ticket = $requester->tickets()->create([
            'title' => substr($title, 0, 190),
            'body' => $body,
            'public_token' => str_random(24),
            'first_name' => request('first_name'),
            'last_name' => request('last_name'),
            'email' => request('email'),
            'phone' => request('phone'),
            'country' => request('country'),
            'address' => request('address'),
            'zip' => request('zip'),
            'city' => request('city'),
            'request_type' => request('request_type'),
            'request_description' => request('request_description'),
            'prof_of_identity' => $imageNamesAddress,
            'prof_of_address' => $imageNames,
        ])->attachTags($tags);

//        tap(new TicketCreated($ticket), function ($newTicketNotification) use ($requester) {
//            Admin::notifyAll($newTicketNotification);
//            $requester->notify($newTicketNotification);
//        });

        return $ticket;
    }

    public static function updateNote($id)
    {
        if (auth()->user()->admin == 1){
            return Ticket::select('view')->where('id', '=', $id)->update(['view' => self::ADMIN_SEEN]);
        }else{
            return Ticket::select('view')->where('id', '=', $id)->update(['view' => self::USER_SEEN]);
        }

    }

    public static function updateNoteByDataUser($id)
    {
        return Ticket::select('view')->where('id', '=', $id)->update(['view' => self::NOT_SEEN]);
    }

    public static function updatedBy($id, $user)
    {
        return Ticket::select('updated_by')->where('id', '=', $id)->update(['updated_by' => $user]);
    }

    public static function updatedByDataUser($id, $user = -1)
    {
        return Ticket::select('updated_by')->where('id', '=', $id)->update(['updated_by' => $user]);
    }

    public function updateWith($requester, $priority)
    {
        $requester = Requester::findOrCreate($requester['name'] ?? 'Unknown', $requester['email'] ?? null);
        $this->update([
            'priority' => $priority,
            'requester_id' => $requester->id,
        ]);

        return $this;
    }

    public static function findWithPublicToken($public_token)
    {
        return self::where('public_token', $public_token)->firstOrFail();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function requester()
    {
        return $this->belongsTo(Requester::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function comments()
    {
        return $this->commentsAndNotes()->where('private', false);
    }

    public function commentsAndNotes()
    {
        return $this->hasMany(Comment::class)->latest();
    }

    public function commentsAndNotesAndEvents()
    {
        return $this->commentsAndNotes->toBase()->merge($this->events);
    }

    public function events()
    {
        return $this->hasMany(TicketEvent::class)->latest();
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function mergedTickets()
    {
        return $this->belongsToMany(self::class, 'merged_tickets', 'ticket_id', 'merged_ticket_id');
    }

    /**
     * @param $user
     * @param $newStatus
     *
     * @return mixed
     */
    private function updateStatusFromComment($user, $newStatus)
    {
        $previousStatus = $this->status;
        if ($newStatus && $newStatus != $previousStatus) {
            $this->updateStatus($newStatus);
        } elseif (!$user && $this->status != static::STATUS_NEW) {
            $this->updateStatus(static::STATUS_OPEN);
        } else {
            $this->touch();
        }
        event(new TicketStatusUpdated($this, $user, $previousStatus));

        return $previousStatus;
    }

    private function associateUserIfNecessary($user)
    {
        if (!$this->user && $user) {
            $this->user()->associate($user)->save();
        }
    }

    protected function getAssignedNotification()
    {
        return new TicketAssigned($this);
    }

    public function addComment($user, $body, $newStatus = null)
    {
        if ($user && $this->isEscalated()) {
            return $this->addNote($user, $body);
        }

        $previousStatus = $this->updateStatusFromComment($user, $newStatus);

        $this->associateUserIfNecessary($user);
        if (!$body) {
            return;
        }
        $comment = $this->comments()->create([
            'body' => $body,
            'user_id' => $user ? $user->id : null,
            'new_status' => $newStatus ?: $this->status,
        ]);//->notifyNewComment();

        event(new TicketCommented($this, $comment, $previousStatus));

        return $comment;
    }

    public function addCommentFromEmail($body, $ticket_id)
    {

        $comment = new Comment();
        $comment->body = $body;
        $comment->new_status = true;
        $comment->ticket_id = $ticket_id;
        $comment->save();
        // event(new TicketCommented($this, $comment, $previousStatus = null));

        return $comment;
    }

    public function addNote($user, $body)
    {
        if (!$body) {
            return null;
        }
        if (!$this->user && $user) {
            $this->user()->associate($user)->save();
        }  //We don't want the notes to automatically assign the user
        else {
            $this->touch();
        }

        return $this->comments()->create([
            'body' => $body,
            'user_id' => $user->id,
            'new_status' => $this->status,
            'private' => true,
        ]);//->notifyNewNote();
    }

    public function merge($user, $tickets)
    {
        collect($tickets)->map(function ($ticket) {
            return is_numeric($ticket) ? Ticket::find($ticket) : $ticket;
        })->reject(function ($ticket) {
            return $ticket->id == $this->id || $ticket->status > Ticket::STATUS_SOLVED;
        })->each(function ($ticket) use ($user) {
            $ticket->addNote($user, "Merged with #{$this->id}");
            $ticket->updateStatus(Ticket::STATUS_MERGED);
            $this->mergedTickets()->attach($ticket);
            $admin = auth()->user()->name;
            Ticket::updateNote($this->id);
            Ticket::updatedBy($this->id, auth()->user()->id);
            if ($user) {
                \Mail::raw($admin . ' Merged ticket #' . $ticket->id . ' with ticket #' . $this->id . ' at ' . date('Y.m.d H:i'), function ($message) use ($ticket) {
                    $message->from(env('MAIL_USERNAME'), 'ticket@dg-datenschutz.de');
                    $message->to($this->user->email);
                    $message->subject('Changed status #' . $ticket->id);
                });
            }
        });
    }

    public function assign($user, $ticket, $team)
    {
        if (request('user_id')) {
            Ticket::assignTo(request('user_id'), $ticket);
        }
        if (request('team_id')) {
            Ticket::assignToTeam(request('team_id'));
        }

    }

    public function updateStatus($status)
    {
        $this->update(['status' => $status, 'updated_at' => Carbon::now()]);
        $admin = auth()->user()->name;
        if ($this->user && $status != 6) {
            \Mail::raw($admin . ' changed ticket #' . $this->id . ' status to ' . $this->statusName() . ' ' . date('Y.m.d H:i'), function ($message) {
                $message->from(env('MAIL_USERNAME'), 'ticket@dg-datenschutz.de');
                $message->to($this->user->email);
                $message->subject('Changed status #' . $this->id);
            });
        }
        TicketEvent::make($this, 'Status updated: ' . $this->statusName());
        if ($status == Ticket::STATUS_SOLVED && !$this->rating && config('handesk.sendRatingEmail')) {
//            $this->requester->notify((new RateTicket($this))->delay(now()->addMinutes(60)));
        }
    }

    public function updatePriority($priority)
    {
        $this->update(['priority' => $priority, 'updated_at' => Carbon::now()]);
        $admin = auth()->user()->name;
        if ($this->user) {
            \Mail::raw($admin . ' changed ticket #' . $this->id . ' priority to ' . $this->priorityName() . ' ' . date('Y.m.d H:i'), function ($message) {
                $message->from(env('MAIL_USERNAME'), 'ticket@dg-datenschutz.de');
                $message->to($this->user->email);
                $message->subject('Changed priority #' . $this->id);
            });
        }
        TicketEvent::make($this, 'Priority updated: ' . $this->priorityName());
    }

    public function setLevel($level)
    {
        $this->update(['level' => $level]);
        if ($level == 1) {
            TicketEvent::make($this, 'Escalated');

            return Assistant::notifyAll(new TicketEscalated($this));
        }
        TicketEvent::make($this, 'De-Escalated');
    }

    public function isEscalated()
    {
        return $this->level == 1;
    }

    public function hasBeenReplied()
    {
        return $this->comments()->whereNotNull('user_id')->count() > 1;
    }

    public function scopeOpen($query)
    {
        return $query->where('status', '<', self::STATUS_SOLVED);
    }

    public function scopeSolved($query)
    {
        return $query->where('status', '>=', self::STATUS_SOLVED);
    }

    public function canBeEdited()
    {
        return !in_array($this->status, [self::STATUS_CLOSED, self::STATUS_MERGED]);
    }

    public static function statusNameFor($status)
    {
        switch ($status) {
            case static::STATUS_NEW:
                return 'new';
            case static::STATUS_OPEN:
                return 'open';
            case static::STATUS_PENDING:
                return 'pending';
            case static::STATUS_SOLVED:
                return 'solved';
            case static::STATUS_CLOSED:
                return 'closed';
            case static::STATUS_MERGED:
                return 'merged';
            case static::STATUS_SPAM:
                return 'spam';
        }
    }

    public function statusName()
    {
        return static::statusNameFor($this->status);
    }

    public static function priorityNameFor($priority)
    {
        switch ($priority) {
            case static::PRIORITY_LOW:
                return 'low';
            case static::PRIORITY_NORMAL:
                return 'normal';
            case static::PRIORITY_HIGH:
                return 'high';
            case static::PRIORITY_BLOCKER:
                return 'blocker';
        }
    }

    public function priorityName()
    {
        return static::priorityNameFor($this->priority);
    }

    public function getSubscribableEmail()
    {
        return $this->requester->email;
    }

    public function getSubscribableName()
    {
        return $this->requester->name;
    }

    //========================================================
    // ISSUE
    //========================================================
    public function createIssue(IssueCreator $issueCreator, $repository)
    {
        $issue = $issueCreator->createIssue(
            $repository,
            $this->title,
            'Issue from ticket: ' . route('tickets.show', $this) . "   \n\r" . $this->body
        );
        $this->addNote(auth()->user(), "Issue created https://bitbucket.org{$issue->resource_uri} with id #{$issue->local_id}");
        //TODO: Notify somebody? if so, create the test
        TicketEvent::make($this, "Issue created #{$issue->local_id} at {$repository}");

        return $issue;
    }

    public function findIssueNote()
    {
        return $this->commentsAndNotes->first(function ($comment) {
            return starts_with($comment->body, 'Issue created');
        });
    }

    public function getIssueId()
    {
        $issueNote = $this->findIssueNote();
        if (!$issueNote) {
            return null;
        }

        return substr($issueNote->body, strpos($issueNote->body, '#') + 1);
    }

    public function issueUrl()
    {
        $issueNote = $this->findIssueNote();
        if (!$issueNote) {
            return null;
        }
        $start = strpos($issueNote->body, 'https://');
        $end = strpos($issueNote->body, 'with id');
        $apiUrl = substr($issueNote->body, $start, $end - $start);

        return str_replace('api.', '', str_replace('1.0/repositories/', '', $apiUrl));
    }

    public function createIdea()
    {
        $idea = Idea::create([
            'requester_id' => $this->requester_id,
            'title' => $this->title,
            'body' => $this->body,
        ])->attachTags(['ticket']);
        TicketEvent::make($this, "Idea created #{$idea->id}");
        App::setLocale((new TicketLanguageDetector($this))->detect());
        $this->addComment(auth()->user(), __('idea.fromTicket'), self::STATUS_SOLVED);

        return $idea;
    }

    public function getIdeaId()
    {
        $issueEvent = $this->events()->where('body', 'like', '%Idea created%')->first();
        if (!$issueEvent) {
            return null;
        }

        return explode('#', $issueEvent->body)[1];
    }
}
