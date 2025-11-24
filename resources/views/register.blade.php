@extends('layouts.app')

@section('content')
<div class="container" style="max-width:400px; margin-top:50px;">
    <h2 style="text-align:center; color:#0d6efd;">Create Your Account</h2>

    <form id="registerForm" style="margin-top:20px;">
        <div style="margin-bottom:15px;">
            <label for="name">Full Name</label><br>
            <input type="text" id="name" name="name" required
                   style="width:100%; padding:10px; border:1px solid #ccc; border-radius:5px;">
        </div>

        <div style="margin-bottom:15px;">
            <label for="email">Email</label><br>
            <input type="email" id="email" name="email" required
                   style="width:100%; padding:10px; border:1px solid #ccc; border-radius:5px;">
        </div>

        <div style="margin-bottom:20px;">
            <label for="password">Password</label><br>
            <input type="password" id="password" name="password" required
                   style="width:100%; padding:10px; border:1px solid #ccc; border-radius:5px;">
        </div>

        <button type="submit"
                style="width:100%; background-color:#0d6efd; color:white; border:none; padding:10px; border-radius:5px; cursor:pointer;">
            Register
        </button>
    </form>

    <p style="text-align:center; margin-top:20px;">
        Already have an account?
        <a href="/login" style="color:#0d6efd;">Login here</a>
    </p>

    <p id="message" style="text-align:center; color:red; margin-top:15px;"></p>
</div>

<script>
document.getElementById('registerForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();
    const message = document.getElementById('message');

    message.textContent = "";

    try {
        const response = await fetch("http://127.0.0.1:8000/api/register", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json"
            },
            body: JSON.stringify({ name, email, password })
        });

        const data = await response.json();

        if (response.ok) {
            message.style.color = "green";
            message.textContent = "Registration successful! Redirecting to login...";
            setTimeout(() => window.location.href = "/login", 1500);
        } else {
            message.textContent = data.message || "Registration failed. Please check your details.";
        }
    } catch (error) {
        message.textContent = "Error connecting to server.";
        console.error(error);
    }
});
</script>
@endsection
