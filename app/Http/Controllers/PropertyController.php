<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Property;
use App\Models\Setting;
use App\Models\Notification;
use App\Models\Message;
use App\Models\User;
use App\Models\ManagedCategory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PropertyController extends Controller
{
    public function index()
    {
        $current_user_id = Auth::user()->id;
        $page_title = "Properties";
        $categories = Category::all();
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
        if ($currentCategory) {
            // You can safely access $currentCategory->id here
            $currentCategoryId = $currentCategory->id;
            $categoriesIsNull = false;
        } else {
            // Handle the case where no categories are found
            $categoriesIsNull = true; // or set a default value
        }

        $users = User::whereNot('name', Auth::user()->name)->get();
        $properties = $currentCategory
            ? Property::where('category_id', $currentCategory->id)->orderBy('name', 'ASC')->get()
            : Property::orderBy('name', 'ASC')->get();

        return view('admin.pages.properties', compact(
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
            'properties',
            'roles'
        ));
    }

    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'qty' => 'required|integer|min:1',
            'category' => 'required|exists:categories,id',
            'approval_level' => ['required'],
            'per' => ['string'],
            'price' => ['nullable'],
            'assigned_personel' => ['required', 'string'],
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

        $property = new Property();
        $property->name = $validatedData['name'];
        $property->category_id = $validatedData['category'];
        $property->img = $imageFileName;
        $property->qty = $validatedData['qty'];
        $property->approval_level = $validatedData['approval_level'];

        if ($validatedData['price'] == null) {
            $property->price = null;
        } else {
            $property->price = $validatedData['price'];
        }
        $property->per = $validatedData['per'];
        $property->assigned_personel = $validatedData['assigned_personel'];
        $property->save();


        Notification::create([
            'icon' => Auth::user()->img,
            'title' => 'New Item',
            'description' => Auth::user()->name . ' added a new item named ' . $validatedData['name'] .
                ', check it now.',
            'redirect_link' => 'items',
            'category_id' => $validatedData['category'],
            'for' => 'all',

        ]);

        return redirect()->back()->with('success', 'A new item has been added successfully!');
    }

    public function propertiesFilter(Request $request)
    {
        $current_user_id = Auth::user()->id;
        $page_title = "Properties";
        $currentCategory = Category::find($request->category);

        $properties = $currentCategory ? Property::where('category_id', $currentCategory->id)->get() : Property::all();

        $categories = Category::all();

        $setting = Setting::where('user_id', $current_user_id)->first();

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


        $roles = Auth::user()->getRoleNames();

        if ($roles->contains('superadmin')) {


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
            $managedCategoriesId = $categoryIds->toArray();
            if (in_array($currentCategory->id, $managedCategoriesId)) {
                $categories = Category::whereIn('id', $categoryIds)->get();
            } else {
                return redirect()->back()->with('error', 'You are not granted to access this category!');
            }

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

            $currentCategoryId = $currentCategory->id;
            $categoriesIsNull = false;


        } else {

            $categoriesIsNull = true;
        }

        $users = User::whereNot('id', Auth::user()->id)->get();

        return view('admin.pages.properties', compact(
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
            'properties',
            'roles'
        ));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'update_name' => 'string',
            'update_img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'update_category' => 'required|integer|exists:categories,id',
            'update_qty' => 'integer',
            'update_price' => ['nullable'],
            'update_per' => 'string',
            'update_approval_level' => 'required',
            'update_assigned_personel' => 'string',
        ]);


        $property = Property::find($id);

        if (!$property) {
            return redirect()->back()->with('error', 'Item not found!');
        }

        $currentCategoryFolder = $property->category->folder_name;
        $newCategoryFolder = Category::find($validatedData['update_category'])->folder_name;
        $imageFileName = $property->img;

        if ($request->hasFile('update_img')) {
            $image = $request->file('update_img');
            $imageFileName = Str::random(10) . '.' . $image->getClientOriginalExtension();
            $filePath = 'images/categories/' . $newCategoryFolder;
            $image->storeAs($filePath, $imageFileName, 'public');

            if ($property->img) {
                $oldFilePath = 'images/categories/' . $currentCategoryFolder . '/' . $property->img;
                if (Storage::disk('public')->exists($oldFilePath)) {
                    Storage::disk('public')->delete($oldFilePath);
                }
            }
        } else {
            if ($property->img && $currentCategoryFolder !== $newCategoryFolder) {
                $oldFilePath = 'images/categories/' . $currentCategoryFolder . '/' . $property->img;
                $newFilePath = 'images/categories/' . $newCategoryFolder . '/' . $property->img;

                if (Storage::disk('public')->exists($oldFilePath)) {
                    Storage::disk('public')->move($oldFilePath, $newFilePath);
                }
            }
        }


        if ($request->isAvailableForRenting == 'on') {
            $property->update([
                'name' => $validatedData['update_name'],
                'qty' => $validatedData['update_qty'],
                'per' => $validatedData['update_per'],
                'approval_level' => $validatedData['update_approval_level'],
                'price' => (float) $validatedData['update_price'],
                'img' => $imageFileName,
                'category_id' => $validatedData['update_category'],
                'assigned_personel' => $validatedData['update_assigned_personel']
            ]);


        } else {
            $property->update([
                'name' => $validatedData['update_name'],
                'qty' => $validatedData['update_qty'],
                'approval_level' => $validatedData['update_approval_level'],
                'img' => $imageFileName,
                'category_id' => $validatedData['update_category'],
                'price' => null,
                'assigned_personel' => $validatedData['update_assigned_personel']
            ]);


        }


        return redirect()->back()->with('success', 'Item has been successfully updated!');
    }

    public function search(Request $request, $day, $category_id)
    {
        $items = Item::where('category_id', $category_id)
            ->where('name', 'LIKE', '%' . $request->input('input') . '%')
            ->get();

        return view('admin.partials.item', compact('items', 'day'));
    }

    public function propertySearch(Request $request, $category_id)
    {

        $categories = Category::all();

        if ($request->search_value == null) {

            $properties = Property::where('category_id', $category_id)
                ->get();
        } else {
            $properties = Property::where('category_id', $category_id)
                ->where('name', 'LIKE', '%' . $request->search_value . '%')
                ->get();
        }
        $currentCategory = Category::find($category_id);

        $setting = Setting::where('user_id', Auth::user()->id)->first();

        return view('admin.partials.properties', compact(
            'categories',
            'properties',
            'setting',
            'currentCategory'
        ));

    }

}