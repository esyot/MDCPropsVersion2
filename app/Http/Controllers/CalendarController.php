<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Destination;
use App\Models\Item;
use App\Models\ItemsTransaction;
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


        $daysWithRecords = ItemsTransaction::where('category_id', $category)
            ->whereYear('date_start', $currentDate->format('Y'))
            ->get()
            ->map(fn($transaction) => Carbon::parse($transaction->date_start)->format('Y-m-d'))
            ->unique()
            ->values()
            ->toArray();
        $currentCategory = Category::find($category);

        $setting = Setting::where('user_id', Auth::user()->id)->first();
        $transactions = ItemsTransaction::all();

        $items = Item::where('category_id', $category)->get();

        $destinations = Destination::all();

        return view('admin.partials.calendar-month', compact(
            'selectedMonth',
            'destinations',
            'transactions',
            'items',
            'currentDate',
            'daysWithRecords',
            'currentCategory',
            'setting'
        ));
    }
}
