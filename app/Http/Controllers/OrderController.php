<?php

namespace App\Http\Controllers;

use App\Mail\mailOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function sendMail(Request $request)
    {
        Mail::to('saquib.rizwan@cloudways.com')->send(
            new mailOrder($request->all())
        );
    }
}
