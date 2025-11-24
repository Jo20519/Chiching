<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    // GET /api/groups/{id}/transactions
    public function index($id, Request $request)
{
    $user = $request->user();

    // membership check
    $isMember = $user->groups()->where('groups.id', $id)->exists();
    if (!$isMember) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized: not a member of this group.'
        ], 403);
    }

    $transactions = \App\Models\Transaction::with('user')
        ->where('group_id', $id)
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    return response()->json([
        'success' => true,
        'data' => $transactions->items(),
        'current_page' => $transactions->currentPage(),
        'last_page' => $transactions->lastPage(),
    ]);
}

    // Optional: user-specific transactions (if you want)
    public function userTransactions(Request $request)
    {
        $userId = $request->user()->id;

        $transactions = Transaction::with(['user', 'group'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $transactions->items(),
            'current_page' => $transactions->currentPage(),
            'last_page' => $transactions->lastPage(),
        ]);
    }
    public function showGroups(Request $request)
{
    $user = $request->user();

    // Get all groups the user belongs to
    $groups = $user->groups()->get();

    return view('transactions.groups', compact('groups'));
}

public function showGroupTransactions($id, Request $request)
{
    $user = $request->user();

    // Verify membership
    $isMember = $user->groups()->where('groups.id', $id)->exists();
    if (!$isMember) {
        abort(403, 'Unauthorized access');
    }

    // Just show the Blade page — transactions will be loaded by AJAX
    return view('transactions.index', compact('id'));
}

}
