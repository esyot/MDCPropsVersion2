<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Message;
use App\Models\Notification;
use App\Models\PasswordResetRequest;
use App\Models\Property;
use App\Models\PropertyReservation;
use App\Models\Setting;
use App\Models\User;
use Role;
use Auth;
use DB;
use Illuminate\Http\Request;

class PasswordResetRequestController extends Controller
{
    public function index()
    {
        $current_user_id = Auth::user()->id;

        $messages = Message::where('receiver_id', $current_user_id)->where('isReadByReceiver', false)->get();
        $unreadMessagesCount = $messages->count();

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




        $defaultCategoryId = 1;

        $users = User::whereNot('id', Auth::user()->id)->get();
        $setting = Setting::where('user_id', Auth::user()->id)->first();
        $currentCategory = Category::find($defaultCategoryId);
        $transactions = PropertyReservation::where('category_id', $defaultCategoryId)->get();
        $categories = Category::orderBy('id')->get();
        $properties = Property::where('category_id', $defaultCategoryId)->get();




        $categories = Category::all();
        $currentCategory = $categories->first();

        $notifications = Notification::whereIn('for', ['superadmin', 'superadmin|admin', 'all'])->whereJsonDoesntContain(
            'isDeletedBy',
            Auth::user()->id
        )->orderBy('created_at', 'DESC')->get();

        $unreadNotifications = Notification::whereJsonDoesntContain(
            'isReadBy',
            Auth::user()->id
        )->whereJsonDoesntContain('isDeletedBy', Auth::user()->id)->whereIn('for', ['superadmin', 'superadmin|admin', 'all'])->count();

        if ($currentCategory) {

            $currentCategoryId = $currentCategory->id;
            $categoriesIsNull = false;
        } else {

            $categoriesIsNull = true;
        }


        $passwordResetRequests = PasswordResetRequest::where('passwordChanged_at', null)->orderBy('created_at', 'DESC')->get();

        return view('admin.pages.password-reset-requests', [
            'passwordResetRequests' => $passwordResetRequests,
            'users' => $users,
            'setting' => $setting,
            'current_user_id' => $current_user_id,
            'contacts' => $contacts,
            'unreadMessages' => $unreadMessagesCount,
            'page_title' => 'Password Reset Requests',
            'unreadNotifications' => $unreadNotifications,
            'notifications' => $notifications,
            'currentCategory' => $currentCategory,
            'categories' => $categories,
            'currentDate' => now(),
            'transactions' => $transactions,
        ]);
    }

    public function store(Request $request)
    {
        $registeredEmail = User::where('email', $request->email)->first();

        if ($registeredEmail != null) {
            PasswordResetRequest::create([
                'email' => $request->email,
            ]);

            $user = User::where('email', $request->email)->first();

            Notification::create([
                'icon' => 'user.png',
                'user_id' => $user->id,
                'title' => 'Password Reset Request',
                'description' => ' requested for password reset, reset it now.',
                'redirect_link' => 'password-reset-requests',
                'for' => 'superadmin|admin',
            ]);

            return redirect()->route('loginPage')->with('success', 'Password reset request submitted successfully!');

        }
        return redirect()->route('loginPage')->with('error', 'Email is not registered in the database!');

    }
}
