<?php

namespace App\Http\Controllers;

use App\Forms\Request;


class RequestController extends Controller
{
    public function index()
    {
        $requests = Request::all();
        return view('request.index', compact('requests'));
    }

    public function show($id)
    {
        $requests = Request::Where('id', $id)->get();
        return view('request.show', compact('requests'));
    }

    public function create()
    {
        return view('request.create');
    }


    public function store(Request $requestModel, \Illuminate\Http\Request $request)
    {

        $this->validate($request, [
            'first_name' => 'required',
        ]);

        $imageNames = [];
        $imageNamesAddress = [];
        if ($request->post('first_name')) {
            $images = request()->prof_of_identity;
            $images1 = request()->prof_of_address;

            foreach ($images as $image) {
                $extension = $image->getClientOriginalExtension();
                $imageName1 = time();
                $imageUniqueName1 = time(). rand(0,1000000) . '.' . $extension;
                $image->move(public_path('images'), $imageUniqueName1);
                $imageNamesAddress[] = $imageUniqueName1;
            }
            foreach ($images1 as $image) {
                $extension = $image->getClientOriginalExtension();
                $imageName = time();
                $imageUniqueName = time(). rand(0,1000000) . '.' . $extension;
                $image->move(public_path('images'), $imageUniqueName);
                $imageNames[] = $imageUniqueName;
            }
        }
        $requestModel->create(
            [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone' => $request->phone,
                'country' =>$request->country,
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

        return redirect()->back();
    }
}