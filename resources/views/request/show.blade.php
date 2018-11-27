@extends('layouts.app')

@section('content')
    <table class="table table-bordered">
        @foreach($requests as $request)
            <?php
            $identity = json_decode($request->prof_of_identity);
            $address = json_decode($request->prof_of_address);
            ?>
            <thead>
            <tr>
                <th scope="col" style="font-weight: bold">id</th>
                <th scope="col">{{ $request->id }}</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th scope="row" style="font-weight: bold">First Name</th>
                <td>{{ $request->first_name}}</td>

            </tr>
            <tr>
                <th scope="row" style="font-weight: bold">Last Name</th>
                <td>{{ $request->last_name }}</td>

            </tr>
            <tr>
                <th scope="row" style="font-weight: bold">Email</th>
                <td colspan="2">{{ $request->email }}</td>
            </tr>
            <tr>
                <th scope="row" style="font-weight: bold">Phone Number</th>
                <td colspan="2">{{ $request->phone }}</td>
            </tr>
            <tr>
                <th scope="row" style="font-weight: bold">Country</th>
                <td colspan="2">{{ \App\Forms\Request::COUNTRY[ $request->country] }}</td>
            </tr>
            <tr>
                <th scope="row" style="font-weight: bold">Address</th>
                <td colspan="2">{{ $request->address }}</td>
            </tr>
            <tr>
                <th scope="row" style="font-weight: bold">ZIP Code</th>
                <td colspan="2">{{ $request->zip }}</td>
            </tr>
            <tr>
                <th scope="row" style="font-weight: bold">City</th>
                <td colspan="2">{{ $request->city }}</td>
            </tr>
            <tr>
                <th scope="row" style="font-weight: bold">Request Text</th>
                <td colspan="2">{{ $request->request_description }}</td>
            </tr>
            <tr>
                <th scope="row" style="font-weight: bold">Request Type</th>
                <td colspan="2">{{ \App\Forms\Request::TYPE[ $request->request_type] }}</td>
            </tr>
            <tr>
                <th scope="row" style="font-weight: bold">Created Date</th>
                <td colspan="2">{{ $request->created_at }}</td>
            </tr>
            <tr>
                <th scope="row" style="font-weight: bold">From Site</th>
                <td colspan="2">{{ $request->from_site }}</td>
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
        @endforeach
    </table>
@endsection

