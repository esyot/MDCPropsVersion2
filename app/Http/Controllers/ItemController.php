<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Item;
use App\Models\Setting;
use App\Models\Notification;
use App\Models\Message;
use App\Models\User;
use App\Models\ManagedCategory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ItemController extends Controller
{
    public function index()
    {
        $current_user_name = Auth::user()->name;
        $page_title = "Items";
        $categories = Category::all();
        $setting = Setting::findOrFail(1);
        $messages = Message::where('receiver_name', $current_user_name)->where('isRead', false)->get();
        $unreadMessages = $messages->count();

        $contacts = Message::where('receiver_name', $current_user_name)
            ->latest()
            ->get()
            ->groupBy('sender_name')
            ->map(fn($group) => $group->first())
            ->values();

        // Settings and roles
        $setting = Setting::find(1);
        $roles = Auth::user()->getRoleNames();

        $categories = [];
        $unreadNotifications = 0;
        $notifications = [];
        $currentCategory = null;

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
            )->whereJsonDoesntContain('isDeletedBy', Auth::user()->id)->whereIn('for', ['admin', 'both'])->count();


        } else if ($roles->contains('admin')) {
            $managedCategories = ManagedCategory::where('user_id', Auth::user()->id)->get();
            $categoryIds = $managedCategories->pluck('category_id');
            $categories = Category::whereIn('id', $categoryIds)->get();
            $currentCategory = $categories->first();

            $categories = Category::all();
            $currentCategory = $categories->first();

            $notifications = Notification::whereIn('for', ['admin', 'both'])->whereJsonDoesntContain(
                'isDeletedBy',
                Auth::user()->id
            )->orderBy('created_at', 'DESC')->get();
            $unreadNotifications = Notification::whereJsonDoesntContain(
                'isReadBy',
                Auth::user()->id
            )->whereJsonDoesntContain('isDeletedBy', Auth::user()->id)->whereIn('for', ['admin', 'both'])->count();



        } else if ($roles->contains('staff')) {
            $managedCategories = ManagedCategory::where('user_id', Auth::user()->id)->get();
            $categoryIds = $managedCategories->pluck('category_id');
            $categories = Category::whereIn('id', $categoryIds)->get();
            $currentCategory = $categories->first();

            $notifications = Notification::where(function ($query) use ($categoryIds) {
                $query->whereIn('category_id', $categoryIds)
                    ->orWhereNull('category_id');
            })->whereIn('for', ['staff', 'both'])
                ->orderBy('created_at', 'DESC')
                ->get();

            $unreadNotifications = Notification::whereJsonDoesntContain('isReadBy', Auth::user()->id)->where(function ($query) use ($categoryIds) {
                $query->whereIn('category_id', $categoryIds)
                    ->orWhereNull('category_id');
            })->whereIn('for', ['staff', 'both'])
                ->orderBy('created_at', 'DESC')
                ->get()->count();

        }






        if ($currentCategory) {
            // You can safely access $currentCategory->id here
            $currentCategoryId = $currentCategory->id;
            $categoriesIsNull = false;
        } else {
            // Handle the case where no categories are found
            $categoriesIsNull = true; // or set a default value
        }

        $users = User::whereNot('name', Auth::user()->name)->get();
        $items = $currentCategory
            ? Item::where('category_id', $currentCategory->id)->orderBy('name', 'ASC')->get()
            : Item::orderBy('name', 'ASC')->get();

        return view('admin.pages.items', compact(
            'users',
            'categories',
            'categoriesIsNull',
            'contacts',
            'notifications',
            'unreadMessages',
            'unreadNotifications',
            'page_title',
            'setting',
            'categories',
            'currentCategory',
            'items'
        ));
    }

    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'qty' => 'required|integer|min:1',
            'category' => 'required|exists:categories,id',
            'img' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $category = Category::findOrFail($validatedData['category']);

        if ($request->hasFile('img')) {
            $image = $request->file('img');
            $imageFileName = Str::random(10) . '.' . $image->getClientOriginalExtension();
            $filePath = 'images/categories/' . $category->folder_name;
            $image->storeAs($filePath, $imageFileName, 'public');
        } else {
            return redirect()->back()->withErrors(['img' => 'Image file is required.']);
        }

        $item = new Item();
        $item->name = $validatedData['name'];
        $item->category_id = $validatedData['category'];
        $item->img = $imageFileName;
        $item->qty = $validatedData['qty'];
        $item->save();

        Notification::create([
            'icon' => Auth::user()->img,
            'title' => 'Added a new item',
            'description' => Auth::user()->name . ' added a new item named ' . $validatedData['name'] .
                ', check it now.',
            'redirect_link' => 'items',
            'category_id' => $validatedData['category'],
            'for' => 'all',

        ]);

        return redirect()->back()->with('success', 'A new item has been added successfully!');
    }

    public function itemsFilter(Request $request)
    {
        $current_user_name = "Reinhard Esteban";
        $page_title = "Items";
        $currentCategory = Category::find($request->category);
        $items = $currentCategory ? Item::where('category_id', $currentCategory->id)->get() : Item::all();
        $categories = Category::all();
        $setting = Setting::findOrFail(1);

        $messages = Message::where('receiver_name', $current_user_name)->where('isRead', false)->get();
        $unreadMessages = $messages->count();
        $contacts = Message::where('receiver_name', $current_user_name)
            ->latest()
            ->get()
            ->groupBy('sender_name')
            ->map(fn($group) => $group->first())
            ->values();

        // Settings and roles
        $setting = Setting::find(1);
        $roles = Auth::user()->getRoleNames();

        if ($roles->contains('moderator') || $roles->contains('editor')) {
            $managedCategories = ManagedCategory::where('user_id', Auth::user()->id)->get();
            $categoryIds = $managedCategories->pluck('category_id');
            $categories = Category::whereIn('id', $categoryIds)->get();


            $notifications = Notification::where(function ($query) use ($categoryIds) {
                $query->whereIn('category_id', $categoryIds)
                    ->orWhereNull('category_id');
            })->whereIn('for', ['staff', 'both'])
                ->orderBy('created_at', 'DESC')
                ->get();

            $unreadNotifications = Notification::whereJsonDoesntContain('isReadBy', Auth::user()->id)->where(function ($query) use ($categoryIds) {
                $query->whereIn('category_id', $categoryIds)
                    ->orWhereNull('category_id');
            })->whereIn('for', ['staff', 'both'])
                ->orderBy('created_at', 'DESC')
                ->get()->count();

        } else if ($roles->contains('admin')) {
            $categories = Category::all();


            $notifications = Notification::whereIn('for', ['admin', 'both'])->orderBy('created_at', 'DESC')->get();
            $unreadNotifications = Notification::whereJsonDoesntContain('isReadBy', Auth::user()->id)->whereIn('for', ['admin', 'both'])->count();

        } else if ($roles->contains('viewer')) {
            $managedCategories = ManagedCategory::where('user_id', Auth::user()->id)->get();
            $categoryIds = $managedCategories->pluck('category_id');
            $categories = Category::whereIn('id', $categoryIds)->get();

            $notifications = Notification::where(function ($query) use ($categoryIds) {
                $query->whereIn('category_id', $categoryIds)
                    ->orWhereNull('category_id');
            })->whereIn('for', ['staff', 'both'])
                ->orderBy('created_at', 'DESC')
                ->get();

            $unreadNotifications = Notification::whereJsonDoesntContain('isReadBy', Auth::user()->id)->where(function ($query) use ($categoryIds) {
                $query->whereIn('category_id', $categoryIds)
                    ->orWhereNull('category_id');
            })->whereIn('for', ['staff', 'both'])
                ->orderBy('created_at', 'DESC')
                ->get()->count();

        }


        if ($currentCategory) {
            // You can safely access $currentCategory->id here
            $currentCategoryId = $currentCategory->id;
            $categoriesIsNull = false;
        } else {
            // Handle the case where no categories are found
            $categoriesIsNull = true; // or set a default value
        }

        $users = User::all();

        return view('admin.pages.items', compact(
            'users',
            'categories',
            'categoriesIsNull',
            'contacts',
            'notifications',
            'unreadMessages',
            'unreadNotifications',
            'page_title',
            'setting',
            'categories',
            'currentCategory',
            'items'
        ));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'update_name' => 'string',
            'update_qty' => 'string',
            'update_category' => 'required|integer|exists:categories,id',
            'update_img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $item = Item::find($id);

        if (!$item) {
            return redirect()->back()->with('error', 'Item not found!');
        }

        $currentCategoryFolder = $item->category->folder_name;
        $newCategoryFolder = Category::find($validatedData['update_category'])->folder_name;
        $imageFileName = $item->img;

        if ($request->hasFile('update_img')) {
            $image = $request->file('update_img');
            $imageFileName = Str::random(10) . '.' . $image->getClientOriginalExtension();
            $filePath = 'images/categories/' . $newCategoryFolder;
            $image->storeAs($filePath, $imageFileName, 'public');

            if ($item->img) {
                $oldFilePath = 'images/categories/' . $currentCategoryFolder . '/' . $item->img;
                if (Storage::disk('public')->exists($oldFilePath)) {
                    Storage::disk('public')->delete($oldFilePath);
                }
            }
        } else {
            if ($item->img && $currentCategoryFolder !== $newCategoryFolder) {
                $oldFilePath = 'images/categories/' . $currentCategoryFolder . '/' . $item->img;
                $newFilePath = 'images/categories/' . $newCategoryFolder . '/' . $item->img;

                if (Storage::disk('public')->exists($oldFilePath)) {
                    Storage::disk('public')->move($oldFilePath, $newFilePath);
                }
            }
        }

        $item->update([
            'name' => $validatedData['update_name'],
            'qty' => $validatedData['update_qty'],
            'img' => $imageFileName,
            'category_id' => $validatedData['update_category'],
        ]);

        return redirect()->back()->with('success', 'Item has been successfully updated!');
    }

    public function search(Request $request, $day)
    {
        $items = Item::where('name', 'LIKE', '%' . $request->input . '%')->get();
        return view('admin.partials.item', compact('items', 'day'));
    }

}
