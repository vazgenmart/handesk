<?php

namespace App\Http\Controllers;

use Dacastro4\LaravelGmail\Facade\LaravelGmail;

class HomeController extends Controller
{
    public function form1()
    {
       /* $messages = LaravelGmail::message()
            ->from('vazgenmart@gmail.com')
            ->unread()
            ->in('TRASH')
            ->hasAttachment()
            ->all();
        foreach ( $messages as $message ) {
            $body = $message->getHtmlBody();
            $subject = $message->getSubject();
        }
        var_dump($body);die;*/
        return view('home.form1');
    }

    public function form2()
    {
        return view('home.form2');
    }
}
