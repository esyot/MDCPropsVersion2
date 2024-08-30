<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function isRead($id, $redirect_link)
{
        // Find the notification by ID and update its 'isRead' status
        $update = Notification::where('id', $id)->update(['isRead' => true]);

        // Optionally, you can check if the update was successful
        if ($update) {
            // Redirect to the provided link if the update was successful
            return redirect($redirect_link);
        } else {
            // Handle the case where the update failed
            return redirect()->back()->with('error', 'Failed to mark notification as read.');
        }
    }

    public function notificationList($filter)
    {
        if ($filter === 'unread') {
            $notifications = Notification::where('isRead', false)->get();
            return view('pages.partials.notification-list', compact('notifications'));
        }

        if ($filter === 'all') {
            $notifications = Notification::orderBy('created_at', 'DESC')->get();
            return view('pages.partials.notification-list', compact('notifications'));
        }
    
        return view('pages.partials.notification-list', ['notifications' => collect()]);
    }

    public function readAll(){

        $notifications = Notification::where('isRead', false)->update(['isRead' => true]);

        if($notifications){

            return redirect()->back()->with('success', 'All notifications marked as read.');
        }

        return redirect()->back()->with('info', 'There were no unread notifications to be marked as read.');
    }

    public function deleteAll() {
        // Delete all notifications where 'isRead' is false
        $deletedCount = Notification::where('isRead', true)->delete();
    
        // Check if any records were deleted
        if ($deletedCount > 0) {
            return redirect()->back()->with('success', 'All unread notifications have been deleted.');
        }
    
        // If no records were deleted, you might want to handle that case
        return redirect()->back()->with('info', 'There were no unread notifications to delete.');
    }
    
    
}
