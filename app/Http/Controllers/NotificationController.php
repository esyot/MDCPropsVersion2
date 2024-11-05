<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\ManagedCategory;
use App\Models\Category;
use Auth;

class NotificationController extends Controller
{
    public function isRead($id, $redirect_link, $role)
    {
        $notification = Notification::find($id);

        if (!$notification) {
            return redirect()->back()->with('error', 'Notification not found.');
        }


        if ($role == 'admin' && in_array(Auth::user()->id, $notification->isReadBy ?? [])) {
            return redirect('/admin/' . $redirect_link);
        } else if ($role == 'cashier' && in_array(Auth::user()->id, $notification->isReadBy ?? [])) {
            return redirect('/cashier/' . $redirect_link);

        }


        $isReadByOld = is_array($notification->isReadBy) ? $notification->isReadBy : [];
        $isReadBy = array_merge($isReadByOld, [Auth::user()->id]);

        $notification->update([
            'isReadBy' => $isReadBy,
        ]);

        if ($role == 'cashier') {
            return redirect('cashier/' . $redirect_link);
        } else if ($role == 'admin') {
            return redirect('admin/' . $redirect_link);
        }

    }



    public function notificationList($filter)
    {
        $roles = Auth::user()->getRoleNames();

        $managedCategories = ManagedCategory::where('user_id', Auth::user()->id)->pluck('category_id');
        $categories = Category::whereIn('id', $managedCategories)->get();

        if ($filter === 'unread') {

            if ($roles->contains('superadmin')) {

                $notifications = Notification::whereJsonDoesntContain('isDeletedBy', Auth::user()->id)
                    ->whereIn('for', ['superadmin', 'all'])
                    ->whereJsonDoesntContain('isReadBy', Auth::user()->id)
                    ->orderBy('created_at', 'DESC')->get();


            } else if ($roles->contains('admin')) {

                $notifications = Notification::whereJsonDoesntContain('isReadBy', Auth::user()->id)->get();

            } else if ($roles->contains('staff')) {

                $notifications = Notification::whereIn('category_id', $categories)->whereJsonDoesntContain('isReadBy', Auth::user()->id)->get();

            }



            return view('admin.partials.notification-list', compact('notifications'));

        } else if ($filter === 'all') {

            if ($roles->contains('superadmin')) {

                $notifications = Notification::whereIn('for', ['superadmin', 'all'])->whereJsonDoesntContain('isDeletedBy', Auth::user()->id)->orderBy('created_at', 'DESC')->get();


            } else if ($roles->contains('admin')) {

                $notifications = Notification::whereIn('for', ['admin', 'all'])->orderBy('created_at', 'DESC')->get();

            } else if ($roles->contains('staff')) {

                $notifications = Notification::whereIn('category_id', $categories)->get();

            }

            return view('admin.partials.notification-list', compact('notifications'));
        }
    }

    public function readAll()
    {
        $userId = Auth::user()->id;
        $notifications = Notification::whereJsonDoesntContain('isReadBy', $userId)->get();

        if ($notifications->isEmpty()) {
            return redirect()->back()->with('message', 'No unread notifications.');
        }

        foreach ($notifications as $notification) {

            $isReadBy = $notification->isReadBy ?? [];


            if (!is_array($isReadBy)) {
                $isReadBy = [];
            }


            if (!in_array($userId, $isReadBy)) {
                $isReadBy[] = $userId;
            }


            $notification->update(['isReadBy' => $isReadBy]);
        }

        return redirect()->back()->with('success', 'Notifications marked as read.');
    }


    public function deleteAll()
    {
        $userId = Auth::user()->id;
        $notifications = Notification::whereJsonDoesntContain('isDeletedBy', $userId)->get();

        if ($notifications->isEmpty()) {
            return redirect()->back()->with('message', 'No notifications to delete.');
        }

        foreach ($notifications as $notification) {
            // Get the current 'isDeletedBy' field, it might be an array or null.
            $isDeletedBy = $notification->isDeletedBy ? json_decode($notification->isDeletedBy, true) : [];

            // Ensure it's an array.
            if (!is_array($isDeletedBy)) {
                $isDeletedBy = [];
            }

            // If the user ID is not already in the array, add it.
            if (!in_array($userId, $isDeletedBy)) {
                $isDeletedBy[] = $userId;
            }

            // Update the 'isDeletedBy' field.
            $notification->update(['isDeletedBy' => json_encode($isDeletedBy)]);
        }

        return redirect()->back()->with('success', 'Notifications marked as deleted.');
    }
}