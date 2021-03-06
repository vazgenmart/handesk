<?php

namespace App\Http\Controllers;

use App\Attachment;
use App\Mail;
use App\Ticket;
use App\Repositories\TicketsIndexQuery;
use App\Repositories\TicketsRepository;
use BadChoice\Thrust\Controllers\ThrustController;
use Dacastro4\LaravelGmail\Facade\LaravelGmail;
use Sunra\PhpSimple\HtmlDomParser;
use Mail as Mailing;
use Illuminate\Http\Request;


class TicketsController extends Controller
{
    public function index()
    {
        $ticket = Ticket::where('status', [1, 2])->get();
//        foreach ($ticket as $mail) {
//            $email = $mail->email;
//            $this->gmail($email);
//        }
        return (new ThrustController)->index('tickets');
    }


    public function show(Ticket $ticket)
    {
        $this->authorize('view', $ticket);
//        $email = $ticket->email;
//        $this->gmail($email);
        return view('tickets.show', ['ticket' => $ticket]);
    }

    public function create()
    {
        return view('tickets.create');
    }

    public function store(Ticket $tickets, \Illuminate\Http\Request $request)
    {
        $this->validate($request, [
//            'requester' => 'required|array',
//            'title'     => 'required|min:3',
//            'body'      => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'country' => 'required',
            'address' => 'required',
            'zip' => 'required',
            'city' => 'required',
            'request_description' => 'required',
            'request_type' => 'required',
            'email' => 'required',
            'prof_of_identity.*' => 'required|mimes:jpg,jpeg,png,bmp,pdf|max:5120',
            'prof_of_address.*' => 'required|mimes:jpg,jpeg,png,bmp,pdf|max:5120',
            'prof_of_identity' => 'required',
            'prof_of_address' => 'required',
            'phone' => 'required',
            'team_id' => 'nullable|exists:teams,id',
        ],
            [
                'prof_of_identity.*.required' => 'Please upload an image',
                'prof_of_identity.*.mimes' => 'Only jpeg,png,pdf and bmp images are allowed',
                'prof_of_identity.*.max' => 'Sorry! Maximum allowed size for an image is 5MB',
                'prof_of_address.*.required' => 'Please upload an image',
                'prof_of_address.*.mimes' => 'Only jpeg,png,pdf and bmp images are allowed',
                'prof_of_address.*.max' => 'Sorry! Maximum allowed size for an image is 5MB',
            ]);

        $imageNames = [];
        $imageNamesAddress = [];

        if ($request->post('first_name')) {
            $images = request()->prof_of_identity;
            $images1 = request()->prof_of_address;
            if (is_array($images) || is_object($images)) {
                foreach ($images as $image) {
                    $extension = $image->getClientOriginalExtension();
                    $imageName1 = time();
                    $imageUniqueName1 = time() . rand(0, 1000000) . '.' . $extension;
                    $image->move(public_path('images'), $imageUniqueName1);
                    $imageNamesAddress[] = $imageUniqueName1;
                }
            }
            if (is_array($images1) || is_object($images1)) {
                foreach ($images1 as $image) {
                    $extension = $image->getClientOriginalExtension();
                    $imageName = time();
                    $imageUniqueName = time() . rand(0, 1000000) . '.' . $extension;
                    $image->move(public_path('images'), $imageUniqueName);
                    $imageNames[] = $imageUniqueName;
                }
            }
        }

        if ($request->post('requester')) {
            $ticket = Ticket::createAndNotify(request('requester'), request('title'), request('body'), request('tags'), json_encode($imageNames), json_encode($imageNamesAddress));
            $ticket->updateStatus(request('status'));
            return view('tickets.show', ['ticket' => $ticket]);
        }
        if (request('team_id')) {
            $ticket = Ticket::createAndNotify(request('requester'), request('title'), request('body'), request('tags'), json_encode($imageNames), json_encode($imageNamesAddress));
            $ticket->assignToTeam(request('team_id'));
        }

        $tickets->create(
            [
                'first_name' => $request->first_name,
//                'status' => $request->status,
                'body' => $request->body,
                'title' => $request->title,
//                'priority' => $request->priority,
                'last_name' => $request->last_name,
                'requester_id' => $request->requester_id,
//                'level' => $request->level,
                'phone' => $request->phone,
                'country' => $request->country,
                'address' => $request->address,
                'zip' => $request->zip,
                'city' => $request->city,
                'prof_of_identity' => json_encode($imageNamesAddress),
                'prof_of_address' => json_encode($imageNames),
                'request_type' => $request->request_type,
                'request_description' => $request->request_description,
                'email' => $request->email,
                'from_site' => $request->from_site,
            ]
        );


        return view('tickets.success');
    }

    public function removeBell(Request $request)
    {
        $id = $request->id;
        $ticket = Ticket::where('id', $id)->first();
        if ($ticket->updated_by != auth()->user()->id) {
            if (auth()->user()->admin == 1) {
                if ($ticket->updated_by == -1 && $ticket->view != Ticket::NOT_SEEN) {
                    $query = Ticket::where('id', $id)->update(['view' => Ticket::SEEN]);
                }  elseif ($ticket->updated_by != -1) {
                    $query = Ticket::where('id', $id)->update(['view' => Ticket::SEEN]);
                }else{
                    $query = Ticket::where('id', $id)->update(['view' => Ticket::ADMIN_SEEN]);
                }
            } else {
                if ($ticket->updated_by == -1 && $ticket->view != Ticket::NOT_SEEN) {
                    $query = Ticket::where('id', $id)->update(['view' => Ticket::SEEN]);
                } elseif ($ticket->updated_by != -1) {
                    $query = Ticket::where('id', $id)->update(['view' => Ticket::SEEN]);
                }else{
                    $query = Ticket::where('id', $id)->update(['view' => Ticket::USER_SEEN]);
                }
            }

        }
        return response()->json($id);
    }

    public
    function reopen(Ticket $ticket)
    {
        $ticket->updateStatus(Ticket::STATUS_OPEN);

        return back();
    }

    public
    function update(Ticket $ticket)
    {
        $this->validate(request(), [
//            'requester' => 'required|array',
            'priority' => 'required|integer',
            //'title'      => 'required|min:3',
        ]);

        $ticket->updateWith(request('requester'), request('priority'));

        return back();
    }

    public function gmail($mail)
    {

        $messages = LaravelGmail::message()
            ->from($mail)
            ->unread()
            ->in('INBOX')
            ->preload()
            ->all();
        foreach ($messages as $key => $message) {
            $messages[] = $message;
            $body[] = $message->getHtmlBody();
            $subjects[] = $message->getSubject();
            $attachments[] = $message->getAttachments();
        }

        if (isset($subjects)) {

            $ids = [];
            $ticket_object = [];
            foreach ($subjects as $key => $subject) {
                $ticket_id = explode('#', $subject);
                $ticket_numb = end($ticket_id);
                $ticket_numb = intval($ticket_numb);
                $ticket = Ticket::where('id', $ticket_numb)->where('email', $mail)->first();

                if (!$ticket) {
                    continue;
                }

                if (!isset($ids[$ticket_numb])) {
                    $ids[$ticket_numb] = $ticket_numb;
                    $ticket_object[$ticket_numb] = $ticket;
                }
                $email = new Mail();
                if (isset($ticket_object[$ticket_numb])) {
                    $email->ticket_id = $ticket_object[$ticket_numb]->id;
                }
                $email->from = $mail;
                $email->subject = $subject;
                $email->text = HTMLDomParser::str_get_html($body[$key])->find('div')[0]->text();
                if ($email->save()) {
                    if (isset($ticket_object[$ticket_numb]) && intval($ticket_numb) != 0) {
                        $model = new Ticket();
                        $comment = $model->addCommentFromEmail($email->text, $ticket_numb);
                        if ($attachments[$key]) {
                            foreach ($attachments[$key] as $attachment) {
                                $attach = new Attachment();
                                $attach->path = $attachment->getFileName();
                                $attach->attachable_type = 'App\Comment';
                                $attach->attachable_id = $comment->id;

                                $attach->save();
                                $attachment->saveAttachmentTo('public/attachments/');

                            }
                        }
                        $path = '';
                        if ($comment) {
                            if ($comment && request()->hasFile('attachment')) {
                                $path = Attachment::storeAttachmentFromRequest(request(), $comment);
                            }
//                            echo '<pre>';
//                            var_dump($ticket->first_name);die;
                            Ticket::updateNoteByDataUser($ticket->id);
                            Ticket::updatedByDataUser($ticket->id, $user = -1);
                            $message = $comment->body;
                            date_default_timezone_set('Europe/Berlin');
                            Mailing::raw($ticket->first_name . ' ' . $ticket->last_name . ' commented on ' . date('Y.m.d H:i') . ' on Ticket#' . $ticket->id . ': ' . '" ' . $message . ' "', function ($mes) use ($ticket, $comment, $path) {
                                $mes->from(env('MAIL_USERNAME'));
                                $mes->to($ticket->user->email)->subject('Ticket#' . $ticket->id . ' You have a new Comment! ');
                                if ($comment && request()->hasFile('attachment')) {
                                    $mes->attach(storage_path('app/public/attachments/' . $path));
                                }
                            });

                        }
                    }
                    $messages[$key]->markAsRead();
                }
            }
        }
    }
}
