<?php

namespace App\Http\Controllers;

use App\Ticket;
use App\Attachment;
use Dacastro4\LaravelGmail\Facade\LaravelGmail;
use Mail;

class CommentsController extends Controller
{
    public function store(Ticket $ticket)
    {

        $this->authorize('view', $ticket);

        if (request('private')) {
            $comment = $ticket->addNote(auth()->user(), request('body'));
        } else {
            $comment = $ticket->addComment(auth()->user(), request('body'), request('new_status'));
            $message = $comment->body;
            Mail::raw($message, function ($mes) use ($ticket) {
                $mes->from(env('MAIL_USERNAME'));
                $mes->to($ticket->email)->subject('Ticket#' . $ticket->id);
            });
        }
        if ($comment && request()->hasFile('attachment')) {
            Attachment::storeAttachmentFromRequest(request(), $comment);

        }

        return redirect()->route('tickets.index');
    }

}
