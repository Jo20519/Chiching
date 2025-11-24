@extends('layouts.app')

@section('content')
<div class="container" style="max-width:600px; margin-top:40px;">
    <h2 style="text-align:center; color:#0d6efd;">Create a New Group</h2>
    
    <form id="createGroupForm" style="margin-top:30px;">
        <div style="margin-bottom:15px;">
            <label for="groupName" style="display:block; font-weight:bold;">Group Name:</label>
            <input type="text" id="groupName" name="name" required
                   style="width:100%; padding:10px; border:1px solid #ccc; border-radius:5px;">
        </div>

        <div style="margin-bottom:15px;">
            <label for="description" style="display:block; font-weight:bold;">Description:</label>
            <textarea id="description" name="description" rows="3"
                      style="width:100%; padding:10px; border:1px solid #ccc; border-radius:5px;"></textarea>
        </div>

        <button type="submit"
                style="background-color:#0d6efd; color:white; border:none; padding:10px 20px; border-radius:5px; cursor:pointer;">
            Create Group
        </button>
    </form>

    <p id="message" style="margin-top:20px; text-align:center;"></p>
</div>

<script>
document.getElementById("createGroupForm").addEventListener("submit", async (e) => {
    e.preventDefault();

    const token = localStorage.getItem("auth_token");
    const name = document.getElementById("groupName").value;
    const description = document.getElementById("description").value;
    const message = document.getElementById("message");

    if (!token) {
        window.location.href = "/login";
        return;
    }

    try {
        const response = await fetch("http://127.0.0.1:8000/api/groups", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Authorization": "Bearer " + token,
                "Accept": "application/json"
            },
            body: JSON.stringify({ name, description })
        });

        const data = await response.json();

        if (response.ok) {
            message.style.color = "green";
            message.textContent = "✅ Group created successfully!";
            setTimeout(() => window.location.href = "/dashboard", 1500);
        } else {
            message.style.color = "red";
            message.textContent = data.message || "❌ Failed to create group.";
        }
    } catch (error) {
        console.error("Error creating group:", error);
        message.style.color = "red";
        message.textContent = "⚠️ Something went wrong. Try again later.";
    }
});
</script>
@endsection
