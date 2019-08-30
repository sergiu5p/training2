<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    protected $fillable = ["username", "password"];

    public function login(Request $request)
    {
        $this->validate($request, [
            "username" => "required|string",
            "password" => "required"
        ]);

        if ($request->username == \config('admin.name') && $request->password == \config('admin.password')) {
            $request->session()->put('login', true);

        } else {
            return view('login', ['message' => "Wrong username or password"]);
        }
    }
}
