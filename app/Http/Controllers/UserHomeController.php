<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;

class UserHomeController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        return view('user.pages.index', compact('categories'));
    }
}
