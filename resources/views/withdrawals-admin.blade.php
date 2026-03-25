@extends('layouts.app')

@section('content')
<div class="container" style="max-width:900px; margin-top:40px;">
    <h2 style="text-align:center;">Withdrawal Requests</h2>

    <table style="width:100%; border-collapse:collapse; margin-top:20px;">
        <thead>
            <tr>
                <th>User</th>
                <th>Amount</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="withdrawalsTable"></tbody>
    </table>
</div>

<script>
document.addEventListener("DOMContentLoaded", async () => {
    const token = localStorage.getItem("auth_token");
    const groupId = {{ $groupId }};
    const table = document.getElementById("withdrawalsTable");

    const res = await fetch(`/api/groups/${groupId}/withdrawals`, {
        headers: {
            "Authorization": "Bearer " + token,
            "Accept": "application/json"
        }
    });

    const data = await res.json();

    if (!res.ok) {
        table.innerHTML = `<tr><td colspan="5">${data.message}</td></tr>`;
        return;
    }

    if (data.withdrawals.length === 0) {
        table.innerHTML = `<tr><td colspan="5">No withdrawal requests</td></tr>`;
        return;
    }

  
    data.withdrawals.forEach(w => {
        table.innerHTML += `
            <tr>
                <td>${w.user.name}</td>
                <td>KSh ${w.amount}</td>
                <td>${w.reason ?? '-'}</td>
                <td>${w.status}</td>
                <td>
                    ${w.status === 'pending' ? `
                        <button onclick="approve(${w.id})">Approve</button>
                        <button onclick="reject(${w.id})">Reject</button>
                    ` : '-'}
                </td>
            </tr>
        `;
    });
});

async function approve(id) {
    await fetch(`/api/withdrawals/${id}/approve`, {
        method: "POST",
        headers: { "Authorization": "Bearer " + localStorage.getItem("auth_token") }
    });
    location.reload();
}

async function reject(id) {
    await fetch(`/api/withdrawals/${id}/reject`, {
        method: "POST",
        headers: { "Authorization": "Bearer " + localStorage.getItem("auth_token") }
    });
    location.reload();
}
</script>
@endsection
