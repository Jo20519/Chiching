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
                <li><a href="/login" style="display:block; padding:8px 12px; color:#0d6efd; text-decoration:none;">Login</a></li>
                <li><a href="/register" style="display:block; padding:8px 12px; color:#0d6efd; text-decoration:none;">Register</a></li>
                <li><a href="/groups" style="display:block; padding:8px 12px; color:#0d6efd; text-decoration:none;">Groups</a></li>
            </ul>
        </div>
    </div>

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

    // Dropdown toggle
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

    // Fetch user and groups
    try {
        const response = await fetch("http://127.0.0.1:8000/api/user/groups", {
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
                noGroups.style.display = "none";
                groupsList.innerHTML = "";
                data.groups.forEach(group => {
                    const li = document.createElement("li");
                    li.style.padding = "10px";
                    li.style.borderBottom = "1px solid #eee";
                    li.style.display = "flex";
                    li.style.justifyContent = "space-between";
                    li.style.alignItems = "center";
                    li.innerHTML = `
                        <span><strong>${group.name}</strong> — ${group.description || "No description"}</span>
                        <button onclick="viewGroupTransactions(${group.id})"
                            style="background-color:#198754; color:white; border:none; padding:5px 10px; border-radius:5px; cursor:pointer;">
                            View Transactions
                        </button>
                    `;
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

    // Create / Join Group buttons
    document.getElementById("createGroupBtn")?.addEventListener("click", () => {
        window.location.href = "/create-group";
    });
    document.getElementById("joinGroupBtn")?.addEventListener("click", () => {
        window.location.href = "/join-group";
    });
});

// Navigate to transactions page
function viewGroupTransactions(groupId) {
    window.location.href = `/groups/${groupId}/transactions-page`;
}
</script>
@endsection
