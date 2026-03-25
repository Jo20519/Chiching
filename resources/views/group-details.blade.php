@extends('layouts.app')

@section('content')
<div class="container" style="max-width:900px; margin-top:40px;">

    <h2 style="text-align:center; color:#0d6efd;">
        {{ $group->name }}
    </h2>

    <p style="text-align:center; color:#555;">
        {{ $group->description ?? 'No description provided.' }}
    </p>

    <!-- Group Summary -->
    <div style="display:flex; justify-content:space-around; margin:20px 0;">
        <div style="text-align:center;">
            <h4>💰 Total Savings</h4>
            <p>KSh {{ number_format($totalSavings) }}</p>
        </div>
        <div style="text-align:center;">
            <h4>👥 Members</h4>
            <p>
                <strong>{{ count($contributors) }}</strong> active /
                <strong>{{ count($contributors) + count($nonContributors) }}</strong> total
            </p>
        </div>
    </div>

    <!-- Actions -->
    <div style="text-align:center; margin:30px 0;">
        <h4>Group Actions</h4>

       <form method="POST" action="{{ route('mpesa.contribute', $group->id) }}">
            @csrf
            <input type="text" name="phone" placeholder="2547XXXXXXXX" required
                style="padding:8px; border:1px solid #ccc; border-radius:5px;">
            <button type="submit"
                style="background-color:#198754; color:white; border:none; padding:8px 20px; border-radius:5px;">
                💰 Contribute
            </button>
        </form>

        <a href="{{ route('groups.withdraw', $group->id) }}">
            <button style="background-color:#dc3545; color:white; border:none; padding:8px 15px; border-radius:5px;">
                Request Withdrawal
            </button>
        </a>

        <div style="margin-top:20px;">
            <a href="{{ url('/groups/'.$group->id.'/withdrawals/admin') }}">
                <button style="background:#dc3545; color:white; padding:10px 20px; border:none; border-radius:5px;">
                    Manage Withdrawals
                </button>
            </a>
        </div>
    </div>

    <!-- Search -->
    <input type="text" id="searchInput" placeholder="Search by name..."
        style="padding:8px; width:300px; border:1px solid #ccc; border-radius:5px; margin:20px auto; display:block;">

    <!-- Contributors -->
    <div style="margin-top:40px;">
        <h4>🟢 Contributors</h4>

        <table style="width:100%; border-collapse:collapse; margin-top:10px;">
            <thead>
                <tr style="background:#f9f9f9;">
                    <th style="padding:10px;">Name</th>
                    <th style="padding:10px;">Total Contributed</th>
                    <th style="padding:10px;">Last Contribution</th>
                </tr>
            </thead>
            <tbody id="contributorsList">
                @forelse($contributors as $c)
                    <tr>
                        <td style="padding:10px;">{{ $c->name }}</td>
                        <td style="padding:10px;">KSh {{ number_format($c->total_contributed) }}</td>
                        <td style="padding:10px;">
                            {{ $c->last_contribution ? \Carbon\Carbon::parse($c->last_contribution)->format('d M Y') : '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="padding:10px;">No contributions yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Non Contributors -->
    <div style="margin-top:40px;">
        <h4>🔴 Non-Contributors</h4>

        <table style="width:100%; border-collapse:collapse; margin-top:10px;">
            <thead>
                <tr style="background:#f9f9f9;">
                    <th style="padding:10px;">Name</th>
                </tr>
            </thead>
            <tbody>
                @forelse($nonContributors as $n)
                    <tr>
                        <td style="padding:10px;">{{ $n->name }}</td>
                    </tr>
                @empty
                    <tr>
                        <td style="padding:10px;">All members have contributed 🎉</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Back -->
    <div style="text-align:center; margin-top:40px;">
        <a href="/groups">
            <button style="background:#0d6efd; color:white; padding:10px 20px; border:none; border-radius:5px;">
                ← Back to Groups
            </button>
        </a>
    </div>

</div>

<!-- Simple search JS (optional, not API-based) -->
<script>
document.getElementById("searchInput").addEventListener("input", function () {
    const query = this.value.toLowerCase();

    document.querySelectorAll("#contributorsList tr").forEach(row => {
        const name = row.children[0]?.textContent.toLowerCase() || "";
        row.style.display = name.includes(query) ? "" : "none";
    });
});
</script>

@endsection