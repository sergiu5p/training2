<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    protected $fillable = ["username", "password"];

    public function login(Request $request)
    {
        if ($request->session()->has('login')) {
            return redirect()->route('product.products');
        } else {
            return view('login');
        }
    }

    public function checkLogin(Request $request)
    {
        if ($request->username == \config('admin.name') && $request->password == \config('admin.password')) {
            $request->session()->put('login', true);
            return redirect()->route('product.products');
        } else {
            return view('login', ['message' => "Wrong username or password"]);
        }
    }
}
