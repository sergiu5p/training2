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
        $data = $request->all('username', 'password');

        if ($data['username'] == \config('admin.name') && $data['password'] == \config('admin.password')) {
            $request->session()->put('login', true);

            if ($request->ajax()) {
                return ['success' => true];
            } else {
                return redirect()->route('product.products');
            }
        } else {
            if ($request->ajax()) {
                return ['success' => false];
            } else {
                return view('login', ['message' => "Wrong username or password"]);
            }
        }
    }

    public function logout(Request $request)
    {
        $request->session()->forget('login');
        if ($request->ajax()) {
            return ['success' => true];
        }
        return redirect()->route('login');
    }
}
