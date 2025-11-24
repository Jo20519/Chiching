@extends('layouts.app')

@section('content')
<div class="container" style="max-width:900px; margin-top:40px;">
    <h2 id="groupName" style="text-align:center; color:#0d6efd;">Loading...</h2>
    <p id="groupDescription" style="text-align:center; color:#555;"></p>

    <!-- Group Summary -->
    <div style="display:flex; justify-content:space-around; margin:20px 0;">
        <div style="text-align:center;">
            <h4>💰 Total Savings</h4>
            <p id="totalSavings">KSh 0</p>
        </div>
        <div style="text-align:center;">
            <h4>👥 Members</h4>
            <p><strong id="activeCount">0</strong> active / <strong id="totalCount">0</strong> total</p>
        </div>
    </div>

    <!-- Contribution Section -->
    <div id="contributeSection" style="text-align:center; margin:30px 0;">
        <h4>Make a Contribution</h4>
        <input type="number" id="amount" placeholder="Enter amount"
               style="padding:8px; width:200px; border:1px solid #ccc; border-radius:5px; margin-right:10px;">
        <button id="contributeBtn"
                style="background-color:#198754; color:white; border:none; padding:8px 15px; border-radius:5px; cursor:pointer;">
            Contribute
        </button>
        <p id="contributeMsg" style="margin-top:10px; color:#555;"></p>
    </div>

    <input type="text" id="searchInput" placeholder="Search by name..."
       style="padding:8px; width:300px; border:1px solid #ccc; border-radius:5px; margin:20px auto; display:block;">

    <!-- Contributors Table -->
    <div id="contributorsSection" style="margin-top:40px;">
        <h4>🟢 Contributors</h4>
        <table style="width:100%; border-collapse:collapse; margin-top:10px;">
            <thead>
                <tr style="background:#f9f9f9; text-align:left;">
                    <th style="padding:10px;">Name</th>
                    <th style="padding:10px;">Total Contributed (KSh)</th>
                    <th style="padding:10px;">Last Contribution</th>
                </tr>
            </thead>
            <tbody id="contributorsList"></tbody>
        </table>
    </div>

    <!-- Non-Contributors Table -->
    <div id="nonContributorsSection" style="margin-top:40px;">
        <h4>🔴 Non-Contributors</h4>
        <table style="width:100%; border-collapse:collapse; margin-top:10px;">
            <thead>
                <tr style="background:#f9f9f9; text-align:left;">
                    <th style="padding:10px;">Name</th>
                </tr>
            </thead>
            <tbody id="nonContributorsList"></tbody>
        </table>
    </div>

    <!-- Back Button -->
    <div style="text-align:center; margin-top:40px;">
        <button onclick="window.location.href='/groups'"
                style="background-color:#0d6efd; color:white; border:none; padding:10px 20px; border-radius:5px; cursor:pointer;">
            ← Back to Groups
        </button>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", async () => {
    const token = localStorage.getItem("auth_token");
    const user = JSON.parse(localStorage.getItem("user")); // store current user in localStorage after login
    const pathParts = window.location.pathname.split("/");
    const groupId = pathParts[pathParts.length - 1];

    if (!token) {
        window.location.href = "/login";
        return;
    }

    async function loadGroupData() {
        try {
            const response = await fetch(`http://127.0.0.1:8000/api/groups/${groupId}`, {
                headers: {
                    "Authorization": "Bearer " + token,
                    "Accept": "application/json"
                }
            });

            const data = await response.json();

            if (!response.ok) {
                document.getElementById("groupName").textContent = "Group not found.";
                return;
            }

            // Display group info
            document.getElementById("groupName").textContent = data.name;
            document.getElementById("groupDescription").textContent = data.description || "No description provided.";
            document.getElementById("totalSavings").textContent = `KSh ${data.total_savings || 0}`;
            document.getElementById("activeCount").textContent = data.contributors.length;
            document.getElementById("totalCount").textContent =
                data.contributors.length + data.non_contributors.length;

            // Render contributors
            const contributorsList = document.getElementById("contributorsList");
            contributorsList.innerHTML = "";
            if (data.contributors.length > 0) {
                data.contributors.forEach(c => {
                    const tr = document.createElement("tr");
                    if (user && c.user_id === user.id) tr.style.backgroundColor = "#e7f5ff"; // highlight self
                    tr.innerHTML = `
                        <td style="padding:10px; border-bottom:1px solid #eee;">${c.name}</td>
                        <td style="padding:10px; border-bottom:1px solid #eee;">${c.total_contributed}</td>
                        <td style="padding:10px; border-bottom:1px solid #eee;">${new Date(c.last_contribution).toLocaleDateString()}</td>
                    `;
                    contributorsList.appendChild(tr);
                });
            } else {
                contributorsList.innerHTML = "<tr><td colspan='3' style='padding:10px;'>No contributions yet.</td></tr>";
            }

            // Render non-contributors
            const nonContributorsList = document.getElementById("nonContributorsList");
            nonContributorsList.innerHTML = "";
            if (data.non_contributors.length > 0) {
                data.non_contributors.forEach(n => {
                    const tr = document.createElement("tr");
                    tr.innerHTML = `
                        <td style="padding:10px; border-bottom:1px solid #eee;">${n.name}</td>
                    `;
                    nonContributorsList.appendChild(tr);
                });
            } else {
                nonContributorsList.innerHTML = "<tr><td style='padding:10px;'>All members have contributed 🎉</td></tr>";
            }

        } catch (error) {
            console.error("Error loading group details:", error);
            document.getElementById("groupName").textContent = "Error loading group details.";
        }
    }

    document.getElementById("searchInput").addEventListener("input", () => {
    const query = document.getElementById("searchInput").value.toLowerCase();
    document.querySelectorAll("#contributorsList tr").forEach(row => {
        const name = row.children[0]?.textContent.toLowerCase() || "";
        row.style.display = name.includes(query) ? "" : "none";
    });
});

    // Handle contribution
    document.getElementById("contributeBtn").addEventListener("click", async () => {
        const amount = document.getElementById("amount").value;
        const message = document.getElementById("contributeMsg");

        if (!amount || amount <= 0) {
            message.style.color = "red";
            message.textContent = "Please enter a valid amount.";
            return;
        }

        try {
            const contributeRes = await fetch(`http://127.0.0.1:8000/api/groups/${groupId}/contribute`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Authorization": "Bearer " + token,
                    "Accept": "application/json"
                },
                body: JSON.stringify({ amount })
            });

            const result = await contributeRes.json();

            if (contributeRes.ok) {
                message.style.color = "green";
                message.textContent = "✅ Contribution successful!";
                await loadGroupData(); // refresh data dynamically
                document.getElementById("amount").value = "";
            } else {
                message.style.color = "red";
                message.textContent = result.message || "⚠️ Contribution failed.";
            }
        } catch (err) {
            message.style.color = "red";
            message.textContent = "Network error.";
        }
    });

    // Load data initially
    loadGroupData();
});
</script>
@endsection

