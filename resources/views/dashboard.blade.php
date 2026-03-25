@extends('layouts.app')

@section('content')
<div class="container" style="max-width:800px; margin-top:40px;">

    <!-- Dropdown Menu -->
    <div style="text-align:right; margin-bottom:20px;">
        <div style="display:inline-block; position:relative;">
            <button id="menuBtn"
                    style="background-color:#0d6efd; color:white; border:none; padding:8px 12px; border-radius:5px; cursor:pointer;">
                Menu ▼
            </button>
            <ul id="menuDropdown" style="display:none; position:absolute; right:0; top:35px; list-style:none; margin:0; padding:0; border:1px solid #ccc; border-radius:5px; background:white; width:150px; box-shadow:0 2px 5px rgba(0,0,0,0.2);">
                <li><a href="/dashboard" style="display:block; padding:8px 12px; color:#0d6efd; text-decoration:none;">Home</a></li>
                <li><a href="/groups" style="display:block; padding:8px 12px; color:#0d6efd; text-decoration:none;">Groups</a></li>
                <li>
                    <form method="POST" action="/logout">
                        @csrf
                        <button type="submit" style="width:100%; text-align:left; padding:8px 12px; color:#dc3545; background:none; border:none; cursor:pointer;">
                            Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>

    <h2 style="text-align:center; color:#0d6efd;">Welcome to Your Dashboard</h2>
    <p style="text-align:center; font-weight:bold;">Hello, {{ auth()->user()->name }}!</p>

    <div style="margin-top:30px;">
        <h3>Your Savings Groups</h3>

        @if($groups->isEmpty())
            <div style="text-align:center; margin-top:20px;">
                <p>You are not in any group yet.</p>
                <a href="/create-group"
                   style="background-color:#198754; color:white; text-decoration:none; padding:10px 15px; border-radius:5px; margin-right:10px;">
                    + Create Group
                </a>
                <a href="/join-group"
                   style="background-color:#0d6efd; color:white; text-decoration:none; padding:10px 15px; border-radius:5px;">
                    Join Group
                </a>
            </div>
        @else
            <ul style="list-style:none; padding:0;">
                @foreach($groups as $group)
                    <li style="padding:10px; border-bottom:1px solid #eee; display:flex; justify-content:space-between; align-items:center;">
                        <span>
                            <strong>{{ $group->name }}</strong> — {{ $group->description ?? 'No description' }}
                        </span>
                        <a href="/groups/{{ $group->id }}/transactions-page"
                           style="background-color:#198754; color:white; text-decoration:none; padding:5px 10px; border-radius:5px;">
                            View Transactions
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <div style="text-align:center; margin-top:30px;">
        <form method="POST" action="/logout">
            @csrf
            <button type="submit"
                    style="background-color:#dc3545; color:white; border:none; padding:10px 20px; border-radius:5px; cursor:pointer;">
                Logout
            </button>
        </form>
    </div>
</div>

<script>
// Dropdown toggle only — no fetch, no token logic needed
const menuBtn = document.getElementById("menuBtn");
const menuDropdown = document.getElementById("menuDropdown");

menuBtn.addEventListener("click", () => {
    menuDropdown.style.display = menuDropdown.style.display === "block" ? "none" : "block";
});

document.addEventListener("click", e => {
    if (!menuBtn.contains(e.target) && !menuDropdown.contains(e.target)) {
        menuDropdown.style.display = "none";
    }
});
</script>
@endsection