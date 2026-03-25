<?php

namespace App\Http\Controllers;

use App\Models\WithdrawalRequest;
use Illuminate\Http\Request;
use App\Models\Group;

class WithdrawalRequestController extends Controller
{
    public function index($groupId, Request $request)
{
    $user = $request->user();
    $group = Group::findOrFail($groupId);

    $isAdmin = $group->members()
        ->where('user_id', $user->id)
        ->wherePivot('role', 'admin')
        ->exists();

    if (!$isAdmin) {
        return response()->json(['message' => 'Forbidden'], 403);
    }

  
}

    public function store(Request $request, $groupId)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'reason' => 'nullable|string',
        ]);

        $withdrawal = WithdrawalRequest::create([
            'group_id' => $groupId,
            'user_id' => $request->user()->id,
            'amount' => $request->amount,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return response()->json($withdrawal, 201);
    }

    public function approve(Request $request, $id)
    {
        $withdrawal = WithdrawalRequest::findOrFail($id);
        $withdrawal->update([
            'status' => 'approved',
            'approved_by' => $request->user()->id,
            'approved_at' => now(),
        ]);

        return response()->json(['message' => 'Withdrawal approved']);
    }

    public function reject($id)
    {
        $withdrawal = WithdrawalRequest::findOrFail($id);
        $withdrawal->status = 'rejected';
        $withdrawal->save();

        return response()->json(['success' => true]);
    }


}

