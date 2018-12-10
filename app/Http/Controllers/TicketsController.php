<?php

namespace App\Http\Controllers;

use App\Ticket;
use App\Repositories\TicketsIndexQuery;
use App\Repositories\TicketsRepository;
use BadChoice\Thrust\Controllers\ThrustController;

class TicketsController extends Controller
{
    public function index()
    {
        return (new ThrustController)->index('tickets');
    }

    /*public function index(TicketsRepository $repository)
    {
        $ticketsQuery = TicketsIndexQuery::get($repository);
        $ticketsQuery = $ticketsQuery->select('tickets.*')->latest('updated_at');

        return view('tickets.index', ['tickets' => $ticketsQuery->paginate(25, ['tickets.user_id'])]);
    }*/

    public function show(Ticket $ticket)
    {
        $this->authorize('view', $ticket);

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
            'request_type' => 'required',
            'email' => 'required',
            'prof_of_identity' => 'required|file|size:5000',
            'prof_of_address' => 'required|file|size:5000',
            'phone' => 'required',
            'team_id' => 'nullable|exists:teams,id',
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
        }
        if (request('team_id')) {
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
            'requester' => 'required|array',
            'priority' => 'required|integer',
            //'title'      => 'required|min:3',
        ]);
        $ticket->updateWith(request('requester'), request('priority'));

        return back();
    }

}
