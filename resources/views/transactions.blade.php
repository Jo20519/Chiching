@extends('layouts.app')

@section('content')
<div class="container" style="max-width:900px; margin-top:40px;">
    <h2 style="text-align:center; color:#0d6efd;">Your Groups</h2>
    <p style="text-align:center;">Select a group to view its transactions</p>

    <div id="groupsContainer" style="margin-top:30px; text-align:center;">
        <p id="loading">Loading your groups...</p>
        <div id="groupsList" style="display:none;"></div>
        <p id="noGroups" style="display:none;">No groups found.</p>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", async () => {
    const token = localStorage.getItem("auth_token");
    if (!token) {
        window.location.href = "/login";
        return;
    }

    try {
        const response = await fetch("http://127.0.0.1:8000/api/groups", {
            headers: {
                "Authorization": "Bearer " + token,
                "Accept": "application/json"
            }
        });

        const data = await response.json();
        const groupsList = document.getElementById("groupsList");
        const loading = document.getElementById("loading");
        const noGroups = document.getElementById("noGroups");

        loading.style.display = "none";

        if (data.groups && data.groups.data && data.groups.data.length > 0) {
            groupsList.style.display = "block";
            data.groups.data.forEach(group => {
                const div = document.createElement("div");
                div.style = "border:1px solid #ccc; border-radius:8px; padding:15px; margin:10px auto; max-width:600px; background:#f9f9f9;";
                div.innerHTML = `
                    <h4 style="color:#0d6efd;">${group.name}</h4>
                    <p>${group.description || "No description provided."}</p>
                    <p><strong>Members:</strong> ${group.members_count || 0}</p>
                    <button onclick="window.location.href='/groups/${group.id}/transactions'"
                        style="background-color:#0d6efd; color:white; border:none; padding:8px 12px; border-radius:5px; cursor:pointer;">
                        View Transactions
                    </button>
                `;
                groupsList.appendChild(div);
            });
        } else {
            noGroups.style.display = "block";
        }

    } catch (error) {
        console.error("Error loading groups:", error);
        document.getElementById("loading").textContent = "Failed to load groups.";
    }
});
</script>
@endsection
