<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function index()
    {       
        return view('auth.login');
    }

    public function authLogin(Request $request)
    {
        $input = $request->all();
        $request->validate([
            'email'    => 'required',
            'password' => 'required',
        ]);
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return  redirect()->route('dashboard')->with('success', 'You have Successfully logged in');
        }else{
            return redirect()->back()->with('error', 'You have entered invalid credentials');
        }
    }

    public function logout()
    {
        Auth::logout();
        Session::flush();
    return  redirect()->route('login')->with('success', 'You have Successfully logged out');
    }

    

}
