<?php
use App\Models\Category;
use App\Models\Notification;
use App\Models\ManagedCategory;

if ($roles->contains('moderator') || $roles->contains('editor')) {
    $managedCategories = ManagedCategory::where('user_id', Auth::user()->id)->get();
    $categoryIds = $managedCategories->pluck('category_id');
    $categories = Category::whereIn('id', $categoryIds)->get();
    $currentCategory = $categories->first();

    $categories = Category::all();
    $currentCategory = $categories->first();

    $notifications = Notification::whereIn('for', ['admin', 'both'])->whereJsonDoesntContain(
        'isDeletedBy',
        Auth::user()->id
    )->orderBy('created_at', 'DESC')->get();
    $unreadNotifications = Notification::whereJsonDoesntContain(
        'isReadBy',
        Auth::user()->id
    )->whereJsonDoesntContain('isDeletedBy', Auth::user()->id)->whereIn('for', ['admin', 'both'])->count();


} else if ($roles->contains('admin')) {
    $categories = Category::all();
    $currentCategory = $categories->first();

    $notifications = Notification::whereIn('for', ['admin', 'both'])->whereJsonDoesntContain(
        'isDeletedBy',
        Auth::user()->id
    )->orderBy('created_at', 'DESC')->get();
    $unreadNotifications = Notification::whereJsonDoesntContain(
        'isReadBy',
        Auth::user()->id
    )->whereJsonDoesntContain('isDeletedBy', Auth::user()->id)->whereIn('for', ['admin', 'both'])->count();

} else if ($roles->contains('viewer')) {
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

    $unreadNotifications = Notification::whereJsonDoesntContain('isReadBy', Auth::user()->id)->where(function ($query) use ($categoryIds) {
        $query->whereIn('category_id', $categoryIds)
            ->orWhereNull('category_id');
    })->whereIn('for', ['staff', 'both'])
        ->orderBy('created_at', 'DESC')
        ->get()->count();

}