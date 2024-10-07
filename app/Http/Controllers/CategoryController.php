<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Setting;
use App\Models\Notification;
use App\Models\Message;
use App\Models\User;
use App\Models\ManagedCategory;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $current_user_name = Auth::user()->name;

        $default = 1;

        $page_title = "Categories";

        $setting = Setting::findOrFail(1);

        $messages = Message::where('receiver_name', $current_user_name)->where('isRead', false)->get();
        $unreadMessages = $messages->count();

        $contacts = Message::where('receiver_name', $current_user_name)
            ->latest()
            ->get()
            ->groupBy('sender_name')
            ->map(fn($group) => $group->first())
            ->values();

        $users = User::whereNot('id', Auth::user()->id)->get();
        $users_for_roles = User::all();

        $roles = Auth::user()->getRoleNames();

        $categories_admin = Category::where('approval_level', 1)
            ->orWhere('approval_level', 3)
            ->orderBy('id')
            ->get();

        $categories_staff = Category::where('approval_level', 2)
            ->orWhere('approval_level', 3)
            ->orderBy('id')
            ->get();

        $currentCategory = null;
        $categoriesIsNull = true;


        include app_path('http\controllers\inclusion\inclusion-1.php');



        $categories = Category::all();
        $transactions = [];
        $items = [];
        $daysWithRecords = [];

        $managedCategories = ManagedCategory::all()
            ->groupBy('user_id')
            ->map(function ($group) {
                return $group->pluck('category_id')->toArray();
            })
            ->toArray();

        return view('admin.pages.category', compact(
            'users_for_roles',
            'categoriesIsNull',
            'managedCategories',
            'users',
            'currentCategory',
            'contacts',
            'unreadMessages',
            'notifications',
            'unreadNotifications',
            'page_title',
            'setting',
            'categories'
        ));

    }


    public function create(Request $request)
    {

        $request->validate([
            'files.*' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'title' => 'required|string|max:255',
            'approval_level' => 'required|integer|between:1,3',
        ]);

        $filePaths = [];
        $imageFolderName = Str::random(10);


        if ($request->hasFile('files')) {
            $files = $request->file('files');

            foreach ($files as $file) {

                $imageFileName = Str::random(10) . '.' . $file->getClientOriginalExtension();
                $fileName = time() . '_' . $imageFileName;
                $filePath = $file->storeAs('public/images/categories/' . $imageFolderName, $fileName);
                $filePaths[] = $filePath;
            }
        }


        Category::create([
            'title' => $request->title,
            'approval_level' => $request->approval_level,
            'folder_name' => $imageFolderName,
        ]);

        $isReadBy = [];
        $isDeletedBy = [];

        Notification::create([
            'icon' => Auth::user()->img,
            'title' => 'Added a new category',
            'description' => Auth::user()->name . ' added a new category ' . $request->title,
            'redirect_link' => 'categories',
            'for' => 'admin',
            'isReadBy' => $isReadBy,
        ]);

        return back()->with('success', 'Files uploaded successfully!');
    }

    public function update(Request $request, $category_id)
    {
        $request->validate([
            'title' => ['required', 'string'],
            'approval_level' => ['required']
        ]);

        $category = Category::find($category_id);

        $category->update([
            'title' => $request->title,
            'approval_level' => $request->approval_level
        ]);

        if ($category) {

            return redirect()->back()->with('success', 'Category has been updated successfully!');

        }

    }

}
