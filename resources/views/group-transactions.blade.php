@extends('layouts.app')

@section('content')
<div class="container mt-5" style="max-width:900px;">
    <h2 style="text-align:center; color:#0d6efd;">Your Groups</h2>
    <p style="text-align:center; color:#666;">Select a group to view its transactions</p>

    <div class="row mt-4" id="groupsList">
        <p style="text-align:center;">Loading your groups...</p>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", async () => {
    const token = localStorage.getItem("auth_token");
    const user = JSON.parse(localStorage.getItem("user"));

    if (!token) {
        window.location.href = "/login";
        return;
    }

    const listContainer = document.getElementById("groupsList");

    try {
        const res = await fetch("http://127.0.0.1:8000/api/groups", {
            headers: {
                "Authorization": "Bearer " + token,
                "Accept": "application/json"
            }
        });

        const data = await res.json();

        if (!res.ok || !data.length) {
            listContainer.innerHTML = "<p style='text-align:center;'>No groups found.</p>";
            return;
        }

        listContainer.innerHTML = "";
        data.forEach(group => {
            const col = document.createElement("div");
            col.classList.add("col-md-4", "mb-3");
            col.innerHTML = `
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>${group.name}</h5>
                        <p>${group.description || 'No description provided.'}</p>
                        <button class="btn btn-primary" onclick="viewGroup(${group.id})">View Transactions</button>
                    </div>
                </div>
            `;
            listContainer.appendChild(col);
        });
    } catch (err) {
        listContainer.innerHTML = "<p style='text-align:center; color:red;'>Error loading groups.</p>";
    }
});

function viewGroup(groupId) {
    window.location.href = `/groups/${groupId}`;
}
</script>
@endsection
