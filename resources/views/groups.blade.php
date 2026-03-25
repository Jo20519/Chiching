@extends('layouts.app')

@section('content')
<div class="container" style="max-width:900px; margin-top:40px;">
    <h2 style="text-align:center; color:#0d6efd; margin-bottom:20px;">My Savings Groups</h2>

    <!-- Actions -->
    <div style="text-align:center; margin-bottom:25px;">
        <button onclick="window.location.href='/create-group'"
            style="background-color:#198754; color:white; border:none; padding:10px 15px; border-radius:6px; margin-right:10px;">
            + Create Group
        </button>
        <button onclick="window.location.href='/join-group'"
            style="background-color:#0d6efd; color:white; border:none; padding:10px 15px; border-radius:6px;">
            Join Group
        </button>
    </div>

    <!-- Search -->
    <div style="text-align:center; margin-bottom:25px;">
        <input type="text" id="searchGroup" placeholder="Search groups..."
               style="width:80%; padding:8px; border:1px solid #ccc; border-radius:5px;"
               onkeyup="filterGroups(this.value)">
    </div>

    <!-- Group List -->
    @if($groups->isEmpty())
        <p style="text-align:center; margin-top:20px;">You are not in any group yet.</p>
    @else
        <ul id="groupsList" style="list-style:none; padding:0;">
            @foreach($groups as $group)
                <li class="group-card"
                    style="border:1px solid #ddd; border-radius:10px; padding:15px; margin-bottom:15px; box-shadow:0 1px 3px rgba(0,0,0,0.1);">
                    
                    <h4 style="color:#0d6efd;">{{ $group->name }}</h4>
                    <p>{{ $group->description ?? 'No description provided.' }}</p>
                    <p><strong>Members:</strong> {{ $group->members_count ?? '—' }}</p>

                    <button onclick="window.location.href='/groups/{{ $group->id }}'"
                        style="background-color:#0d6efd; color:white; border:none; padding:6px 12px; border-radius:5px; cursor:pointer;">
                        View Group
                    </button>

                    <button onclick="window.location.href='/groups/{{ $group->id }}/transactions'"
                        style="background-color:#198754; color:white; border:none; padding:6px 12px; border-radius:5px; cursor:pointer; margin-left:6px;">
                        View Transactions
                    </button>
                </li>
            @endforeach
        </ul>
    @endif
</div>

<script>
function filterGroups(query) {
    query = query.toLowerCase();
    document.querySelectorAll(".group-card").forEach(card => {
        card.style.display = card.textContent.toLowerCase().includes(query) ? "block" : "none";
    });
}
</script>
@endsection