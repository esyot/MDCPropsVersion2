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
        // Find the notification by ID and update its 'isRead' status
        $update = Notification::where('id', $id)->update(['isRead' => true]);

        // Optionally, you can check if the update was successful
        if ($update) {
            // Redirect to the provided link if the update was successful
            return redirect('/admin/' . $redirect_link);
        } else {
            // Handle the case where the update failed
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

                $notifications = Notification::where('isRead', false)->where(function ($query) use ($categoryIds) {
                    $query->whereIn('category_id', $categoryIds)
                        ->orWhereNull('category_id');
                })->whereIn('for', ['staff', 'both'])
                    ->orderBy('created_at', 'DESC')
                    ->get();
                return view('admin.partials.notification-list', compact('notifications'));
            } else {
                $notifications = Notification::where('isRead', false)->orderBy('created_at', 'DESC')->get();
                return view('admin.partials.notification-list', compact('notifications'));
            }
        }

        if ($filter === 'all') {
            if ($roles->contains('moderator') || $roles->contains('editor')) {
                $managedCategories = ManagedCategory::where('user_id', Auth::user()->id)->get();
                $categoryIds = $managedCategories->pluck('category_id');
                $categories = Category::whereIn('id', $categoryIds)->get();
                $currentCategory = $categories->first();

                $notifications = Notification::where(function ($query) use ($categoryIds) {
                    $query->whereIn('category_id', $categoryIds)
                        ->orWhereNull('category_id');
                })->whereIn('for', ['staff', 'both'])
                    ->orderBy('created_at', 'DESC')
                    ->get();
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

        $notifications = Notification::where('isRead', false)->update(['isRead' => true]);

        if ($notifications) {

            return redirect()->back();
        }

        return redirect()->back()->with('info', 'There were no unread notifications to be marked as read.');
    }

    public function deleteAll()
    {
        // Delete all notifications where 'isRead' is false
        $deletedCount = Notification::where('isRead', true)->delete();

        // Check if any records were deleted
        if ($deletedCount > 0) {
            return redirect()->back();

        }

        // If no records were deleted, you might want to handle that case
        return redirect()->back()->with('info', 'There were no unread notifications to delete.');
    }


}
