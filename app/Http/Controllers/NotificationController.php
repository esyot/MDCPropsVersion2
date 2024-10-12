<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\ManagedCategory;
use App\Models\Category;
use Auth;

class NotificationController extends Controller
{
    public function isRead($id, $redirect_link)
    {
        $notification = Notification::find($id);

        if (!$notification) {
            return redirect()->back()->with('error', 'Notification not found.');
        }


        if (in_array(Auth::user()->id, $notification->isReadBy ?? [])) {
            return redirect('/admin/' . $redirect_link);
        }


        $isReadByOld = is_array($notification->isReadBy) ? $notification->isReadBy : [];
        $isReadBy = array_merge($isReadByOld, [Auth::user()->id]);

        $notification->update([
            'isReadBy' => $isReadBy,
        ]);


        return redirect('/admin/' . $redirect_link);
    }



    public function notificationList($filter, $category)
    {
        $roles = Auth::user()->getRoleNames();


        if ($filter === 'unread') {

            if ($roles->contains('superadmin')) {

                $notifications = Notification::whereJsonDoesntContain('isDeletedBy', Auth::user()->id)->whereIn('for', ['superadmin', 'all'])->
                    whereJsonDoesntContain('isReadBy', Auth::user()->orderBy('created_at', 'DESC')->id)->get();


            } else if ($roles->contains('admin')) {

                $notifications = Notification::where('category_id', $category)->whereJsonDoesntContain('isReadBy', Auth::user()->id)->get();

            } else if ($roles->contains('staff')) {

                $notifications = Notification::where('category_id', $category)->whereJsonDoesntContain('isReadBy', Auth::user()->id)->get();

            }



            return view('admin.partials.notification-list', compact('notifications'));

        } else if ($filter === 'all') {

            if ($roles->contains('superadmin')) {

                $notifications = Notification::whereIn('for', ['superadmin', 'all'])->whereJsonDoesntContain('isDeletedBy', Auth::user()->id)->orderBy('created_at', 'DESC')->get();


            } else if ($roles->contains('admin')) {

                $notifications = Notification::whereIn('for', ['admin', 'all'])->orderBy('created_at', 'DESC')->get();

            } else if ($roles->contains('staff')) {

                $notifications = Notification::where('category_id', $category)->get();

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
        // Get all notifications for the authenticated user
        $notifications = Notification::whereJsonContains('isReadBy', Auth::user()->id)->get();

        // Initialize the array to hold IDs of users who deleted the notifications
        $deletedCount = 0;

        // Loop through each notification and gather existing isDeletedBy data
        foreach ($notifications as $notification) {
            // Ensure isDeletedBy is an array
            $existingDeletedBy = $notification->isDeletedBy ?? [];

            // If $existingDeletedBy is not an array, reset it to an empty array
            if (!is_array($existingDeletedBy)) {
                $existingDeletedBy = [];
            }

            // Merge the current user ID into the array if not already present
            if (!in_array(Auth::user()->id, $existingDeletedBy)) {
                $existingDeletedBy[] = Auth::user()->id; // Add user ID if not already in the array
            }

            // Update the notification with the new isDeletedBy array
            $notification->isDeletedBy = $existingDeletedBy;
            $notification->save();

            // Increment deleted count
            $deletedCount++;
        }

        // Check if any records were updated
        if ($deletedCount > 0) {
            return redirect()->back()->with('success', 'Notifications deleted successfully.');
        }

        // If no records were updated, handle that case
        return redirect()->back()->with('info', 'There were no notifications to delete.');
    }



}
