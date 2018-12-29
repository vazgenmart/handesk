<?php

namespace App\Http\Controllers;

use Dacastro4\LaravelGmail\Facade\LaravelGmail;

class HomeController extends Controller
{
    public function form1()
    {
        return view('home.form1');
    }

    public function form2()
    {
        return view('home.form2');
    }
}
