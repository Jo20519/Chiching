<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
   public function index(Request $request)
{
    $user = $request->user();

    $groups = $user->groups()
        ->select('groups.id', 'groups.name', 'groups.description', 'groups.contribution_amount')
        ->withCount('members')
        ->get();

    return view('groups', compact('groups'));
}

public function dashboard(Request $request)
{
    $groups = $request->user()->groups()
        ->select('groups.id', 'groups.name', 'groups.description')
        ->get();

    return view('dashboard', compact('groups'));
}
    // Create new group (API)
   public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string',
        'description' => 'nullable|string',
        'contribution_amount' => 'nullable|numeric',
    ]);

    $group = Group::create([
        'name' => $request->name,
        'description' => $request->description,
        'contribution_amount' => $request->contribution_amount,
        'created_by' => $request->user()->id,
    ]);

    $group->members()->attach($request->user()->id, ['role' => 'admin']);

    return redirect('/groups')->with('success', 'Group created successfully!');
}
    // Join a group (API)
    public function joinGroup(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Please log in first.'
            ]);
        }

        $group = Group::where('name', $request->name)->first();

        if (!$group) {
            return response()->json([
                'success' => false,
                'message' => 'Group not found.'
            ]);
        }

        if ($group->members()->where('user_id', $user->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'You are already a member of this group.'
            ]);
        }

        $group->members()->attach($user->id);

        return response()->json([
            'success' => true,
            'message' => 'You have successfully joined the group!'
        ]);
    }

    // List user's groups (API)
    public function userGroups()
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Not authenticated.'], 401);
        }

        $groups = $user->groups()
        ->select('groups.id', 'groups.name', 'groups.description', 'groups.contribution_amount')
        ->withCount('members')
        ->get();
    

        return response()->json([
            'success' => true,
            'groups' => $groups
        ]);
    }

    // ✅ Web route: show group details page (loads Blade view)
   public function show($id, Request $request)
{
    $user = $request->user();

    $group = Group::with(['members', 'contributions.user'])->findOrFail($id);

    // Check admin
    $isAdmin = $group->members()
        ->where('user_id', $user->id)
        ->wherePivot('role', 'admin')
        ->exists();

    // Total savings
    $totalSavings = $group->contributions->sum('amount');

    // Contributors
    $contributors = $group->contributions
        ->groupBy('user_id')
        ->map(function ($contribs) {
            $user = $contribs->first()->user;

            return [
                'user_id' => $user->id,
                'name' => $user->name,
                'total_contributed' => $contribs->sum('amount'),
                'last_contribution' => $contribs->max('created_at'),
            ];
        })->values();

    // Non-contributors
    $nonContributors = $group->members->filter(function ($member) use ($contributors) {
        return !$contributors->pluck('user_id')->contains($member->id);
    })->values();

    return view('group-details', [
        'group' => $group,
        'contributors' => $contributors,
        'nonContributors' => $nonContributors,
        'totalSavings' => $totalSavings,
        'isAdmin' => $isAdmin
    ]);
}
    // ✅ API route: return actual group data as JSON
    public function apiShow($id)
    {
        $group = Group::with(['members', 'contributions.user'])->find($id);
    
        if (!$group) {
            return response()->json(['message' => 'Group not found'], 404);
        }
    
        // Total amount contributed
        $totalSavings = $group->contributions->sum('amount');
    
        // Get contributors with their contribution totals
        $contributors = $group->contributions
            ->groupBy('user_id')
            ->map(function ($contribs) {
                $user = $contribs->first()->user;
                return [
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'total_contributed' => $contribs->sum('amount'),
                    'last_contribution' => $contribs->max('created_at'),
                ];
            })
            ->values();
    
        // Get members who haven’t contributed yet
        $nonContributors = $group->members->filter(function ($member) use ($contributors) {
            return !$contributors->pluck('user_id')->contains($member->id);
         })->values();
    
        return response()->json([
            'id' => $group->id,
            'name' => $group->name,
            'description' => $group->description,
            'total_savings' => $totalSavings,
            'contributors' => $contributors,
            'non_contributors' => $nonContributors,
        ]);
    }

    // transaction api
public function transactions()
{
    return $this->hasMany(Transaction::class);
}

}   