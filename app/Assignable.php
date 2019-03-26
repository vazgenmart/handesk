<?php
namespace App;


trait Assignable
{
    public function assignTo($user, $ticket)
    {
        if (!$user instanceof User) {
            $user = User::findOrFail($user);
        }
        if ($this->user && $this->user->id == $user->id) {
            return;
        }

        $admin = auth()->user()->name;
        $this->user()->associate($user)->save();
//        date_default_timezone_set('Europe/Berlin');

        \Mail::raw($admin . ' has assigned you ticket #' . $ticket->id . ' ' . date('Y.m.d H:i'), function ($message) use ($ticket) {
            $message->from(env('MAIL_USERNAME'), 'ticket@dg-datenschutz.de');
            $message->to($this->user->email);
            $message->subject('Assigned ticket #' . $ticket->id);
        });
        TicketEvent::make($this, "Assigned to agent: {$user->name}");
    }

    public function assignToTeam($team)
    {
        if (!$team instanceof Team) {
            $team = Team::findOrFail($team);
        }
        if ($this->team && $this->team->id == $team->id) {
            return;
        }
        $this->team()->associate($team)->save();
        // $team->notify($this->getAssignedNotification());
        TicketEvent::make($this, "Assigned to team: {$team->name}");
    }
}
