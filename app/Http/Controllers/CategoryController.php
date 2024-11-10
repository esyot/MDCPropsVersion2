<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Setting;
use App\Models\Notification;
use App\Models\Message;
use App\Models\User;
use App\Models\ManagedCategory;
use DB;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $current_user_name = Auth::user()->name;
        $current_user_id = Auth::user()->id;

        $default = 1;

        $page_title = "Categories";

        $setting = Setting::findOrFail(1);

        $messages = Message::where('receiver_id', $current_user_id)->where('isReadByReceiver', false)->get();
        $unreadMessages = $messages->count();

        $contacts = DB::table('messages')
            ->select('messages.*', 'users.*', 'users.name as sender_name', 'users.id as sender_id')
            ->join('users', 'users.id', '=', 'messages.sender_id')
            ->where(function ($query) {
                $query->where('messages.receiver_id', Auth::user()->id);
            })
            ->whereIn('messages.id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('messages')
                    ->groupBy('sender_id', 'receiver_id');
            })
            ->get();

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


        if ($roles->contains('superadmin')) {

            $categories = Category::all();
            $currentCategory = $categories->first();

            $notifications = Notification::whereIn('for', ['superadmin', 'all'])->whereJsonDoesntContain(
                'isDeletedBy',
                Auth::user()->id
            )->orderBy('created_at', 'DESC')->get();

            $unreadNotifications = Notification::whereJsonDoesntContain(
                'isReadBy',
                Auth::user()->id
            )->whereJsonDoesntContain('isDeletedBy', Auth::user()->id)->whereIn('for', ['superadmin', 'all'])->count();


        } else if ($roles->contains('admin')) {
            $managedCategories = ManagedCategory::where('user_id', Auth::user()->id)->get();
            $categoryIds = $managedCategories->pluck('category_id');
            $categories = Category::whereIn('id', $categoryIds)->get();
            $currentCategory = $categories->first();

            $categories = Category::all();
            $currentCategory = $categories->first();

            $notifications = Notification::whereIn('for', ['admin', 'all'])->whereJsonDoesntContain(
                'isDeletedBy',
                Auth::user()->id
            )->orderBy('created_at', 'DESC')->get();

            $unreadNotifications = Notification::whereJsonDoesntContain(
                'isReadBy',
                Auth::user()->id
            )->whereJsonDoesntContain('isDeletedBy', Auth::user()->id)->whereIn('for', ['admin', 'all'])->count();


        } else if ($roles->contains('staff')) {
            $managedCategories = ManagedCategory::where('user_id', Auth::user()->id)->get();
            $categoryIds = $managedCategories->pluck('category_id');
            $categories = Category::whereIn('id', $categoryIds)->get();
            $currentCategory = $categories->first();

            $notifications = Notification::whereIn('category_id', $categoryIds)->whereIn('for', ['staff', 'all'])->whereJsonDoesntContain(
                'isDeletedBy',
                Auth::user()->id
            )->orderBy('created_at', 'DESC')->get();

            $unreadNotifications = Notification::whereIn('category_id', $categoryIds)->whereJsonDoesntContain(
                'isReadBy',
                Auth::user()->id
            )->whereJsonDoesntContain('isDeletedBy', Auth::user()->id)->whereIn('for', ['staff', 'all'])->count();


        }

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
            'description' => Auth::user()->name . ' added a new category named ' . $request->title . '.',
            'redirect_link' => 'categories',
            'for' => 'superadmin',
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
