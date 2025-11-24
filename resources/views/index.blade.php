@extends('layouts.app')



@section('content')
<div style="text-align:center; margin-top:80px;">
    <h1 style="color:#0d6efd;">Welcome to Chiching</h1>
    <p style="font-size:18px; color:#555;">Your digital savings group app — save, grow, and achieve together.</p>

    <img src="{{ asset('images/icons/web-app-manifest-192x192.png') }}" 
         alt="Chiching Logo" width="150" style="margin:30px auto; display:block;">

    <div style="margin-top:40px;">
        <a href="/login" 
           style="background-color:#0d6efd; color:white; text-decoration:none; padding:12px 30px; border-radius:5px; margin-right:10px;">
            Login
        </a>

        <a href="/register" 
           style="background-color:#198754; color:white; text-decoration:none; padding:12px 30px; border-radius:5px;">
            Register
        </a>
    </div>
</div>
@endsection
