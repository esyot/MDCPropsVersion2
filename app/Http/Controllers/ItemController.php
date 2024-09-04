<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Item;
use App\Models\Setting;
use App\Models\Notification;
use App\Models\Message;

use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ItemController extends Controller
{

    public function index()
    {
        $current_user_name = "Reinhard Esteban";
        $defaultCategoryId = 1;  // Default category ID
        $page_title = "Items";

        // Fetch the default category
        $currentCategory = Category::find($defaultCategoryId);

        // Fetch items based on the default category
        $items = $currentCategory ? Item::where('category_id', $currentCategory->id)->get() : Item::all();

        // Fetch all categories
        $categories = Category::all();

        // Fetch settings, assuming there's only one setting with ID 1
        $setting = Setting::findOrFail(1);

        // Fetch notifications and unread notifications count
        $notifications = Notification::orderBy('created_at', 'DESC')->get();
        $unreadNotifications = Notification::where('isRead', false)->count();

        // Fetch messages for the current user and unread messages count
        $messages = Message::where('receiver_name', $current_user_name)->where('isRead', false)->get();
        $unreadMessages = $messages->count();

        // Group messages by sender and get the most recent message from each sender
        $contacts = Message::where('receiver_name', $current_user_name)
            ->latest()
            ->get()
            ->groupBy('sender_name')
            ->map(function ($group) {
                return $group->first();
            })
            ->values();

        // Return the view with the filtered data
        return view('pages.items', compact('contacts', 'notifications', 'unreadMessages', 'unreadNotifications', 'page_title', 'setting', 'categories', 'currentCategory', 'items'));
    }

    public function create(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'qty' => 'required|integer|min:1',
            'category' => 'required|exists:categories,id', // Ensure category exists
            'img' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048' // Validate image file
        ]);

        // Find the category or fail if not found
        $category = Category::findOrFail($validatedData['category']);

        // Handle the uploaded file
        if ($request->hasFile('img')) {
            $image = $request->file('img');

            // Generate a unique filename for the image
            $imageFileName = Str::random(10) . '.' . $image->getClientOriginalExtension();

            // Define the path for storing the image
            $filePath = 'images/categories/' . $category->folder_name;

            // Store the image file
            $image->storeAs($filePath, $imageFileName, 'public');
        } else {
            return redirect()->back()->withErrors(['img' => 'Image file is required.']);
        }

        // Create the new item
        $item = new Item();
        $item->name = $validatedData['name'];
        $item->category_id = $validatedData['category'];
        $item->img = $imageFileName; // Store the image file name
        $item->qty = $validatedData['qty'];
        $item->save(); // Save the item to the database

        // Redirect back with success message
        return redirect()->back()->with('success', 'A new item has been added successfully!');
    }

    public function itemsFilter(Request $request)
    {
        $current_user_name = "Reinhard Esteban";
        $page_title = "Items";

        // Fetch the current category. Use find instead of where + get for a single record.
        $currentCategory = Category::find($request->category);

        // Filter items based on the selected category
        $items = $currentCategory ? Item::where('category_id', $currentCategory->id)->get() : Item::all();

        // Fetch all categories
        $categories = Category::all();

        // Fetch the settings, assuming there is only one setting with ID 1
        $setting = Setting::findOrFail(1);

        // Fetch notifications and unread notifications count
        $notifications = Notification::orderBy('created_at', 'DESC')->get();
        $unreadNotifications = Notification::where('isRead', false)->count();

        // Fetch messages for the current user and unread messages count
        $messages = Message::where('receiver_name', $current_user_name)->where('isRead', false)->get();
        $unreadMessages = $messages->count();

        // Group messages by sender and get the most recent message from each sender
        $contacts = Message::where('receiver_name', $current_user_name)
            ->latest()
            ->get()
            ->groupBy('sender_name')
            ->map(function ($group) {
                return $group->first();
            })
            ->values();

        // Return the view with the filtered data
        return view('pages.items', compact('contacts', 'notifications', 'unreadMessages', 'unreadNotifications', 'page_title', 'setting', 'categories', 'currentCategory', 'items'));
    }


}


