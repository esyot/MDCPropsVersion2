<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Notification;
use App\Models\Message;
use App\Models\User;
use Auth;
use Hash;
use Str;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        $setting = Setting::find(1);
        $current_user_name = Auth::user()->name;
        $page_title = "Profile";
        $notifications = Notification::orderBy('created_at', 'DESC')->get();
        $unreadNotifications = Notification::where('isRead', false)->count();

        $messages = Message::where('receiver_name', $current_user_name)->where('isRead', false)->get();
        $unreadMessages = $messages->count();

        $contacts = Message::where('receiver_name', $current_user_name)
            ->latest()
            ->get()
            ->groupBy('sender_name')
            ->map(fn($group) => $group->first())
            ->values();

        $users = User::all();
        return view('admin.pages.profile', compact('users', 'unreadMessages', 'contacts', 'notifications', 'unreadNotifications', 'setting', 'page_title'));

    }

    public function profileUpdate(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'string',
            'img' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'email' => 'email'
        ]);

        $user = User::find(Auth::user()->id);

        // Handle the uploaded file
        if ($request->hasFile('img')) {
            $image = $request->file('img');

            // Generate a unique filename for the image
            $imageFileName = Str::random(10) . '.' . $image->getClientOriginalExtension();

            // Define the path for storing the image
            $filePath = 'images/users/';

            // Store the image file
            $image->storeAs($filePath, $imageFileName, 'public');

            $user->update([
                'img' => $imageFileName
            ]);
            return redirect()->back()->with('success', 'User has been updated successfully!');

        } else {




            $user->update([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],

            ]);

            return redirect()->back()->with('success', 'User has been updated successfully!');
        }
    }

    public function passwordUpdate(Request $request)
    {
        $validatedData = $request->validate([
            'password' => ['required', 'string'],
            'password1' => ['required', 'string', 'min:8'],
            'password2' => ['required', 'string', 'min:8', 'same:password1'],
        ]);

        $user = Auth::user();

        // Verify the current password
        if (Hash::check($validatedData['password'], $user->password)) {
            $user->update([
                'password' => Hash::make($validatedData['password2']),
            ]);

            return redirect()->back()->with('success', 'Password has been updated successfully!');
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect'
            ], 401);
        }

        return redirect()->back()->withErrors([
            'password' => 'Current password is incorrect'
        ])->withInput();



    }


}
