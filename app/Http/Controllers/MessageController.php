<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Notification;
use App\Models\Setting;
use App\Models\User;
use App\Models\ManagedCategory;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {

        $page_title = "Messages";
        $current_user_name = Auth::user()->name;
        $sender_name = Auth::user()->name;


        $latestMessage = Message::where('receiver_name', $current_user_name)->latest()->first();

        if ($latestMessage) {

            $receiver_name = $latestMessage->sender_name;
        }

        $latestContact = Message::where('receiver_name', Auth::user()->name)->first();

        $receiver_name = $latestContact ? $latestContact->sender_name : null;

        $messagesByCurrentUser = Message::where('sender_name', $current_user_name)->where('receiver_name', $receiver_name)->orderBy('created_at', 'ASC')->get();
        $messagesFromOtherUser = Message::where('sender_name', $receiver_name)->where('receiver_name', $sender_name)->orderBy('created_at', 'ASC')->get();

        $receivers = Message::where('receiver_name', $sender_name)->get();


        $contacts = $receivers->map(function ($receiver) {
            return Message::where('receiver_name', $receiver->receiver_name)
                ->latest()
                ->first();
        });


        $allMessages = $messagesByCurrentUser->concat($messagesFromOtherUser)->sortBy('created_at');

        $messages = Message::where('receiver_name', $current_user_name)->where('isRead', false)->get();

        $unreadMessages = $messages->count();

        $contacts = Message::where('receiver_name', $current_user_name)
            ->latest()
            ->get()
            ->groupBy('sender_name')
            ->map(fn($group) => $group->first())
            ->values();

        $setting = Setting::where('user_id', Auth::user()->id)->first();

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
        $users = User::all();

        return view(
            'admin.pages.messages',
            compact(
                'users',
                'setting',
                'unreadMessages',
                'contacts',
                'current_user_name',
                'receiver_name',
                'sender_name',
                'notifications',
                'unreadNotifications',
                'page_title',
                'allMessages',
                'currentCategory'
            )
        );
    }


    public function messageBubble($receiver_name)
    {
        $current_user_name = Auth::user()->name;
        $sender_name = Auth::user()->name;

        $messagesByCurrentUser = Message::where('sender_name', $current_user_name)->where('receiver_name', $receiver_name)->orderBy('created_at', 'ASC')->get();
        $messagesFromOtherUser = Message::where('sender_name', $receiver_name)->where('receiver_name', $sender_name)->orderBy('created_at', 'ASC')->get();


        $allMessages = $messagesByCurrentUser->concat($messagesFromOtherUser)->sortBy('created_at');

        return view(
            'admin.partials.message-bubble',
            compact('allMessages', 'sender_name', 'current_user_name', 'receiver_name')
        );
    }
    public function messageReacted($id)
    {

        $message = Message::find($id);

        if ($message->isReacted) {
            $message->update(['isReacted' => false]);
        } else {

            $message->update(['isReacted' => true]);
        }


        return redirect()->back()->with('success', 'added reaction');
    }



    public function messageNewSend(Request $request)
    {
        $validatedData = $request->validate([

            'sender_name' => ['string', 'required'],
            'receiver_name' => ['string', 'required'],
            'content' => ['string', 'required']
        ]);

        Message::create([
            'sender_name' => $validatedData['sender_name'],
            'receiver_name' => $validatedData['receiver_name'],
            'content' => $validatedData['content'],
            'type' => 'text',
        ]);

        return redirect()->back()->with('success', 'Message sent successfully!');

    }

    public function messageSend(Request $request)
    {
        $validatedData = $request->validate([
            'replied_message_id' => 'nullable',
            'replied_message_name' => 'nullable',
            'replied_message_type' => 'nullable',
            'sender_name' => 'required|string|max:255',
            'receiver_name' => 'required|string|max:255',
            'content' => 'nullable',
            'image-data' => '',
        ]);

        $repliedMessage = null;
        $repliedMessageContent = null;
        $repliedMessageImg = null;
        $repliedMessageName = $validatedData['replied_message_name'];
        $repliedMessageType = $validatedData['replied_message_type'];

        if ($validatedData['replied_message_id']) {
            $repliedMessage = Message::find($validatedData['replied_message_id']);
            if ($repliedMessage) {
                $repliedMessageContent = $repliedMessage->content;
                $repliedMessageImg = $repliedMessage->img;
            }
        }

        $imageFileName = null;
        if ($validatedData['image-data']) {

            $imageData = $request->input('image-data');


            if (preg_match('/data:image\/(.*?);base64,(.*)/', $imageData, $matches)) {
                $imageFormat = $matches[1];
                $base64Data = str_replace(' ', '+', $matches[2]);


                $validFormats = ['jpeg', 'jpg', 'png', 'gif'];
                if (!in_array($imageFormat, $validFormats)) {

                    return response()->json(['error' => 'Unsupported image format.'], 400);
                }


                $imageFileName = \Str::random(10) . '.' . $imageFormat;
                $filePath = 'images/' . $imageFileName;


                Storage::disk('public')->put($filePath, base64_decode($base64Data));
            } else {

                return response()->json(['error' => 'Invalid image data.'], 400);
            }
        }

        if (!is_null($validatedData['content'])) {
            if ($imageFileName) {

                $messageData = [
                    'replied_message_name' => $repliedMessageName,
                    'replied_message' => $repliedMessageType == 'image' ? $repliedMessageImg : $repliedMessageContent,
                    'replied_message_type' => $repliedMessageType,
                    'sender_name' => $validatedData['sender_name'],
                    'receiver_name' => $validatedData['receiver_name'],
                    'content' => $validatedData['content'],
                    'type' => 'image',
                    'img' => $imageFileName,
                ];
            } else {

                $messageData = [
                    'replied_message_name' => $repliedMessageName,
                    'replied_message' => $repliedMessageType == 'image' ? $repliedMessageImg : $repliedMessageContent,
                    'replied_message_type' => $repliedMessageType,
                    'sender_name' => $validatedData['sender_name'],
                    'receiver_name' => $validatedData['receiver_name'],
                    'content' => $validatedData['content'],
                    'type' => 'text',
                ];
            }
        } else {
            if ($imageFileName) {

                $messageData = [
                    'replied_message_name' => $repliedMessageName,
                    'replied_message' => $repliedMessageType == 'image' ? $repliedMessageImg : $repliedMessageContent,
                    'replied_message_type' => $repliedMessageType,
                    'sender_name' => $validatedData['sender_name'],
                    'receiver_name' => $validatedData['receiver_name'],
                    'content' => null,
                    'type' => 'image',
                    'img' => $imageFileName,
                ];
            } else {

                $messageData = [
                    'replied_message_name' => $repliedMessageName,
                    'replied_message' => $repliedMessageType == 'image' ? $repliedMessageImg : $repliedMessageContent,
                    'replied_message_type' => $repliedMessageType,
                    'sender_name' => $validatedData['sender_name'],
                    'receiver_name' => $validatedData['receiver_name'],
                    'content' => 'like',
                    'type' => 'sticker',
                ];
            }
        }

        Message::create($messageData);

        if ($imageFileName) {
            return back()->with('success', 'File uploaded successfully')->with('path', Storage::url('images/' . $imageFileName));
        } else {
            return back()->with('success', 'Message successfully sent!');
        }
    }



    public function chatSelected($contact)
    {
        $user = Auth::user();
        $current_user_name = $user->name;

        $messageNotRead = Message::where('sender_name', $contact)->where('receiver_name', $current_user_name);

        $messageNotRead->update([
            'isRead' => true
        ]);


        $messagesByCurrentUser = Message::where('sender_name', $current_user_name)->where('receiver_name', $contact)->orderBy('created_at', 'ASC')->get();
        $messagesFromOtherUser = Message::where('sender_name', $contact)->where('receiver_name', $current_user_name)->orderBy('created_at', 'ASC')->get();

        $allMessages = $messagesByCurrentUser->concat($messagesFromOtherUser)->sortBy('created_at');



        $messages = Message::where('receiver_name', $current_user_name)
            ->orWhere('sender_name', $current_user_name)
            ->latest()
            ->get();

        if ($messages->isEmpty()) {

            dd('No messages found.');
        }


        $contacts = Message::where('receiver_name', $current_user_name)
            ->latest()
            ->get()
            ->groupBy('sender_name')
            ->map(fn($group) => $group->first())
            ->values();

        $receiver_name = $contact;
        $sender_name = $current_user_name;

        $page_title = "Messages";

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

            $currentCategoryId = $currentCategory->id;
            $categoriesIsNull = false;
        } else {

            $categoriesIsNull = true;
        }

        $messages = Message::where('receiver_name', $current_user_name)->where('isRead', false)->get();

        $unreadMessages = $messages->count();
        $setting = Setting::findOrFail(1);

        $users = User::whereNot('name', Auth::user()->id)->get();

        return view('admin.pages.messages', compact(
            'setting',
            'currentCategory',
            'users',
            'unreadMessages',
            'contacts',
            'current_user_name',
            'receiver_name',
            'sender_name',
            'notifications',
            'unreadNotifications',
            'page_title',
            'allMessages'
        ));



    }

    public function contacts(Request $request)
    {

        $current_user_name = Auth::user()->name;
        $receiver_name = Auth::user()->name;
        if ($request->searchValue == null) {

            $contacts = Message::where('receiver_name', $current_user_name)
                ->latest()
                ->get()
                ->groupBy('sender_name')
                ->map(fn($group) => $group->first())
                ->values();


            return view('admin.partials.contact-list', compact('receiver_name', 'contacts'));



        } else {

            $contacts = Message::where('receiver_name', $current_user_name)
                ->where('sender_name', 'like', '%' . $request->searchValue . '%')
                ->latest()
                ->get()
                ->groupBy('sender_name')
                ->map(function ($group) {
                    return $group->first();
                })
                ->values();


            return view('admin.partials.contact-list', compact('receiver_name', 'contacts'));


        }

    }



}
