@extends('layouts.app')
@section('content')
    <style>
        .iframe_text {
            text-align: center;
            padding-bottom: 20px;
        }

        .iframe_text > p {
            color: #000000;
            font-size: 20px;
            visibility: hidden;
            position: absolute;
        }

        .textBox {
            height: 30px;
            width: 300px;
        }

        .blue_form {
            width: 170px;
            border-radius: 8px;
            padding: 10px;
            font-size: 20px;
            height: 52px;
            background: #2D6BD7;
            color: white;
        }

        .black_form {
            width: 170px;
            border-radius: 8px;
            padding: 10px;
            font-size: 20px;
            height: 52px;
            background: black;
            color: white;
        }
    </style>
    <div class="description">
        <h3>{{ trans_choice('Requests' , 2)}} ( {{ $requests->count() }} )</h3>
    </div>
    <div class="iframe_text">
        <p id="p1">Hello, I'm TEXT 1</p>
        <p id="p2">Hi, I'm the 2nd TEXT</p><br/>

        <button onclick="copyToClipboard('#p1')" class="blue_form">Copy Blue form</button>
        <button onclick="copyToClipboard('#p2')" class="black_form">Copy Black form</button>
        <div hidden>
            <input class="textBox" type="text" id="" placeholder="Dont belive me?..TEST it here..;)"/>
        </div>
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
        function copyToClipboard(element) {
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val($(element).text()).select();
            document.execCommand("copy");
            $temp.remove();
        }
    </script>
@endsection
