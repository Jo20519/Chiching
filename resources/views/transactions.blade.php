@extends('layouts.app')

@section('content')
<div class="container" style="max-width:900px; margin-top:40px;">
    <h2 id="groupName" style="text-align:center; color:#0d6efd; margin-bottom:20px;">
        Loading group...
    </h2>

    <div id="transactionsContainer">
        <p id="loading" style="text-align:center;">Loading transactions...</p>
        <ul id="transactionsList" style="list-style:none; padding:0;"></ul>
        <p id="noTransactions" style="display:none; text-align:center; margin-top:20px;">
            No transactions found for this group.
        </p>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", async () => {
    const token = localStorage.getItem("auth_token");
    const transactionsList = document.getElementById("transactionsList");
    const loading = document.getElementById("loading");
    const noTransactions = document.getElementById("noTransactions");
    const groupNameEl = document.getElementById("groupName");

    if (!token) {
        window.location.href = "/login";
        return;
    }

    const groupId = @json($id); // passed from controller
  

    try {
        // Fetch group info and transactions
        const response =  await fetch(`http://127.0.0.1:8000/api/groups/${groupId}/transactions`, {
            headers: {
                "Authorization": "Bearer " + token,
                "Accept": "application/json"
            }
        });

        const data = await response.json();
        loading.style.display = "none";

        if (response.ok) {
            // Set actual group name
            groupNameEl.textContent = `Transactions for ${data.group_name}`;

            if (data.transactions && data.transactions.length > 0) {
                data.transactions.forEach(tx => {
                    const li = document.createElement("li");
                    li.style.border = "1px solid #ccc";
                    li.style.borderRadius = "8px";
                    li.style.padding = "12px";
                    li.style.marginBottom = "10px";
                    li.style.background = "#f9f9f9";
                    li.innerHTML = `
                        <p><strong>User:</strong> ${tx.user.name}</p>
                        <p><strong>Amount:</strong> ${tx.amount}</p>
                        <p><strong>Date:</strong> ${new Date(tx.created_at).toLocaleString()}</p>
                    `;
                    transactionsList.appendChild(li);
                });
            } else {
                noTransactions.style.display = "block";
            }
        } else {
            groupNameEl.textContent = "Group not found";
            noTransactions.style.display = "block";
        }

    } catch (error) {
        console.error("Error loading transactions:", error);
        loading.textContent = "Failed to load transactions.";
    }
});
</script>
@endsection
