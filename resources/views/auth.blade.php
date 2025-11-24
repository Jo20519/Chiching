@extends('layouts.app')

@section('content')
<div class="container" style="max-width:400px; margin-top:40px;">
    <h2>Login to Chiching</h2>

    <form id="loginForm">
        <label>Email</label>
        <input type="email" id="email" class="form-control" required>

        <label>Password</label>
        <input type="password" id="password" class="form-control" required>

        <button type="submit">Login</button>
    </form>

    <hr>

    <h3>New here? Register</h3>
    <form id="registerForm">
        <label>Name</label>
        <input type="text" id="name" class="form-control" required>

        <label>Email</label>
        <input type="email" id="regEmail" class="form-control" required>

        <label>Password</label>
        <input type="password" id="regPassword" class="form-control" required>

        <button type="submit">Register</button>
    </form>
</div>
@endsection
