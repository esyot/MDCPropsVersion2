<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function index()
    {
        return view('admin.login.login');
    }

    public function logout(Request $request)
    {
        User::where('id', Auth::user()->id)->update([
            'isLoggedOut_at' => now(),
        ]);

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('loginPage');
    }

    public function login(Request $request)
    {

        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);


        if (Auth::attempt($validatedData)) {

            $user = Auth::user();
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Login successful',
                    'user_id' => $user->id
                ]);
            }

            User::where('id', Auth::user()->id)->update([
                'isLoggedIn_at' => now(),
            ]);

            return redirect()->intended('/admin/dashboard');
        } else {

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'The provided credentials are incorrect.',
                    'username' => $validatedData['email'],
                    'password' => $validatedData['password'],
                ], 401);
            }
            return redirect()->back()->withErrors([
                'email' => 'The provided credentials are incorrect.',
                'username' => $validatedData['email'],
                'password' => $validatedData['password']
            ])->withInput();
        }
    }

}
