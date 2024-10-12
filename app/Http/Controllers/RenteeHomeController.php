<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;

class RenteeHomeController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        return view('rentee.pages.index', compact('categories'));
    }
    public function welcome()
    {
        return view('rentee.pages.welcome');
    }
}
