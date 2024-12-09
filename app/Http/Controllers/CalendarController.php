<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Destination;
use App\Models\Property;
use App\Models\PropertyReservation;
use App\Models\Setting;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function selectMonth($year, $month, $category)
    {
        $currentDate = Carbon::create($year, $month, 1);

        $selectedMonth = $currentDate->format('F');

        $daysWithRecords = PropertyReservation::where('category_id', $category)
            ->whereYear('date_start', $currentDate->format('Y'))
            ->get()
            ->flatMap(function ($reservation) {
                // Create Carbon instances for the start and end dates
                $startDate = Carbon::parse($reservation->date_start);
                $endDate = Carbon::parse($reservation->date_end);

                // Collect all dates between startDate and endDate (inclusive)
                $dates = [];
                while ($startDate->lte($endDate)) {
                    $dates[] = $startDate->format('Y-m-d');
                    $startDate->addDay(); // Move to the next day
                }

                return $dates;
            })
            ->unique()
            ->values()
            ->toArray();

        $currentCategory = Category::find($category);

        $reservations = PropertyReservation::where('category_id', $currentCategory->id)->get();

        $setting = Setting::where('user_id', Auth::user()->id)->first();

        $properties = Property::where('category_id', $category)->get();

        $destinations = Destination::all();

        return view('admin.partials.calendar-month', compact(
            'selectedMonth',
            'destinations',
            'reservations',
            'properties',
            'currentDate',
            'daysWithRecords',
            'currentCategory',
            'setting'
        ));
    }

    public function calendarDayView($date, $category_id)
    {

        $currentDate = Carbon::parse($date)->format('F j, Y');

        $reservations = PropertyReservation::where('category_id', $category_id)
            ->where(function ($query) use ($date) {

                $query->whereDate('date_start', '<=', $date)
                    ->whereDate('date_end', '>=', $date);
            })
            ->get();

        $setting = Setting::where('user_id', Auth::user()->id)->first();

        return view('admin.partials.calendar-day-view', compact(

            'currentDate',
            'reservations',
            'date',
            'setting'
        ));
    }
}
