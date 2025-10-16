@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <main class="max-w-md mx-auto p-6">
        <h2 class="text-lg font-medium mb-4">Sign in to your account</h2>

        @if ($errors->any())
            <div class="mb-4 text-sm text-red-600">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-sm">Email</label>
                <input id="email" name="email" type="email" required autofocus value="{{ old('email') }}" class="w-full border p-2 rounded" />
            </div>

            <div>
                <label for="password" class="block text-sm">Password</label>
                <input id="password" name="password" type="password" required class="w-full border p-2 rounded" />
            </div>

            <div class="flex items-center justify-between">
                <label class="text-sm flex items-center gap-2">
                    <input type="checkbox" name="remember" />
                    <span>Remember me</span>
                </label>
            </div>

            <button type="submit" class="w-full p-2 rounded bg-black text-white">Sign in</button>
        </form>
    </main>
@endsection


