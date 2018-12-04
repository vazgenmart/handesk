@extends('layouts.app')
@section('content')
    <div class="description comment">
        <div class="breadcrumb">
            <a href="{{ route('tickets.index') }}">{{ trans_choice('ticket.ticket', 2) }}</a>
        </div>
        <h3>#{{ $ticket->id }}. {{ $ticket->title }} </h3>
        <div class="mb2">
            @include('components.ticket.rating')
        </div>
        <div id="ticket-info" class="float-left">
            @busy <span
                    class="label ticket-status-{{ $ticket->statusName() }}">{{ __("ticket." . $ticket->statusName() ) }}</span>
            &nbsp;
            @busy <span
                    class="label ticket-priority-{{ $ticket->priorityName() }}">{{ __("ticket." . $ticket->priorityName() ) }}</span>
            &nbsp;
            <span class="date">{{  $ticket->created_at->diffForHumans() }}
                · {{ isset($ticket->requester) ? $ticket->requester->name : '' }}
                &lt;{{ isset($ticket->requester) ? $ticket->requester->email : ''}}&gt;</span>
            <button class="ternary" onClick="$('#ticket-info').hide(); $('#ticket-edit').show()">@icon(pencil)</button>
            {{--<a class="ml4" title="Public Link" href="{{route('requester.tickets.show',$ticket->public_token)}}"> @icon(globe) </a>--}}
        </div>
        <div id="ticket-edit" class="hidden" class="float-left">
            {{ Form::open(["url" => route("tickets.update", $ticket) ,"method" => "PUT"]) }}
            <select name="priority">
                <option value="{{\App\Ticket::PRIORITY_LOW}}"
                        @if($ticket->priority == App\Ticket::PRIORITY_LOW) selected @endif >{{ __("ticket.low") }}</option>
                <option value="{{\App\Ticket::PRIORITY_NORMAL}}"
                        @if($ticket->priority == App\Ticket::PRIORITY_NORMAL) selected @endif >{{ __("ticket.normal") }}</option>
                <option value="{{\App\Ticket::PRIORITY_HIGH}}"
                        @if($ticket->priority == App\Ticket::PRIORITY_HIGH) selected @endif >{{ __("ticket.high") }}</option>
                <option value="{{\App\Ticket::PRIORITY_BLOCKER}}"
                        @if($ticket->priority == App\Ticket::PRIORITY_BLOCKER) selected @endif >{{ __("ticket.blocker") }}</option>
            </select>
            @if($ticket->requester)
                <input name="requester[name]" value="{{$ticket->requester->name }}">
                <input name="requester[email]" value="{{$ticket->requester->email}}">
            @endif
            <button> {{ __("ticket.update")}} </button>
            {{ Form::close() }}
        </div>

        @include('components.ticket.actions')
        <br>
        @include('components.ticket.merged')
    </div>


    @if( $ticket->canBeEdited() )
        @include('components.assignActions', ["endpoint" => "tickets", "object" => $ticket])
        <div class="comment new-comment">
            {{ Form::open(["url" => route("comments.store", $ticket) , "files" => true, "id" => "comment-form"]) }}
            <textarea id="comment-text-area" name="body"></textarea>
            @include('components.uploadAttachment', ["attachable" => $ticket, "type" => "tickets"])
            {{ Form::hidden('new_status', $ticket->status, ["id" => "new_status"]) }}
            @if($ticket->isEscalated() )
                <button class="mt1 uppercase ph3"> @icon(comment) {{ __('ticket.note') }} </button>
            @else
                <div class="mb1">
                    {{ __('ticket.note') }}: {{ Form::checkbox('private') }}
                </div>
                <button class="mt1 uppercase ph3">
                    @icon(comment) {{ __('ticket.commentAs') }} {{ $ticket->statusName() }}</button>
                <span class="dropdown button caret-down"> @icon(caret-down) </span>
                <ul class="dropdown-container">
                    <li><a class="pointer" onClick="setStatusAndSubmit( {{ App\Ticket::STATUS_OPEN    }} )">
                            <div style="width:10px; height:10px"
                                 class="circle inline ticket-status-open mr1"></div> {{ __('ticket.commentAs') }}
                            <b>{{ __("ticket.open") }}   </b> </a></li>
                    <li><a class="pointer" onClick="setStatusAndSubmit( {{ App\Ticket::STATUS_PENDING }} )">
                            <div style="width:10px; height:10px"
                                 class="circle inline ticket-status-pending mr1"></div> {{ __('ticket.commentAs') }}
                            <b>{{ __("ticket.pending") }}</b> </a></li>
                    <li><a class="pointer" onClick="setStatusAndSubmit( {{ App\Ticket::STATUS_SOLVED  }} )">
                            <div style="width:10px; height:10px"
                                 class="circle inline ticket-status-solved mr1"></div> {{ __('ticket.commentAs') }}
                            <b>{{ __("ticket.solved") }} </b> </a></li>
                </ul>
            @endif
            {{ Form::close() }}
        </div>
    @endif

    @include('components.ticketComments', ["comments" => $ticket->commentsAndNotesAndEvents()->sortBy('created_at')->reverse() ])
    <table class="table table-bordered">
        <?php
        $identity = json_decode($ticket->prof_of_identity);
        $address = json_decode($ticket->prof_of_address);
        ?>
        <thead>
        <tr>
            <th scope="col" style="font-weight: bold">id</th>
            <th scope="col">{{ $ticket->id }}</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th scope="row" style="font-weight: bold">First Name</th>
            <td>{{ $ticket->first_name}}</td>

        </tr>
        <tr>
            <th scope="row" style="font-weight: bold">Last Name</th>
            <td>{{ $ticket->last_name }}</td>

        </tr>
        <tr>
            <th scope="row" style="font-weight: bold">Email</th>
            <td colspan="2">{{ $ticket->email }}</td>
        </tr>
        <tr>
            <th scope="row" style="font-weight: bold">Phone Number</th>
            <td colspan="2">{{ $ticket->phone }}</td>
        </tr>
        <tr>
            <th scope="row" style="font-weight: bold">Country</th>
            <td colspan="2">{{ \App\Forms\Request::COUNTRY[ $ticket->country] }}</td>
        </tr>
        <tr>
            <th scope="row" style="font-weight: bold">Address</th>
            <td colspan="2">{{ $ticket->address }}</td>
        </tr>
        <tr>
            <th scope="row" style="font-weight: bold">ZIP Code</th>
            <td colspan="2">{{ $ticket->zip }}</td>
        </tr>
        <tr>
            <th scope="row" style="font-weight: bold">City</th>
            <td colspan="2">{{ $ticket->city }}</td>
        </tr>
        <tr>
            <th scope="row" style="font-weight: bold">Request Text</th>
            <td colspan="2">{{ $ticket->request_description }}</td>
        </tr>
        <tr>
            <th scope="row" style="font-weight: bold">Request Type</th>
            <td colspan="2">{{ \App\Forms\Request::TYPE[ $ticket->request_type] }}</td>
        </tr>
        <tr>
            <th scope="row" style="font-weight: bold">Created Date</th>
            <td colspan="2">{{ $ticket->created_at }}</td>
        </tr>
        <tr>
            <th scope="row" style="font-weight: bold">From Site</th>
            <td colspan="2">{{ $ticket->from_site }}</td>
        </tr>
        <tr>
            <th scope="row" style="font-weight: bold">Prof of Identity</th>
            @foreach($identity as $path)
                <td colspan="2"><img src="/images/{{$path}}" alt="prof_of_identity" width="50%"></td>
            @endforeach
        </tr>
        <tr>
            <th scope="row" style="font-weight: bold">Prof of Address</th>
            @foreach($address as $path)
                <td colspan="2"><img src="/images/{{$path}}" alt="prof_of_address" width="50%"></td>
            @endforeach
        </tr>
        </tbody>
    </table>
@endsection

@section('scripts')
    @include('components.js.taggableInput', ["el" => "tags", "endpoint" => "tickets", "object" => $ticket])

    <script>
        function setStatusAndSubmit(new_status) {
            $("#new_status").val(new_status);
            $("#comment-form").submit();
        }

        $("#comment-text-area").mention({
            delimiter: '@',
            emptyQuery: true,
            typeaheadOpts: {
                items: 10 // Max number of items you want to show
            },
            users: {!! json_encode(App\Services\Mentions::arrayFor(auth()->user())) !!}
        });

    </script>
@endsection