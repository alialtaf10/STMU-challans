@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-10">
    <div class="card bg-base-100 shadow-lg">
        <div class="card-body">
            <h2 class="card-title">Change Password</h2>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text">Current Password</span>
                    </label>
                    <input type="password" name="current_password" class="input input-bordered" required>
                    @error('current_password')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text">New Password</span>
                    </label>
                    <input type="password" name="new_password" class="input input-bordered" required>
                    @error('new_password')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text">Confirm New Password</span>
                    </label>
                    <input type="password" name="new_password_confirmation" class="input input-bordered" required>
                </div>

                <div class="form-control">
                    <button class="btn btn-primary">Update Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
