<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdminLoginRequest;


class SuperAdminLoginController extends Controller
{

    protected $redirectTo = '/super-admin/dashboard';

    public function __construct()
    {
        $this->middleware('guest:super-admin')->except('logout');
    }

    protected function guard()
    {
        return auth()->guard('super-admin');
    }

    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(SuperAdminLoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended($this->redirectTo);
    }


    public function logout(Request $request)
    {
        auth()->guard('super-admin')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/super-admin/login');
    }
}
