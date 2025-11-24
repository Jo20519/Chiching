@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 400px; margin-top: 50px;">
    <h2 style="text-align:center; color:#0d6efd;">Login to Chiching</h2>
    <p style="text-align:center;">Welcome back! Access your savings group below.</p>

    <form id="loginForm" style="margin-top: 20px;">
        <div style="margin-bottom: 15px;">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required
                   style="width:100%; padding:10px; border-radius:5px; border:1px solid #ccc;">
        </div>

        <div style="margin-bottom: 20px;">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required
                   style="width:100%; padding:10px; border-radius:5px; border:1px solid #ccc;">
        </div>

        <button type="submit"
                style="width:100%; background-color:#0d6efd; color:white; border:none; padding:10px; border-radius:5px; cursor:pointer;">
            Login
        </button>

        <p id="loginMessage" style="text-align:center; margin-top:15px; color:red;"></p>
    </form>
</div>

<script>
document.getElementById("loginForm").addEventListener("submit", async function(event) {
    event.preventDefault();

    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value.trim();
    const message = document.getElementById("loginMessage");

    message.textContent = "Logging in...";

    try {
        const response = await fetch("http://127.0.0.1:8000/api/login", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json"
            },
            body: JSON.stringify({ email, password })
        });

        const data = await response.json();

        if (response.ok && data.token) {
            message.style.color = "green";
            message.textContent = "Login successful! Redirecting...";

            // ✅ Save token to localStorage for later API requests
            localStorage.setItem("auth_token", data.token);

            // Redirect to dashboard or homepage
            setTimeout(() => {
                window.location.href = "/dashboard"; 
            }, 1500);
        } else {
            message.style.color = "red";
            message.textContent = data.message || "Invalid credentials. Try again.";
        }
    } catch (error) {
        message.style.color = "red";
        message.textContent = "Server error. Please try again later.";
        console.error(error);
    }
});
</script>
@endsection
