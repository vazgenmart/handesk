@extends('layouts.app')
@section('content')
    <div class="description">
        <h3>{{ trans_choice('Requests' , 2)}} ( {{ $requests->count() }} )</h3>
    </div>


    @paginator($requests)
    <table class="striped">
        <thead>
        <tr>
            <th class="small"></th>
            <th> {{ trans_choice('Id',1) }}             </th>
            <th> {{ trans_choice('First Name',1) }}             </th>
            <th> {{ trans_choice('Last Name',1) }}              </th>
            <th> {{ trans_choice('Email',1) }}       </th>
            <th> {{ trans_choice('Phone',1) }}        </th>
            <th> {{ trans_choice('Country',1) }}             </th>
            <th> {{ trans_choice('Address',1) }}            </th>
            <th> {{ trans_choice('Zip',1) }}            </th>
            <th> {{ trans_choice('City',1) }}            </th>
            <th> {{ trans_choice('Request type',1) }}            </th>
            <th> {{ trans_choice('Created Date',1) }}            </th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($requests as $request)
            <tr>
                <td class="small"> @gravatar($request->id)</td>
                <td>{{ $request->id }}</td>
                <td> {{ $request->first_name     }}</td>
                <td> {{ $request->last_name }} </td>
                <td> {{ $request->email }} </td>
                <td> {{ $request->phone }} </td>
                <td> {{ \App\Forms\Request::COUNTRY[ $request->country] }} </td>
                <td> {{ $request->address }} </td>
                <td> {{ $request->zip }} </td>
                <td> {{ $request->city }} </td>
                <td>{{ \App\Forms\Request::TYPE[ $request->request_type] }}</td>
                <td> {{ $request->created_at }} </td>
                <td>
                    <a href="/show/{{ $request->id }}">@icon(eye)</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @paginator($requests)

@endsection
