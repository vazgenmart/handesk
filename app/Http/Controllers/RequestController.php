<?php

namespace App\Http\Controllers;

use App\Forms\Request;


class RequestController extends Controller
{
    public function index()
    {

        return view('request.index');
    }

    public function show()
    {
        return view('request.show');
    }

    public function create()
    {
        return view('request.create');
    }


    public function store(Request $requestModel, \Illuminate\Http\Request $request)
    {
//        dd($request->all());
        $requestModel->create(
            [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone' => $request->phone,
                'country' => $request->country,
                'address' => $request->address,
                'zip' => $request->zip,
                'city' => $request->city,
                'prof_of_identity' => $request->prof_of_identity,
                'prof_of_address' => $request->prof_of_address,
                'request_type' => $request->request_type,
                'request_description' => $request->request_description,
                'email' => $request->email,
            ]
        );

        return redirect()->route('request.index');
    }
}