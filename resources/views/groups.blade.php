@extends('layouts.app')

@section('content')
<div class="container" style="max-width:900px; margin-top:40px;">
    <h2 style="text-align:center; color:#0d6efd; margin-bottom:20px;">My Savings Groups</h2>

    <!-- Actions -->
    <div style="text-align:center; margin-bottom:25px;">
        <button id="createGroupBtn"
            style="background-color:#198754; color:white; border:none; padding:10px 15px; border-radius:6px; margin-right:10px;">
            + Create Group
        </button>
        <button id="joinGroupBtn"
            style="background-color:#0d6efd; color:white; border:none; padding:10px 15px; border-radius:6px;">
            Join Group
        </button>
    </div>

    <!-- Search -->
    <div style="text-align:center; margin-bottom:25px;">
        <input type="text" id="searchGroup" placeholder="Search groups..."
               style="width:80%; padding:8px; border:1px solid #ccc; border-radius:5px;">
    </div>

    <!-- Group List -->
    <div id="groupsContainer">
        <p id="loading" style="text-align:center;">Loading your groups...</p>
        <ul id="groupsList" style="list-style:none; padding:0;"></ul>
        <p id="noGroups" style="display:none; text-align:center; margin-top:20px;">
            You are not in any group yet.
        </p>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", async () => {
    const token = localStorage.getItem("auth_token");
    const groupsList = document.getElementById("groupsList");
    const noGroups = document.getElementById("noGroups");
    const loading = document.getElementById("loading");
    const searchInput = document.getElementById("searchGroup");

    if (!token) {
        window.location.href = "/login";
        return;
    }

    // Fetch user's groups from API
    try {
        const response = await fetch("/api/user/groups", {
            method: "GET",
            headers: {
                "Authorization": "Bearer " + token,
                "Accept": "application/json"
            }
        });

        const data = await response.json();
        loading.style.display = "none";

        if (response.ok && data.groups && data.groups.length > 0) {
            renderGroups(data.groups);
        } else {
            noGroups.style.display = "block";
        }

    } catch (error) {
        console.error("Error fetching groups:", error);
        loading.textContent = "Unable to load groups. Try again later.";
    }

    // Render groups list
    function renderGroups(groups) {
        groupsList.innerHTML = "";
        groups.forEach(group => {
            const li = document.createElement("li");
            li.classList.add("group-card");
            li.style.border = "1px solid #ddd";
            li.style.borderRadius = "10px";
            li.style.padding = "15px";
            li.style.marginBottom = "15px";
            li.style.boxShadow = "0 1px 3px rgba(0,0,0,0.1)";
            li.innerHTML = `
                <h4 style="color:#0d6efd;">${group.name}</h4>
                <p>${group.description || "No description provided."}</p>
                <p><strong>Members:</strong> ${group.members_count || '—'}</p>
                <button class="view-group-btn" data-id="${group.id}"
                    style="background-color:#0d6efd; color:white; border:none; padding:6px 12px; border-radius:5px; cursor:pointer;">
                    View Group
                </button>

                <button class="view-group-transactions-btn" data-id="${group.id}"
                    style="background-color:#198754; color:white; border:none; padding:6px 12px; border-radius:5px; cursor:pointer;">
                    View Transactions
                </button>
            `;
            groupsList.appendChild(li);
        });

        // Attach click handlers for View Group buttons
        document.querySelectorAll(".view-group-btn").forEach(btn => {
            btn.addEventListener("click", (e) => {
                const groupId = e.target.getAttribute("data-id");
                viewGroup(groupId);
            });
        });

        
        // Attach click handlers for View Group  Transactions buttons
        document.querySelectorAll(".view-group-transactions-btn").forEach(btn => {
            btn.addEventListener("click", (e) => {
                const groupId = e.target.getAttribute("data-id");
                viewGroupTransactions(groupId);
            });
        });
    }

    // Search/filter functionality
    searchInput.addEventListener("keyup", (e) => {
        const query = e.target.value.toLowerCase();
        document.querySelectorAll(".group-card").forEach(card => {
            card.style.display = card.textContent.toLowerCase().includes(query) ? "block" : "none";
        });
    });

    // Navigation actions
    document.getElementById("createGroupBtn").addEventListener("click", () => {
        window.location.href = "/create-group";
    });

    document.getElementById("joinGroupBtn").addEventListener("click", () => {
        window.location.href = "/join-group";
    });
});

// View group page using Blade route (public)
function viewGroup(groupId) {
    window.location.href = `/groups/${groupId}`;
}
//View group transaction page using Blade route (public)
function viewGroupTransactions(groupId) {
    window.location.href = `/groups/${groupId}/transactions`;
}

</script>
