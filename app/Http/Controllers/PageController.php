<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function dashboard()
{
    $user = auth()->user(); // Get the logged-in user
    $groups = []; // You'll fetch from DB later
    $transactions = []; // Fetch recent transactions later

    return view('dashboard', compact('user', 'groups', 'transactions'));
}

public function transactions($id)
{
    return view('transactions');
}

}
