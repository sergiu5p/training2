<?php

namespace App\Http\Controllers;

use App\Mail\sendMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    protected $fillable = [
        "name",
        "email",
        "comments"
    ];

    public function sendMail(Request $request)
    {
        $this->validate($request, [
            "name" => "required|string",
            "email" => "required|email",
            "comments" => "required|"
        ]);

        $name = $request->name;
        $email = $request->email;
        $comments = $request->comments;

        Mail::to('purcariu.sergiu@gmail.com')->send(
            new sendMail($name, $email, $comments)
        );
        $request->session()->forget('cart');
        return redirect()->route('product.show');
    }
}
