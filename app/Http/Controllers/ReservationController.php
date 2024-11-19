<?php

namespace App\Http\Controllers;

use App\Models\PropertyReservation;
use App\Models\Rentee;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Notification;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Message;
use App\Models\User;
use App\Models\ManagedCategory;
use Illuminate\Support\Facades\Auth;
use Log;
use Str;


class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $current_user_id = Auth::user()->id;

        $category = Category::all()->first();

        $reservations = PropertyReservation::where('category_id', $category->id)
            ->where('approvedByAdmin_at', null)
            ->where('approvedByCashier_at', null)
            ->where('canceledByRentee_at', null)
            ->where('declinedByAdmin_at', null)
            ->get();

        $categories = Category::all();


        $page_title = 'Transactions';

        $setting = Setting::findOrFail(1);

        $messages = Message::where('receiver_id', $current_user_id)->where('isReadByReceiver', false)->get();

        $unreadMessages = $messages->count();

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


        $currentStatus = 'pending';

        $roles = Auth::user()->getRoleNames();


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

        $users = User::whereNot('name', Auth::user()->name)->get();
        return view('admin.pages.reservations', compact(

            'users',
            'categoriesIsNull',
            'currentStatus',
            'contacts',
            'unreadMessages',
            'setting',
            'page_title',
            'currentCategory',
            'categories',
            'reservations',
            'unreadNotifications',
            'notifications'
        ));

    }
    public function decline(Request $request, $id)
    {

        $reservation = PropertyReservation::find($id);

        if (!$reservation) {
            return redirect()->back()->with('error', 'Reservation not found.');
        }

        $reservation = Reservation::find($reservation->reservation->id);

        if (!$reservation) {
            return redirect()->back()->with('error', 'Reservation not found.');
        }


        $reservations = PropertyReservation::where('reservation_id', $reservation->id)
            ->update([
                'message' => $request->message,
                'declinedByAdmin_at' => now(),
            ]);

        Reservation::where('id', $reservation->id)
            ->update([
                'status' => 'in progress'
            ]);

        $reservationsCount = PropertyReservation::where('reservation_id', $reservation->id)->count();

        $declinedReservations = PropertyReservation::where('reservation_id', $reservation->id)
            ->whereNot('declinedByAdmin_at', null)
            ->count();

        $allDeclined = false;


        if ($declinedReservations == $reservationsCount) {
            $allDeclined = true;
        }

        if ($allDeclined == true) {
            $reservation->update([
                'status' => 'declined',
            ]);
        }


        return redirect()->back()->with('success', 'Transaction has been successfully declined!');
    }

    public function approve($id)
    {

        $reservation = PropertyReservation::find($id);

        $updateData = [
            'approvedByAdmin_at' => now(),
            'admin_id' => Auth::user()->id,
        ];

        if ($reservation->reservation_type == 'borrow') {
            $updateData['approvedByCashier_at'] = now();
        }

        $reservation->update($updateData);


        if ($reservation) {

            Reservation::where('id', $reservation->reservation->id)
                ->update([
                    'status' => 'in progress'
                ]);

            Notification::create([
                'icon' => Auth::user()->img,
                'category_id' => $reservation->category_id,
                'title' => 'Approved Reservation',
                'description' => Auth::user()->name . ' approved a new reservation, check it now!',
                'redirect_link' => 'reservations',
                'for' => 'cashier',
            ]);

            return redirect()->back()->with('success', 'Transaction has been successfuly approved!');
        }

    }

    public function filter(Request $request)
    {
        if ($request->status == 'pending') {
            $reservations = PropertyReservation::where('declinedByAdmin_at', null)
                ->where('approvedByCashier_at', null)
                ->where('approvedByAdmin_at', null)
                ->where('canceledByRentee_at', null)
                ->where('category_id', $request->category)
                ->get();

        } else if ($request->status == 'approved') {
            $reservations = PropertyReservation::where('declinedByAdmin_at', null)
                ->whereNot('approvedByCashier_at', null)
                ->whereNot('approvedByAdmin_at', null)
                ->where('canceledByRentee_at', null)
                ->where('category_id', $request->category)
                ->get();
        } else if ($request->status == 'canceled') {
            $reservations = PropertyReservation::where('declinedByAdmin_at', null)
                ->whereNot('canceledByRentee_at', null)
                ->where('category_id', $request->category)
                ->get();


        } else if ($request->status == 'declined') {
            $reservations = PropertyReservation::whereNot('declinedByAdmin_at', null)->where('category_id', $request->category)->get();

        }


        $current_user_id = Auth::user()->id;

        $category = $request->category;

        $categories = Category::all();

        $page_title = 'Transactions';

        $setting = Setting::findOrFail(1);

        $messages = Message::where('receiver_id', $current_user_id)->where('isReadByReceiver', false)->get();

        $unreadMessages = $messages->count();

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

        $currentCategory = Category::find($request->category);

        $currentStatus = $request->status;
        $roles = Auth::user()->getRoleNames();

        if ($roles->contains('superadmin')) {
            $categories = Category::all();

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

            $notifications = Notification::whereIn('for', ['admin', 'both'])->whereJsonDoesntContain(
                'isDeletedBy',
                Auth::user()->id
            )->orderBy('created_at', 'DESC')->get();
            $unreadNotifications = Notification::whereJsonDoesntContain(
                'isReadBy',
                Auth::user()->id
            )->whereJsonDoesntContain('isDeletedBy', Auth::user()->id)->whereIn('for', ['admin', 'both'])->count();



        } else if ($roles->contains('staff')) {
            $managedCategories = ManagedCategory::where('user_id', Auth::user()->id)->get();
            $categoryIds = $managedCategories->pluck('category_id');

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
        if ($currentCategory) {

            $currentCategoryId = $currentCategory->id;
            $categoriesIsNull = false;
        } else {

            $categoriesIsNull = true;
        }
        $users = User::whereNot('id', Auth::user()->id)->get();


        return view(
            'admin.pages.reservations',
            compact(
                'users',
                'categoriesIsNull',
                'currentStatus',
                'contacts',
                'unreadMessages',
                'setting',
                'page_title',
                'currentCategory',
                'categories',
                'reservations',
                'unreadNotifications',
                'notifications'
            )
        );


    }
    public function create(Request $request)
    {

        $validatedData = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'qty' => 'required|integer|min:1',
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'contact_no' => 'required|max:255',
            'address' => 'required|string|max:500',
            'email' => 'required|email|max:255',
            'destination_id' => 'required|exists:destinations,id',
            'date_start' => 'required|date',
            'time_start' => 'required|date_format:H:i',
            'date_end' => 'required|date|after_or_equal:rent_date',
            'time_end' => 'required|date_format:H:i',
            'purpose' => 'nullable|string|max:255',
            'assigned_personel' => ['string', 'nullable'],
            'reservation_type' => ['string', 'required']
        ]);


        try {

            $dateTime = Carbon::now()->format('Ymd');
            $randomString = Str::random(8);
            $code = $dateTime . '-' . $randomString;


            $rentee = Rentee::create([
                'code' => $code,
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'contact_no' => $validatedData['contact_no'],
                'address' => $validatedData['address']
            ]);

            $renteeId = $rentee->id;


            $trackingCode = now()->format('Ymd') . '-' . substr(bin2hex(random_bytes(4)), 0, 8);


            $reservation = Reservation::create([
                'tracking_code' => $trackingCode,
                'rentee_id' => $renteeId,
                'reservation_type' => $validatedData['reservation_type'],
                'purpose' => $validatedData['purpose'],
            ]);

            $reservationId = $reservation->id;

            PropertyReservation::create([
                'reservation_id' => $reservationId,
                'property_id' => $validatedData['property_id'],
                'destination_id' => $validatedData['destination_id'],
                'category_id' => $validatedData['category_id'],
                'qty' => $validatedData['qty'],
                'date_start' => $validatedData['date_start'],
                'time_start' => $validatedData['time_start'],
                'date_end' => $validatedData['date_end'],
                'time_end' => $validatedData['time_end']
            ]);

            $isReadBy = [];

            Notification::create([
                'icon' => Auth::user()->img,
                'title' => 'New Transaction',
                'category_id' => $validatedData['category_id'],
                'description' => Auth::user()->name . ' added a new transaction.',
                'redirect_link' => 'transactions',
                'for' => 'all',
                'isReadBy' => $isReadBy,
            ]);

            return redirect()->back()->with('success', 'Transaction created successfully.');

        } catch (\Exception $e) {

            dd('Reservation creation error: ' . $e->getMessage(), [
                'user_id' => Auth::user()->id,
                'data' => $request->all(),
            ]);


            return redirect()->back()->with('error', 'Error creating reservation. Please try again later.');
        }
    }

}
