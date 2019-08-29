<?php

namespace App\Http\Controllers;

use App\Mail\mailOrder;
use App\Mail\sendMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;


class OrderController extends Controller
{
    public function sendMail(Request $request)
    {
        $this->validate($request, [
            "name" => "required",
            "email" => "required",
            "comments" => "required"
        ]);

        $name = $request->name;
        $email = $request->email;
        $comments = $request->comments;

        Mail::to('purcariu.sergiu@gmail.com')->send(
            new sendMail($name, $email, $comments)
        );
    }
}
