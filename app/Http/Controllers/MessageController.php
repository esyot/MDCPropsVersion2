<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Notification;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    public function index()
    {

        $page_title = "Messages";
        $current_user_name = "Reinhard Esteban";
        $sender_name = "Reinhard Esteban";


        $latestMessage = Message::where('receiver_name', $current_user_name)->latest()->first();

        if ($latestMessage) {

            $receiver_name = $latestMessage->sender_name;
        }



        $messagesByCurrentUser = Message::where('sender_name', $current_user_name)->where('receiver_name', $receiver_name)->orderBy('created_at', 'ASC')->get();
        $messagesFromOtherUser = Message::where('sender_name', $receiver_name)->where('receiver_name', $sender_name)->orderBy('created_at', 'ASC')->get();

        $receivers = Message::where('receiver_name', $sender_name)->get();


        $contacts = $receivers->map(function ($receiver) {
            return Message::where('receiver_name', $receiver->receiver_name)
                ->latest()
                ->first();
        });


        $allMessages = $messagesByCurrentUser->concat($messagesFromOtherUser)->sortBy('created_at');

        $notifications = Notification::orderBy('created_at', 'DESC')->get();
        $unreadNotifications = Notification::where('isRead', false)->count();

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
        $setting = Setting::findOrFail(1);

        return view('pages.messages', compact('setting', 'unreadMessages', 'contacts', 'current_user_name', 'receiver_name', 'sender_name', 'notifications', 'unreadNotifications', 'page_title', 'allMessages'));
    }
    public function messageReacted($id)
    {
        // Find the message by ID
        $message = Message::findOrFail($id);

        // Check if the message has been reacted to
        if ($message->isReacted) {
            // If already reacted, set 'isReacted' to false
            $message->update(['isReacted' => false]);
        } else {

            $message->update(['isReacted' => true]);
        }


        return redirect()->back()->with('success', 'added reaction');
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
            'image-data' => 'nullable',
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
            // Extract base64 image data
            $imageData = str_replace(['data:image/png;base64,', ' '], ['', '+'], $request->input('image-data'));
            $imageFileName = \Str::random(10) . '.png';
            $filePath = 'images/' . $imageFileName;
            Storage::disk('public')->put($filePath, base64_decode($imageData));
        }

        if (!is_null($validatedData['content'])) {
            if ($imageFileName) {
                // Both content and image-data are present
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
                // Only content is present
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
                // Only image-data is present
                $messageData = [
                    'replied_message_name' => $repliedMessageName,
                    'replied_message' => $repliedMessageType == 'image' ? $repliedMessageImg : $repliedMessageContent,
                    'replied_message_type' => $repliedMessageType,
                    'sender_name' => $validatedData['sender_name'],
                    'receiver_name' => $validatedData['receiver_name'],
                    'content' => null, // No text content
                    'type' => 'image',
                    'img' => $imageFileName,
                ];
            } else {
                // Neither content nor image-data is present
                $messageData = [
                    'replied_message_name' => $repliedMessageName,
                    'replied_message' => $repliedMessageType == 'image' ? $repliedMessageImg : $repliedMessageContent,
                    'replied_message_type' => $repliedMessageType,
                    'sender_name' => $validatedData['sender_name'],
                    'receiver_name' => $validatedData['receiver_name'],
                    'content' => 'like', // Default text
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

        $current_user_name = 'Reinhard Esteban';

        $messageNotRead = Message::where('sender_name', $contact)->where('receiver_name', $current_user_name);

        $messageNotRead->update([
            'isRead' => true
        ]);






        $messagesByCurrentUser = Message::where('sender_name', $current_user_name)->where('receiver_name', $contact)->orderBy('created_at', 'ASC')->get();
        $messagesFromOtherUser = Message::where('sender_name', $contact)->where('receiver_name', $current_user_name)->orderBy('created_at', 'ASC')->get();

        $allMessages = $messagesByCurrentUser->concat($messagesFromOtherUser)->sortBy('created_at');

        $notifications = Notification::orderBy('created_at', 'DESC')->get();
        $unreadNotifications = Notification::where('isRead', false)->count();

        $contacts = Message::where('receiver_name', $current_user_name)
            ->latest()
            ->get()
            ->groupBy('sender_name')
            ->map(function ($group) {
                return $group->first();
            })
            ->values();

        $receiver_name = $contact;
        $sender_name = $current_user_name;

        $page_title = "Messages";

        $notifications = Notification::orderBy('created_at', 'DESC')->get();
        $unreadNotifications = Notification::where('isRead', false)->count();

        $messages = Message::where('receiver_name', $current_user_name)->where('isRead', false)->get();

        $unreadMessages = $messages->count();




        return view('pages.messages', compact('unreadMessages', 'contacts', 'current_user_name', 'receiver_name', 'sender_name', 'notifications', 'unreadNotifications', 'page_title', 'allMessages'));



    }

    public function contacts(Request $request)
    {

        $current_user_name = 'Reinhard Esteban';
        $receiver_name = 'Reinhard Esteban';

        if ($request->searchValue == null) {

            $contacts = Message::where('receiver_name', $current_user_name)
                ->latest()
                ->get()
                ->groupBy('sender_name')
                ->map(function ($group) {
                    return $group->first();
                })
                ->values();


            return view('pages.partials.contact-list', compact('receiver_name', 'contacts'));



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


            return view('pages.partials.contact-list', compact('receiver_name', 'contacts'));


        }

    }


}
