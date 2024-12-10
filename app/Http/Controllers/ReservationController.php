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


        $categories = Category::all();


        $page_title = 'Reservations';

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

            $notifications = Notification::whereIn('for', ['superadmin', 'superadmin|admin', 'all'])->whereJsonDoesntContain(
                'isDeletedBy',
                Auth::user()->id
            )->orderBy('created_at', 'DESC')->get();

            $unreadNotifications = Notification::whereJsonDoesntContain(
                'isReadBy',
                Auth::user()->id
            )->whereJsonDoesntContain('isDeletedBy', Auth::user()->id)->whereIn('for', ['superadmin', 'superadmin|admin', 'all'])->count();


        } else if ($roles->contains('admin')) {
            $managedCategories = ManagedCategory::where('user_id', Auth::user()->id)->get();
            $categoryIds = $managedCategories->pluck('category_id');
            $categories = Category::whereIn('id', $categoryIds)->get();
            $currentCategory = $categories->first();

            $categories = Category::all();
            $currentCategory = $categories->first();

            $notifications = Notification::whereIn('for', ['admin', 'superadmin|admin', 'admin|staff', 'all'])->whereJsonDoesntContain(
                'isDeletedBy',
                Auth::user()->id
            )->orderBy('created_at', 'DESC')->get();

            $unreadNotifications = Notification::whereJsonDoesntContain(
                'isReadBy',
                Auth::user()->id
            )->whereJsonDoesntContain('isDeletedBy', Auth::user()->id)->whereIn('for', ['admin', 'superadmin|admin', 'admin|staff', 'all'])->count();


        } else if ($roles->contains('staff')) {



            $managedCategories = ManagedCategory::where('user_id', Auth::user()->id)->get();
            $categoryIds = $managedCategories->pluck('category_id')->toArray();

            $allowedToStaff = Category::whereIn('id', $categoryIds)->get();

            foreach ($allowedToStaff as $category) {
                if ($category->approval_level == 'admin') {
                    return redirect()->route('unauthorize');
                }
            }


            $categories = Category::whereIn('id', $categoryIds)->get();

            $currentCategory = $categories->first();


            $notifications = Notification::whereIn('category_id', $categoryIds)
                ->whereIn('for', ['staff', 'admin|staff', 'staff|cashier', 'all'])
                ->whereJsonDoesntContain('isDeletedBy', Auth::user()->id)
                ->orderBy('created_at', 'DESC')
                ->get();


            $unreadNotifications = Notification::whereIn('category_id', $categoryIds)
                ->whereJsonDoesntContain('isReadBy', Auth::user()->id)
                ->whereJsonDoesntContain('isDeletedBy', Auth::user()->id)
                ->whereIn('for', ['staff', 'admin|staff', 'staff|cashier', 'all'])
                ->count();
        }

        if ($currentCategory) {

            $currentCategoryId = $currentCategory->id;
            $categoriesIsNull = false;

            $reservations = PropertyReservation::where('category_id', $currentCategoryId)
                ->where('approvedByAdmin_at', null)
                ->where('approvedByCashier_at', null)
                ->where('canceledByRentee_at', null)
                ->where('declinedByAdmin_at', null)
                ->orderBy('created_at', 'DESC')
                ->get();
        } else {
            $reservations = null;
            $categoriesIsNull = true;

        }


        // dd($categories->toArray());



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

        if ($reservation) {

            $reservation->update([
                'approvedByAdmin_at' => now(),
                'admin_id' => Auth::user()->id,
            ]);

            Reservation::where('id', $reservation->reservation->id)
                ->update([
                    'status' => 'in progress'
                ]);


            if ($reservation->reservation->reservation_type == 'borrow') {
                $reservation->update([
                    'approvedByCashier_at' => now(),
                ]);
                Reservation::where('id', $reservation->reservation->id)
                    ->update([
                        'status' => 'approved'
                    ]);
                Notification::create([
                    'icon' => Auth::user()->img,
                    'user_id' => Auth::user()->id,
                    'title' => 'Approved Reservation',
                    'description' => ' approved a new reservation, check it now!',
                    'redirect_link' => 'reservations',
                    'for' => 'admin|staff',
                ]);
            } else {
                Notification::create([
                    'icon' => Auth::user()->img,
                    'user_id' => Auth::user()->id,
                    'title' => 'Approved Reservation',
                    'description' => ' approved a new reservation, check it now!',
                    'redirect_link' => 'reservations',
                    'for' => 'cashier',
                ]);
            }



            return redirect()->back()->with('success', 'Transaction has been successfuly approved!');
        }

    }

    public function filter(Request $request)
    {
        $checkCategory = Category::find($request->category);

        $current_user_id = Auth::user()->id;


        $categories = Category::all();

        $page_title = 'Reservations';

        $setting = Setting::where('user_id', $current_user_id)->first();

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
            $currentCategory = Category::where('id', $request->category)->first();

            $notifications = Notification::whereIn('for', ['superadmin', 'superadmin|admin', 'all'])->whereJsonDoesntContain(
                'isDeletedBy',
                Auth::user()->id
            )->orderBy('created_at', 'DESC')->get();

            $unreadNotifications = Notification::whereJsonDoesntContain(
                'isReadBy',
                Auth::user()->id
            )->whereJsonDoesntContain('isDeletedBy', Auth::user()->id)->whereIn('for', ['superadmin', 'superadmin|admin', 'all'])->count();


        } else if ($roles->contains('admin')) {
            $currentCategory = Category::where('id', $request->category)
                ->first();

            $categories = Category::all();

            $notifications = Notification::whereIn('for', ['admin', 'superadmin|admin', 'admin|staff', 'all'])->whereJsonDoesntContain(
                'isDeletedBy',
                Auth::user()->id
            )->orderBy('created_at', 'DESC')->get();

            $unreadNotifications = Notification::whereJsonDoesntContain(
                'isReadBy',
                Auth::user()->id
            )->whereJsonDoesntContain('isDeletedBy', Auth::user()->id)->whereIn('for', ['admin', 'superadmin|admin', 'admin|staff', 'all'])->count();


        } else if ($roles->contains('staff')) {

            if ($checkCategory->approval_level != 'staff' || $checkCategory->approval_level != 'both') {
                return redirect()->route('unauthorize');
            }

            $managedCategories = ManagedCategory::where('user_id', Auth::user()->id)->get();
            $categoryIds = $managedCategories->pluck('category_id');

            if (!in_array($request->category, $categoryIds->toArray())) {
                return redirect()->route('unauthorize');
            }

            $currentCategory = Category::whereIn('id', $categoryIds)
                ->where('id', $request->category)
                ->first();

            $categories = Category::whereIn('id', $categoryIds)->get();


            $notifications = Notification::whereIn('category_id', $categoryIds)
                ->whereIn('for', ['staff', 'admin|staff', 'staff|cashier', 'all'])
                ->whereJsonDoesntContain('isDeletedBy', Auth::user()->id)
                ->orderBy('created_at', 'DESC')
                ->get();


            $unreadNotifications = Notification::whereIn('category_id', $categoryIds)
                ->whereJsonDoesntContain('isReadBy', Auth::user()->id)
                ->whereJsonDoesntContain('isDeletedBy', Auth::user()->id)
                ->whereIn('for', ['staff', 'admin|staff', 'staff|cashier', 'all'])
                ->count();
        }

        if ($currentCategory) {

            $currentCategoryId = $currentCategory->id;
            $categoriesIsNull = false;
            $currentStatus = $request->status;

            if ($request->status == 'pending') {
                $reservations = PropertyReservation::where('category_id', $currentCategoryId)
                    ->where('approvedByAdmin_at', null)
                    ->where('canceledByRentee_at', null)
                    ->where('declinedByAdmin_at', null)
                    ->orderBy('created_at', 'DESC')
                    ->get();

            } else if ($request->status == 'approved') {
                $reservations = PropertyReservation::where('category_id', $currentCategoryId)
                    ->whereNot('approvedByAdmin_at', null)
                    ->whereNot('approvedByCashier_at', null)
                    ->orderBy('created_at', 'DESC')
                    ->get();


            } else if ($request->status == 'declined') {
                $reservations = PropertyReservation::where('category_id', $currentCategoryId)
                    ->whereNot('declinedByAdmin_at', null)
                    ->orderBy('created_at', 'DESC')
                    ->get();
            } else if ($request->status == 'canceled') {
                $reservations = PropertyReservation::where('category_id', $currentCategoryId)
                    ->whereNot('canceledByRentee_at', null)
                    ->orderBy('created_at', 'DESC')
                    ->get();
            }
        } else {
            $reservations = null;
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
    public function create(Request $request)
    {
        $ids = str_split($request->propertiesId);

        $allInputs = $request->all();

        $properties = array_filter($allInputs, function ($key) {
            return strpos($key, 'property-qty-') === 0;
        }, ARRAY_FILTER_USE_KEY);

        $quantities = array_values($properties);

        $newarray = [];
        foreach ($ids as $key => $id) {
            if (isset($quantities[$key])) {
                $newarray[] = [
                    'property_id' => $id,
                    'qty' => $quantities[$key],
                ];
            }
        }

        $propertyIds = array_column($newarray, 'property_id');
        if (count($propertyIds) !== count(array_unique($propertyIds))) {
            return redirect()->back()->with('error', 'Duplicate properties are not allowed!');
        }


        $validatedData = $request->validate([
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


            foreach ($newarray as $entry) {

                PropertyReservation::create([
                    'reservation_id' => $reservationId,
                    'property_id' => $entry['property_id'],
                    'qty' => $entry['qty'], // Use quantity here
                    'category_id' => $validatedData['category_id'],
                    'destination_id' => $validatedData['destination_id'],
                    'date_start' => $validatedData['date_start'],
                    'time_start' => $validatedData['time_start'],
                    'date_end' => $validatedData['date_end'],
                    'time_end' => $validatedData['time_end'],
                    'assigned_personel' => $validatedData['assigned_personel'],
                ]);
            }


            $isReadBy = [];

            Notification::create([
                'icon' => Auth::user()->img,
                'user_id' => Auth::user()->id,
                'reservation_id' => $reservation->id,
                'title' => 'New Reservation',
                'category_id' => $validatedData['category_id'],
                'description' => ' added a new reservation.',
                'redirect_link' => 'reservations',
                'for' => 'superadmin|admin',
                'isReadBy' => $isReadBy,
            ]);


            $tracking_code = '' . $trackingCode;

            return redirect()->back()->with('tracking_code', $tracking_code);

        } catch (\Exception $e) {

            dd('Reservation creation error: ' . $e->getMessage(), [
                'user_id' => Auth::user()->id,
                'data' => $request->all(),
            ]);

            return redirect()->back()->with('error', 'Error creating reservation. Please try again later.');
        }
    }

    public function assign(Request $request)
    {


        $reservation = PropertyReservation::find($request->reservation_id);
        if ($reservation) {
            $reservation->update([
                'assigned_personel' => $request->personel
            ]);

            return redirect()->back()->with('success', 'Personel assigned to reserved property!');
        }

        return redirect()->back()->with('error', 'Process unsucessful!');

    }

}
