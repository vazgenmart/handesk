<?php

namespace App\Http\Controllers;

use Dacastro4\LaravelGmail\Facade\LaravelGmail;

class HomeController extends Controller
{
    public function form1()
    {
        if($_SERVER['REMOTE_ADDR'] == '37.252.82.249') {
           /* $messages = LaravelGmail::message()->preload()->all();
            foreach ( $messages as $message ) {
                $body[] = $message->getHtmlBody();
                $subject[] = $message->getSubject();
            }
            var_dump($body,$subject);
            die;*/
        }
        return view('home.form1');
    }

    public function form2()
    {
        return view('home.form2');
    }
}
