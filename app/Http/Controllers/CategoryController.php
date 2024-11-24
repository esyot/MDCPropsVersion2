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

        $currentCategory = null;
        $categoriesIsNull = true;


        if ($roles->contains('superadmin')) {

            $categories = Category::all();
            $currentCategory = $categories->first();

            $notifications = Notification::whereIn('for', ['superadmin', 'all', 'superadmin|admin'])->whereJsonDoesntContain(
                'isDeletedBy',
                Auth::user()->id
            )->orderBy('created_at', 'DESC')->get();

            $unreadNotifications = Notification::whereJsonDoesntContain(
                'isReadBy',
                Auth::user()->id
            )->whereJsonDoesntContain('isDeletedBy', Auth::user()->id)->whereIn('for', ['superadmin', 'all', 'superadmin|admin'])->count();


        } else if ($roles->contains('admin')) {
            $managedCategories = ManagedCategory::where('user_id', Auth::user()->id)->get();
            $categoryIds = $managedCategories->pluck('category_id');
            $categories = Category::whereIn('id', $categoryIds)->get();
            $currentCategory = $categories->first();

            $categories = Category::all();
            $currentCategory = $categories->first();

            $notifications = Notification::whereIn('for', ['admin', 'superadmin|admin', 'admin|staff', 'all'])->whereJsonDoesntContain(
                'isDeletedBy',
                Auth::user()->id
            )->orderBy('created_at', 'DESC')->get();

            $unreadNotifications = Notification::whereJsonDoesntContain(
                'isReadBy',
                Auth::user()->id
            )->whereJsonDoesntContain('isDeletedBy', Auth::user()->id)->whereIn('for', ['admin', 'superadmin|admin', 'admin|staff', 'all'])->count();


        } else if ($roles->contains('staff')) {
            $managedCategories = ManagedCategory::where('user_id', Auth::user()->id)->get();
            $categoryIds = $managedCategories->pluck('category_id');

            $categories = Category::whereIn('id', $categoryIds)->get();

            $currentCategory = $categories->first();

            $notifications = Notification::whereIn('category_id', $categoryIds)->whereIn('for', ['staff', 'admin|staff', 'staff|cashier', 'all'])->whereJsonDoesntContain(
                'isDeletedBy',
                Auth::user()->id
            )->orderBy('created_at', 'DESC')->get();

            $unreadNotifications = Notification::whereIn('category_id', $categoryIds)->whereJsonDoesntContain(
                'isReadBy',
                Auth::user()->id
            )->whereJsonDoesntContain('isDeletedBy', Auth::user()->id)->whereIn('for', ['staff', 'admin|staff', 'staff|cashier', 'all'])->count();


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
        ]);


        $existingCategory = Category::where('title', $request->title)->first();
        if ($existingCategory) {
            return redirect()->back()->with('error', 'Category with this title already exists.');
        }

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


        $category = Category::create([
            'title' => $request->title,
            'folder_name' => $imageFolderName,
        ]);

        $isReadBy = [];
        $isDeletedBy = [];

        Notification::create([
            'icon' => Auth::user()->img,
            'user_id' => Auth::user()->id,
            'category_id' => $category->id,
            'title' => 'Added a new category',
            'description' => 'added a new category named ' . $request->title . '.',
            'redirect_link' => 'categories',
            'for' => 'superadmin|admin',
            'isReadBy' => $isReadBy,
        ]);

        return redirect()->back()->with('success', 'Category has been added successfully!');
    }

    public function update(Request $request, $category_id)
    {
        $request->validate([
            'title' => ['required', 'string'],

        ]);

        $category = Category::find($category_id);

        $category->update([
            'title' => $request->title,

        ]);

        if ($category) {

            return redirect()->back()->with('success', 'Category has been updated successfully!');

        }

    }

    public function delete($id)
    {

        $category = Category::find($id);

        if ($category) {
            Notification::where('category_id', $category->id)->delete();
            $category->delete();

            return redirect()->back()->with('success', 'Category has been deleted successfully!');
        }

        return redirect()->back()->with('error', 'Category not found!');

    }

}
