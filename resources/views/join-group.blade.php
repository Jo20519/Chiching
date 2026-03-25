@extends('layouts.app')

@section('content')
<div class="container" style="max-width:600px; margin-top:40px;">
    <h2 style="text-align:center; color:#0d6efd;">Join a Group</h2>

    <form id="joinGroupForm" style="margin-top:30px;">
        <div style="margin-bottom:15px;">
            <label for="groupName" style="display:block; font-weight:bold;">Select Group:</label>
            <select id="groupName" name="name"
                    style="width:100%; padding:10px; border:1px solid #ccc; border-radius:5px;">
            </select>
        </div>

        <button type="submit"
                style="background-color:#0d6efd; color:white; border:none; padding:10px 20px; border-radius:5px; cursor:pointer;">
            Join Group
        </button>
    </form>

    <p id="message" style="margin-top:20px; text-align:center;"></p>
</div>

<script>
document.addEventListener("DOMContentLoaded", async () => {
    const token = localStorage.getItem("auth_token");
    const groupSelect = document.getElementById("groupName");

    if (!token) {
        window.location.href = "/login";
        return;
    }

    // ✅ Fetch available groups
    try {
        const response = await fetch("http://127.0.0.1:8000/api/groups", {
            headers: {
                "Authorization": "Bearer " + token,
                "Accept": "application/json"
            }
        });
        const data = await response.json();
        console.log(data);

        data.groups.data.forEach(group => {
            const option = document.createElement("option");
            option.value = group.name;
            option.textContent = group.name;
            groupSelect.appendChild(option);
        });
    } catch (error) {
        console.error("Error fetching groups:", error);
    }
});

document.getElementById("joinGroupForm").addEventListener("submit", async (e) => {
    e.preventDefault();

    const token = localStorage.getItem("auth_token");
    const name = document.getElementById("groupName").value;
    const message = document.getElementById("message");

    try {
        const response = await fetch("http://127.0.0.1:8000/api/groups/join", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Authorization": "Bearer " + token,
                "Accept": "application/json"
            },
            body: JSON.stringify({ name })
        });

        const data = await response.json();

        if (response.ok) {
            message.style.color = "green";
            message.textContent = data.message;
            setTimeout(() => window.location.href = "/dashboard", 1500);
        } else {
            message.style.color = "red";
            message.textContent = data.message || "❌ Failed to join group.";
        }
    } catch (error) {
        console.error("Error joining group:", error);
        message.style.color = "red";
        message.textContent = "⚠️ Something went wrong.";
    }
});
</script>
@endsection
