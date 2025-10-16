@extends('layouts.app')

@section('title', 'Login')

@section('header')
    <header class="p-4 border-b">
        <div class="max-w-2xl mx-auto flex items-center justify-between">
            <h1 class="text-xl font-semibold">My App</h1>
            <nav class="text-sm">
                <a href="/" class="underline">Home</a>
            </nav>
        </div>
    </header>
@endsection

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
                <a href="#" class="text-sm underline">Forgot password?</a>
            </div>

            <button type="submit" class="w-full p-2 rounded bg-black text-white">Sign in</button>
        </form>
    </main>
@endsection

@section('footer')
    <footer class="p-4 border-t mt-8">
        <div class="max-w-2xl mx-auto text-sm text-gray-600">© {{ date('Y') }} My App</div>
    </footer>
@endsection

@section('scripts')
    
@endsection


