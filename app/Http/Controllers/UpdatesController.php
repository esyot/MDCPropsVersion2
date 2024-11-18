<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Auth;
use Illuminate\Http\Request;

class UpdatesController extends Controller
{
    public function notifications()
    {

        $role = Auth::user()->getRoleNames();

        if ($role->contains('superadmin')) {
            $notifications = Notification::whereIN('for', ['superadmin', 'all'])
                ->whereJsonDoesntContain('isReadBy', Auth::user()->id)
                ->get();
        } elseif ($role->contains('admin')) {
            $notifications = Notification::whereIn('for', ['admin', 'all'])
                ->whereJsonDoesntContain('isReadBy', Auth::user()->id)
                ->get();
        } elseif ($role->contains('staff')) {
            $notifications = Notification::whereIn('for', ['staff', 'all'])
                ->whereJsonDoesntContain('isReadBy', Auth::user()->id)
                ->get();

        } elseif ($role->contains('cashier')) {
            $notifications = Notification::whereIn('for', ['cashier', 'all'])
                ->whereJsonDoesntContain('isReadBy', Auth::user()->id)
                ->get();
        }


        return response()->json($notifications);
    }
}
