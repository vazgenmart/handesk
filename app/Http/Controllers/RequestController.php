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

        $this->validate($request, [
            'first_name' => 'required',
        ]);

        $imageNames = [];
        $imageNamesAddress = [];
        if($request->post('first_name')) {
        $images = request()->prof_of_identity;
        $images1 = request()->prof_of_address;

            foreach ($images as $image) {
                $extension = $image->getClientOriginalExtension();
                $imageName = time();
                $imageUniqueName = time() . '.' . $extension;
                $image->move(public_path('images'), $imageUniqueName);
                $imageNamesAddress[] = $imageUniqueName;
            }
            foreach ($images1 as $image) {
                $extension = $image->getClientOriginalExtension();
                $imageName = time();
                $imageUniqueName = time() . '.' . $extension;
                $image->move(public_path('images'), $imageUniqueName);
                $imageNames[] = $imageUniqueName;
            }
        }
        $requestModel->create(
            [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone' => $request->phone,
                'country' => $request->country,
                'address' => $request->address,
                'zip' => $request->zip,
                'city' => $request->city,
                'prof_of_identity' => json_encode($imageNames),
                'prof_of_address' => json_encode($imageNamesAddress),
                'request_type' => $request->request_type,
                'request_description' => $request->request_description,
                'email' => $request->email,
            ]
        );

        return redirect()->route('request.index');
    }
}