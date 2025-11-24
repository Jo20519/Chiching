<?php

namespace App\Http\Controllers;

use App\Models\Contribution;
use App\Models\Transaction;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ContributionController extends Controller
{
    /**
     * Store a new contribution and related transaction
     */
    public function store(Request $request, $groupId)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $user = Auth::user();
        $group = Group::find($groupId);

        if (!$group) {
            return response()->json(['message' => 'Group not found.'], 404);
        }

        // Ensure the user belongs to this group
        if (!$group->members->contains($user->id)) {
            return response()->json(['message' => 'You are not a member of this group.'], 403);
        }

        // 🟢 Create the contribution record
        $contribution = Contribution::create([
            'group_id' => $groupId,
            'user_id' => $user->id,
            'amount' => $request->amount,
            'status' => 'completed',
        ]);

        // 🟢 Record the transaction for accountability
        $transaction = Transaction::create([
            'group_id' => $groupId,
            'user_id' => $user->id,
            'type' => 'contribution',
            'amount' => $request->amount,
            'reference' => Str::uuid(),
        ]);

        // 🟢 Recalculate total savings
        $newTotal = Contribution::where('group_id', $groupId)->sum('amount');

        return response()->json([
            'message' => 'Contribution and transaction recorded successfully.',
            'contribution' => $contribution,
            'transaction' => $transaction,
            'new_total' => $newTotal,
        ], 201);
    }
}


