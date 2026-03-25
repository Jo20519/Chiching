@extends('layouts.app')

@section('content')
<div class="container" style="max-width:600px; margin-top:40px;">
    <h2 style="text-align:center; color:#dc3545;">Withdraw Request</h2>
    <p style="text-align:center; color:#555;">
        Submit a withdrawal request. The group admin must approve it.
    </p>

    <form id="withdrawForm" style="margin-top:30px;">
        <label>Amount (KSh)</label>
        <input type="number" id="amount" required
               style="width:100%; padding:10px; margin-bottom:15px;">

        <label>Reason</label>
        <textarea id="reason" rows="4"
                  style="width:100%; padding:10px; margin-bottom:15px;"></textarea>

        <button type="submit"
                style="width:100%; background:#dc3545; color:white; padding:10px; border:none; border-radius:5px;">
            Submit Request
        </button>

        <p id="message" style="margin-top:15px; text-align:center;"></p>
    </form>

    <div style="text-align:center; margin-top:20px;">
        <a href="/groups" style="color:#0d6efd;">← Back to Group</a>
    </div>
</div>
@endsection
<script>
document.addEventListener("DOMContentLoaded", async () => {
    const token = localStorage.getItem("auth_token");
    const groupId = window.location.pathname.split("/")[2];
    const message = document.getElementById("message");

    if (!token) {
        window.location.href = "/login";
        return;
    }

    document.getElementById("withdrawForm").addEventListener("submit", async (e) => {
        e.preventDefault();

        const amount = document.getElementById("amount").value;
        const reason = document.getElementById("reason").value;

        message.textContent = "Submitting request...";
        message.style.color = "#555";

        try {
            const res = await fetch(`/api/groups/${groupId}/withdrawals`, {
                method: "POST",
                headers: {
                    "Authorization": "Bearer " + token,
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                },
                body: JSON.stringify({ amount, reason })
            });

            const data = await res.json();

            if (res.ok) {
                message.style.color = "green";
                message.textContent = "✅ Withdrawal request sent for approval.";
                document.getElementById("withdrawForm").reset();
            } else {
                message.style.color = "red";
                message.textContent = data.message || "Request failed.";
            }
        } catch {
            message.style.color = "red";
            message.textContent = "Network error.";
        }
    });
});
</script>
