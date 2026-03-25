@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 400px; margin-top: 50px;">
    <h2 style="text-align:center; color:#0d6efd;">Login to Chiching</h2>
    <p style="text-align:center;">Welcome back! Access your savings group below.</p>

    {{-- Show validation errors --}}
    @if(session('error'))
        <p style="color:red; text-align:center;">{{ session('error') }}</p>
    @endif

    <form method="POST" action="/login" style="margin-top: 20px;">
        @csrf

        <div style="margin-bottom: 15px;">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                   style="width:100%; padding:10px; border-radius:5px; border:1px solid #ccc; box-sizing:border-box;">
            @error('email')
                <p style="color:red; font-size:13px; margin-top:4px;">{{ $message }}</p>
            @enderror
        </div>

        <div style="margin-bottom: 20px;">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required
                   style="width:100%; padding:10px; border-radius:5px; border:1px solid #ccc; box-sizing:border-box;">
            @error('password')
                <p style="color:red; font-size:13px; margin-top:4px;">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
                style="width:100%; background-color:#0d6efd; color:white; border:none; padding:10px; border-radius:5px; cursor:pointer;">
            Login
        </button>

        <p style="text-align:center; margin-top:15px;">
            Don't have an account? <a href="/register">Register</a>
        </p>
    </form>
</div>
@endsection