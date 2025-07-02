@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto p-6 bg-base-100 rounded-xl shadow-lg">
    <h2 class="text-3xl font-bold mb-6 text-center">Login</h2>

    @if ($errors->any())
        <div class="alert alert-error mb-4">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login.submit') }}">
        @csrf

        <div class="form-control mb-4">
            <label class="label">
                <span class="label-text">Email</span>
            </label>
            <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                placeholder="you@example.com"
                class="input input-neutral w-full"
            />
        </div>

        <div class="form-control mb-6">
            <label class="label">
                <span class="label-text">Password</span>
            </label>
            <input
                type="password"
                name="password"
                required
                placeholder="********"
                class="input input-neutral w-full"
            />
        </div>

        <button type="submit" class="btn btn-primary w-full">Login</button>
    </form>
</div>
@endsection
