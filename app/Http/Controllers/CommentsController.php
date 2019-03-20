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
//        var_dump($ticket);
//        die;
        $this->authorize('view', $ticket);
        $path = '';
        if (request('private')) {
            $comment = $ticket->addNote(auth()->user(), request('body'));
        } else {
            $comment = $ticket->addComment(auth()->user(), request('body'), request('new_status'));
            if ($comment && request()->hasFile('attachment')) {
                $path = Attachment::storeAttachmentFromRequest(request(), $comment);

            }
//            echo '<pre>';
//            var_dump($ticket->user->email);die;
            if ($comment) {
                $message = $comment->body;
                Mail::raw($message, function ($mes) use ($ticket, $comment, $path) {
                    $mes->from(env('MAIL_USERNAME'));
                    $mes->to($ticket->email)->subject('Ticket#' . $ticket->id);
                    if ($comment && request()->hasFile('attachment')) {
                        $mes->attach(storage_path('app/public/attachments/' . $path));
                    }
                });
            }

            if ($comment && $comment->user->admin == 1){
                $message = $comment->body;
                date_default_timezone_set('Europe/Berlin');
                Mail::raw( $comment->user->name.' commented on '. date('Y.m.d H:i').' on Ticket#' . $ticket->id.': '.'" ' .$message. ' "', function ($mes) use ($ticket, $comment, $path) {
                    $mes->from(env('MAIL_USERNAME'));
                    $mes->to($ticket->user->email)->subject('Ticket#' . $ticket->id . ' You have a new Comment! ');
                    if ($comment && request()->hasFile('attachment')) {
                        $mes->attach(storage_path('app/public/attachments/' . $path));
                    }
                });
            }
        }


        return redirect()->route('tickets.index');
    }

}
