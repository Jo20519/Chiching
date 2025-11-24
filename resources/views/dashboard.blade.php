@extends('layouts.app')

@section('content')
<div class="container" style="max-width:800px; margin-top:40px;">
    <h2 style="text-align:center; color:#0d6efd;">Welcome to Your Dashboard</h2>
    <p id="userName" style="text-align:center; font-weight:bold;"></p>

    <div id="groupsSection" style="margin-top:30px;">
        <h3>Your Savings Groups</h3>
        <ul id="groupsList" style="list-style:none; padding:0;"></ul>

        <div id="noGroups" style="text-align:center; margin-top:20px; display:none;">
            <p>You are not in any group yet.</p>
            <button id="createGroupBtn"
                    style="background-color:#198754; color:white; border:none; padding:10px 15px; border-radius:5px; cursor:pointer; margin-right:10px;">
                + Create Group
            </button>
            <button id="joinGroupBtn"
                    style="background-color:#0d6efd; color:white; border:none; padding:10px 15px; border-radius:5px; cursor:pointer;">
                Join Group
            </button>
        </div>
    </div>

    <div style="text-align:center; margin-top:30px;">
        <button id="logoutBtn"
                style="background-color:#dc3545; color:white; border:none; padding:10px 20px; border-radius:5px; cursor:pointer;">
            Logout
        </button>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", async () => {
    const token = localStorage.getItem("auth_token");
    const userName = document.getElementById("userName");
    const groupsList = document.getElementById("groupsList");
    const noGroups = document.getElementById("noGroups");

    if (!token) {
        window.location.href = "/login";
        return;
    }

    try {
        const response = await fetch("http://127.0.0.1:8000/api/groups", {
            method: "GET",
            headers: {
                "Authorization": "Bearer " + token,
                "Accept": "application/json"
            }
        });

        const data = await response.json();

        if (response.ok) {
            userName.textContent = `Hello, ${data.user?.name || "Member"}!`;

            if (data.groups && data.groups.length > 0) {
                data.groups.forEach(group => {
                    const li = document.createElement("li");
                    li.style.padding = "10px";
                    li.style.borderBottom = "1px solid #eee";
                    li.innerHTML = `<strong>${group.name}</strong> — ${group.description || "No description"}`;
                    groupsList.appendChild(li);
                });
            } else {
                noGroups.style.display = "block";
                groupsList.innerHTML = "";
            }
        } else {
            userName.textContent = "Session expired. Please log in again.";
            setTimeout(() => window.location.href = "/login", 2000);
        }
    } catch (error) {
        console.error("Error fetching groups:", error);
        groupsList.innerHTML = "<li>Unable to load data. Try again later.</li>";
    }

    // Logout
    document.getElementById("logoutBtn").addEventListener("click", async () => {
        await fetch("http://127.0.0.1:8000/api/logout", {
            method: "POST",
            headers: { "Authorization": "Bearer " + token }
        });
        localStorage.removeItem("auth_token");
        window.location.href = "/login";
    });

    // Create Group
    document.getElementById("createGroupBtn")?.addEventListener("click", () => {
        window.location.href = "/create-group";
    });

    // Join Group
    document.getElementById("joinGroupBtn")?.addEventListener("click", () => {
        window.location.href = "/join-group";
    });
});
</script>
@endsection
