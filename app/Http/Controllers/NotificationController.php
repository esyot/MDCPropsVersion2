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

        // Check if the notification exists
        if (!$notification) {
            return redirect()->back()->with('error', 'Notification not found.');
        }

        // Check if the notification is already marked as read by the current user
        if (in_array(Auth::user()->id, $notification->isReadBy)) {
            return redirect('/admin/' . $redirect_link);
        }

        $isReadBy = json_decode($notification->isReadBy != null) ?: [];
        $isReadBy = array_merge($isReadBy, [Auth::user()->id]);

        $notification->update([
            'isReadBy' => $isReadBy,
        ]);

        // Redirect based on whether the update was successful
        if ($notification) {
            return redirect('/admin/' . $redirect_link);
        } else {
            return redirect()->back()->with('error', 'Failed to mark notification as read.');
        }
    }


    public function notificationList($filter)
    {
        $roles = Auth::user()->getRoleNames();
        if ($filter === 'unread') {
            if ($roles->contains('moderator') || $roles->contains('editor')) {
                $managedCategories = ManagedCategory::where('user_id', Auth::user()->id)->get();
                $categoryIds = $managedCategories->pluck('category_id');
                $categories = Category::whereIn('id', $categoryIds)->get();
                $currentCategory = $categories->first();

                $notifications = Notification::whereJsonDoesntContain('isReadBy', Auth::user()->id)->whereIn('for', ['admin', 'both'])->whereJsonDoesntContain('isDeletedBy', Auth::user()->id)->orderBy('created_at', 'DESC')->get();

                return view('admin.partials.notification-list', compact('notifications'));
            } else {
                $notifications = Notification::whereJsonDoesntContain('isReadBy', Auth::user()->id)->orderBy('created_at', 'DESC')->get();
                return view('admin.partials.notification-list', compact('notifications'));
            }
        }

        if ($filter === 'all') {
            if ($roles->contains('moderator') || $roles->contains('editor')) {
                $managedCategories = ManagedCategory::where('user_id', Auth::user()->id)->get();
                $categoryIds = $managedCategories->pluck('category_id');
                $categories = Category::whereIn('id', $categoryIds)->get();
                $currentCategory = $categories->first();

                $notifications = Notification::whereIn('for', ['admin', 'both'])->whereJsonDoesntContain('isDeletedBy', Auth::user()->id)->orderBy('created_at', 'DESC')->get();

                return view('admin.partials.notification-list', compact('notifications'));
            } else {
                $notifications = Notification::orderBy('created_at', 'DESC')->get();
                return view('admin.partials.notification-list', compact('notifications'));
            }
        }

        return view('admin.partials.notification-list', ['notifications' => collect()]);
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
