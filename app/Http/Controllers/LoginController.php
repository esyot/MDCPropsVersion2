<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        // Check if the user is authenticated
        if (Auth::check()) {
            // Redirect to home or dashboard if already logged in
            return redirect()->route('dashboard'); // Change 'home' to your desired route name
        }

        // Return the login view if not authenticated
        return view('admin.login.login');
    }
}
