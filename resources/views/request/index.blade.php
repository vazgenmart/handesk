@extends('layouts.app')
@section('content')
    <style>
        .iframe_div {
            line-height: 4;
        }

        .iframe_div label {
            width: 115px;
            display: inline-block;
            color: black;
            font-weight: bold;
        }

        .iframe_div input {
            width: 395px;
        }
    </style>
    <div class="description">
        <h3>{{ trans_choice('Requests' , 2)}} ( {{ $requests->count() }} )</h3>
    </div>
    <div class="iframe_div">
        <label for="blue">Blue form Iframe </label>
        <input type="text" id="blue"
               value="<iframe src='https://dg-ticketserver.com/form_blue'></iframe>" readonly>
        <button onclick="myFunction()">Copy text</button>
        <br>
        <label for="black">Black form Iframe</label>
        <input type="text" id="black"
               value="<iframe src='https://dg-ticketserver.com/form_black'></iframe>" readonly>
        <button onclick="myFunction2()">Copy text</button>
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
    <script>
        function myFunction() {
            var copyText = document.getElementById("blue");
            copyText.select();
            document.execCommand("copy");
            // alert("Copied the text: " + copyText.value);
        }
        function myFunction2() {
            var copyText = document.getElementById("black");
            copyText.select();
            document.execCommand("copy");
            // alert("Copied the text: " + copyText.value);
        }
    </script>
@endsection
