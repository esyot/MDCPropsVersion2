<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Notification;
use Auth;
use DB;
use Illuminate\Http\Request;

class UpdatesController extends Controller
{
    public function notifications()
    {
        $role = Auth::user()->getRoleNames();

        if ($role->contains('superadmin')) {
            $notifications = Notification::whereIN('for', ['superadmin', 'superadmin|admin', 'all'])
                ->whereJsonDoesntContain('isReadBy', Auth::user()->id)
                ->get();
        } elseif ($role->contains('admin')) {
            $notifications = Notification::whereIn('for', ['admin', 'superadmin|admin', 'admin|staff', 'all'])
                ->whereJsonDoesntContain('isReadBy', Auth::user()->id)
                ->get();
        } elseif ($role->contains('staff')) {
            $notifications = Notification::whereIn('for', ['staff', 'admin|staff', 'staff|cashier', 'all'])
                ->whereJsonDoesntContain('isReadBy', Auth::user()->id)
                ->get();

        } elseif ($role->contains('cashier')) {
            $notifications = Notification::whereIn('for', ['cashier', 'staff|cashier', 'all'])
                ->whereJsonDoesntContain('isReadBy', Auth::user()->id)
                ->get();
        }

        return response()->json($notifications);
    }


    public function messages()
    {

        $messages = Message::where('receiver_id', Auth::user()->id)
            ->where('isReadByReceiver', false)
            ->get();


        return response()->json($messages);
    }
}
