<?php

namespace App\Http\Controllers;
use DB;
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
        $current_user_id = Auth::user()->id;

        $latestMessage = Message::where('receiver_id', $current_user_id)->latest()->first();

        if ($latestMessage == null) {
            return redirect()->back()->with('error', 'No conversation was made with this account.');
        }


        $latestContact = Message::where('receiver_id', Auth::user()->id)->first();

        $sender_name = User::where('id', $latestContact->sender_id)->pluck('name')[0];

        $receiver_name = $latestContact ? $latestContact->sender_name : null;

        $receiver_id = Message::where('receiver_id', $current_user_id)->first();
        $sender_id = Message::where('sender_id', $current_user_id)->first();


        $receiver_name = User::where('id', Auth::user()->id)->first()->pluck('name');

        $contacts = DB::table('messages')
            ->select(
                'messages.*',
                'users.*',
                'users.name as sender_name',
                'users.id as sender_id',
                'messages.created_at as created_at',
            )
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


        $unreadMessages = Message::where('receiver_id', Auth::user()->id)->where('isReadByReceiver', false)->count();
        $allMessages = Message::where('receiver_id', Auth::user()->id)->get();


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

        $contact = $latestContact->id;

        return view(
            'admin.pages.messages',
            compact(
                'users',
                'setting',
                'contacts',
                'current_user_id',
                'receiver_id',
                'sender_id',
                'notifications',
                'unreadNotifications',
                'page_title',
                'currentCategory',
                'sender_name',
                'allMessages',
                'unreadMessages',
                'contact'
            )
        );
    }


    public function messageBubble($receiver_id)
    {
        $current_user_id = Auth::user()->id;
        $sender_id = Auth::user()->id;

        $messagesByCurrentUser = Message::where('sender_id', $current_user_id)->where('receiver_id', $receiver_id)->orderBy('created_at', 'ASC')->get();
        $messagesFromOtherUser = Message::where('sender_id', $receiver_id)->where('receiver_id', $sender_id)->orderBy('created_at', 'ASC')->get();

        $receiver_name = User::where('id', Auth::user()->id)->first()->pluck('name');

        $allMessages = $messagesByCurrentUser->concat($messagesFromOtherUser)->sortBy('created_at');

        $message = Message::where('sender_id', $receiver_id)
            ->where('receiver_id', Auth::user()->id)
            ->first();

        // Check if a message is found before proceeding
        if ($message) {
            // Get the sender_id from the message
            $contactId = $message->sender_id;

            // Retrieve the user with the corresponding sender_id
            $user = User::find($contactId);

            // Check if the user exists before trying to access their name
            if ($user) {
                $sender_name = $user->name;
            } else {
                // Handle the case where no user is found
                $sender_name = 'Unknown User';
            }
        } else {
            // Handle the case where no message is found
            $sender_name = 'No messages found';
        }
        return view(
            'admin.partials.message-bubble',
            compact('allMessages', 'sender_id', 'current_user_id', 'receiver_id', 'receiver_name', 'sender_name')
        );
    }
    public function messageReacted($id)
    {
        $message = Message::find($id);

        if (!$message) {
            return redirect()->back()->withErrors(['Message not found.']);
        }

        $reactedBy = is_array($message->reactedBy) ? $message->reactedBy : [];

        if (in_array(Auth::user()->id, $reactedBy)) {
            $reactedBy = array_filter($reactedBy, function ($userId) {
                return $userId !== Auth::user()->id;
            });
            $reactedBy = array_values($reactedBy);
        } else {
            $reactedBy[] = Auth::user()->id;
        }


        $message->update([
            'reactedBy' => $reactedBy,
        ]);

        return redirect()->back();
    }




    public function messageNewSend(Request $request)
    {
        $validatedData = $request->validate([
            'receiver_id' => ['string', 'required'],
            'content' => ['string', 'required']
        ]);

        Message::create([
            'sender_id' => Auth::user()->id,
            'receiver_id' => $validatedData['receiver_id'],
            'content' => $validatedData['content'],
            'type' => 'text',
        ]);

        return redirect()->back()->with('success', 'Message sent successfully!');

    }

    public function messageSend(Request $request)
    {
        $validatedData = $request->validate([
            'replied_message_id' => 'nullable',
            'replied_message_by_id' => 'nullable',
            'replied_message_type' => 'nullable',
            'sender_id' => 'required|string|max:255',
            'receiver_id' => 'required|string|max:255',
            'content' => 'nullable',
            'image-data' => '',
        ]);

        $repliedMessage = null;
        $repliedMessageContent = null;
        $repliedMessageImg = null;
        $repliedSenderId = $validatedData['replied_message_by_id'];
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
                    'replied_message_by_id' => $repliedSenderId,
                    'replied_message' => $repliedMessageType == 'image' ? $repliedMessageImg : $repliedMessageContent,
                    'replied_message_type' => $repliedMessageType,
                    'sender_id' => $validatedData['sender_id'],
                    'receiver_id' => $validatedData['receiver_id'],
                    'content' => $validatedData['content'],
                    'type' => 'image',
                    'img' => $imageFileName,
                ];
            } else {

                $messageData = [
                    'replied_message_by_id' => $repliedSenderId,
                    'replied_message' => $repliedMessageType == 'image' ? $repliedMessageImg : $repliedMessageContent,
                    'replied_message_type' => $repliedMessageType,
                    'sender_id' => $validatedData['sender_id'],
                    'receiver_id' => $validatedData['receiver_id'],
                    'content' => $validatedData['content'],
                    'type' => 'text',
                ];
            }
        } else {
            if ($imageFileName) {

                $messageData = [
                    'replied_message_by_id' => $repliedSenderId,
                    'replied_message' => $repliedMessageType == 'image' ? $repliedMessageImg : $repliedMessageContent,
                    'replied_message_type' => $repliedMessageType,
                    'sender_id' => $validatedData['sender_id'],
                    'receiver_id' => $validatedData['receiver_id'],
                    'content' => null,
                    'type' => 'image',
                    'img' => $imageFileName,
                ];
            } else {

                $messageData = [
                    'replied_message_by_id' => $repliedSenderId,
                    'replied_message' => $repliedMessageType == 'image' ? $repliedMessageImg : $repliedMessageContent,
                    'replied_message_type' => $repliedMessageType,
                    'sender_id' => $validatedData['sender_id'],
                    'receiver_id' => $validatedData['receiver_id'],
                    'content' => 'like',
                    'type' => 'sticker',
                ];
            }
        }

        Message::create($messageData);

        return '';
    }



    public function chatSelected($contact)
    {

        $messageNotRead = Message::where('sender_id', $contact)
            ->where('receiver_id', Auth::user()->id)
            ->update([
                'isReadByReceiver' => true
            ]);


        $messagesByCurrentUser = Message::where('sender_id', Auth::user()->id)->where('receiver_id', $contact)->orderBy('created_at', 'ASC')->get();
        $messagesFromOtherUser = Message::where('sender_id', $contact)->where('receiver_id', Auth::user()->id)->orderBy('created_at', 'ASC')->get();

        $allMessages = $messagesByCurrentUser->concat($messagesFromOtherUser)->sortBy('created_at');


        $messages = Message::where('receiver_id', Auth::user()->id)
            ->orWhere('sender_id', Auth::user()->id)
            ->latest()
            ->get();

        if ($messages->isEmpty()) {

            dd('No messages found.');
        }

        $message = Message::where('sender_id', $contact)
            ->where('receiver_id', Auth::user()->id)
            ->first();

        // Check if a message is found before proceeding
        if ($message) {
            // Get the sender_id from the message
            $contactId = $message->sender_id;

            // Retrieve the user with the corresponding sender_id
            $user = User::find($contactId);

            // Check if the user exists before trying to access their name
            if ($user) {
                $sender_name = $user->name;
            } else {
                // Handle the case where no user is found
                $sender_name = 'Unknown User';
            }
        } else {
            // Handle the case where no message is found
            $sender_name = 'No messages found';
        }

        $receiver_name = User::where('id', Auth::user()->id)->first()->pluck('name');

        $contacts = DB::table('messages')
            ->select(
                'messages.*',
                'users.*',
                'users.name as sender_name',
                'users.id as sender_id',
                'messages.created_at as created_at',
            )
            ->join('users', 'users.id', '=', 'messages.sender_id')
            ->where(function ($query) {
                $query->where('messages.receiver_id', Auth::user()->id);
            })
            ->whereIn('messages.id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('messages')
                    ->groupBy('sender_id', 'receiver_id');
            })
            ->orderBy('messages.created_at', 'desc') // Order by the most recent message first
            ->get();

        $receiver_id = $contact;
        $sender_id = Auth::user()->id;

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

        $messages = Message::where('receiver_id', Auth::user()->id)->where('isReadByReceiver', false)->get();

        $unreadMessages = $messages->count();
        $setting = Setting::where('user_id', Auth::user()->id)->first();

        $users = User::whereNot('name', Auth::user()->id)->get();

        $current_user_id = Auth::user()->id;
        return view('admin.pages.messages', compact(
            'setting',
            'currentCategory',
            'users',
            'unreadMessages',
            'contacts',
            'current_user_id',
            'receiver_id',
            'sender_id',
            'notifications',
            'unreadNotifications',
            'page_title',
            'allMessages',
            'sender_name',
            'receiver_name',
            'contact'
        ));



    }

    public function contacts(Request $request)
    {
        $current_user_id = Auth::user()->id;
        $receiver_id = Auth::user()->id;

        if ($request->searchValue == null) {


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


            return view('admin.partials.contact-list', compact('receiver_id', 'contacts'));



        } else {
            $contacts = DB::table('messages')
                ->select('messages.*', 'users.*', 'users.name as sender_name', 'users.id as sender_id')
                ->join('users', 'users.id', '=', 'messages.sender_id')
                ->where(function ($query) {
                    $query->where('messages.receiver_id', Auth::user()->id);
                })
                ->where(function ($query) use ($request) {

                    $query->where('users.name', 'LIKE', '%' . $request->searchValue . '%');

                })
                ->whereIn('messages.id', function ($query) {
                    $query->select(DB::raw('MAX(id)'))
                        ->from('messages')
                        ->groupBy('sender_id', 'receiver_id');
                })
                ->get();


            return view('admin.partials.contact-list', compact('receiver_id', 'contacts'));


        }

    }

    public function contactsRefresh()
    {

        $contacts = DB::table('messages')
            ->select(
                'messages.*',
                'users.*',
                'users.name as sender_name',
                'users.id as sender_id',
                'messages.created_at as created_at',
            )
            ->join('users', 'users.id', '=', 'messages.sender_id')
            ->where(function ($query) {
                $query->where('messages.receiver_id', Auth::user()->id);
            })
            ->whereIn('messages.id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('messages')
                    ->groupBy('sender_id', 'receiver_id');
            })
            ->orderBy('messages.created_at', 'desc') // Order by the most recent message first
            ->get();

        return view('admin.partials.messages-dropdown', compact(
            'contacts'
        ));
    }

    public function messengerContactsRefresh()
    {
        $contacts = DB::table('messages')
            ->select(
                'messages.*',
                'users.*',
                'users.name as sender_name',
                'users.id as sender_id',
                'messages.created_at as created_at',
            )
            ->join('users', 'users.id', '=', 'messages.sender_id')
            ->where(function ($query) {
                $query->where('messages.receiver_id', Auth::user()->id);
            })
            ->whereIn('messages.id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('messages')
                    ->groupBy('sender_id', 'receiver_id');
            })
            ->orderBy('messages.created_at', 'desc') // Order by the most recent message first
            ->get();

        return view('admin.partials.contact-list', compact(
            'contacts'
        ));
    }


}
