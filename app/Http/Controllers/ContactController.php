<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function search(Request $request)
    {

        $users = User::whereNot('id', Auth::user()->id)->where('name', 'LIKE', '%' . $request->search . '%')->get();
        $value = $request->search;
        return view('admin.partials.contacts-list', compact('users', 'value'));
    }
}
