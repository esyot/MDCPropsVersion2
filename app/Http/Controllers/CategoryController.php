<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Setting;
use App\Models\Notification;
use App\Models\Message;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $current_user_name = Auth::user()->name;

        $categories = Category::all();

        $default = 1;

        $page_title = "Categories";

        $setting = Setting::findOrFail(1);

        $notifications = Notification::orderBy('created_at', 'DESC')->get();
        $unreadNotifications = Notification::where('isRead', false)->get()->count();

        $messages = Message::where('receiver_name', $current_user_name)->where('isRead', false)->get();
        $unreadMessages = $messages->count();

        $contacts = Message::where('receiver_name', $current_user_name)
            ->latest()
            ->get()
            ->groupBy('sender_name')
            ->map(function ($group) {
                return $group->first();
            })
            ->values();

        $currentCategory = Category::where('id', $default)->get();

        return view('pages.category', compact('currentCategory', 'contacts', 'unreadMessages', 'notifications', 'unreadNotifications', 'page_title', 'setting', 'categories'));

    }


    public function create(Request $request)
    {
        // Validate file uploads
        $request->validate([
            'files.*' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'title' => 'required|string|max:255',
            'approval_level' => 'required|integer|between:1,3',
        ]);

        $filePaths = [];
        $imageFolderName = Str::random(10); // Generate folder name once

        // Handle file uploads
        if ($request->hasFile('files')) {
            $files = $request->file('files');

            foreach ($files as $file) {
                // Generate a unique filename and store the file
                $imageFileName = Str::random(10) . '.' . $file->getClientOriginalExtension();
                $fileName = time() . '_' . $imageFileName;
                $filePath = $file->storeAs('public/images/categories/' . $imageFolderName, $fileName);
                $filePaths[] = $filePath;
            }
        }

        // Create category with the folder name
        Category::create([
            'title' => $request->title,
            'approval_level' => $request->approval_level,
            'folder_name' => $imageFolderName,
        ]);

        return back()->with('success', 'Files uploaded successfully!');
    }

}
