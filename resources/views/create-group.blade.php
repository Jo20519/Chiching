@extends('layouts.app')

@section('content')
<div class="container" style="max-width:600px; margin-top:40px;">
    <h2 style="text-align:center; color:#0d6efd;">Create a New Group</h2>

    @if(session('success'))
        <p style="color:green; text-align:center;">{{ session('success') }}</p>
    @endif

    @if($errors->any())
        <p style="color:red; text-align:center;">{{ $errors->first() }}</p>
    @endif

    <form method="POST" action="/groups" style="margin-top:30px;">
        @csrf

        <div style="margin-bottom:15px;">
            <label for="groupName" style="display:block; font-weight:bold;">Group Name:</label>
            <input type="text" id="groupName" name="name" required value="{{ old('name') }}"
                   style="width:100%; padding:10px; border:1px solid #ccc; border-radius:5px;">
        </div>

        <div style="margin-bottom:15px;">
            <label for="description" style="display:block; font-weight:bold;">Description:</label>
            <textarea id="description" name="description" rows="3"
                      style="width:100%; padding:10px; border:1px solid #ccc; border-radius:5px;">{{ old('description') }}</textarea>
        </div>

        <div style="margin-bottom:15px;">
            <label for="contribution_amount" style="display:block; font-weight:bold;">Contribution Amount (KES):</label>
            <input type="number" id="contribution_amount" name="contribution_amount" value="{{ old('contribution_amount') }}"
                   style="width:100%; padding:10px; border:1px solid #ccc; border-radius:5px;">
        </div>

        <button type="submit"
                style="background-color:#0d6efd; color:white; border:none; padding:10px 20px; border-radius:5px; cursor:pointer;">
            Create Group
        </button>
    </form>
</div>
@endsection