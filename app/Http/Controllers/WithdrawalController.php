<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Withdrawal;
use App\Services\MpesaService;


class WithdrawalController extends Controller
{
    public function store(Request $request, Group $group)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1'
        ]);
    
        $withdrawal = Withdrawal::create([
            'user_id' => auth()->id(),
            'group_id' => $group->id,
            'amount' => $request->amount,
            'status' => 'pending'
        ]);
    
        return response()->json([
            'success' => true,
            'message' => 'Withdrawal request sent for approval'
        ]);
    }

    
    public function approve(Withdrawal $withdrawal, MpesaService $mpesa)
{
    // ensure admin
    abort_unless(
        auth()->user()->isGroupAdmin($withdrawal->group_id),
        403
    );

    $withdrawal->update(['status' => 'approved']);

    // Send M-Pesa B2C
    $mpesa->b2c(
        $withdrawal->user->phone,
        $withdrawal->amount
    );

    return back()->with('success', 'Withdrawal approved & payout sent');
}

}
