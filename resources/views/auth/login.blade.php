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

        <form x-data="loginForm()" @submit.prevent="login">
            @csrf

            <div>
                <label for="email" class="block text-sm">Email</label>
                <input x-model="email" id="email" name="email" type="email" required autofocus value="{{ old('email') }}"
                    class="w-full border p-2 rounded" />
            </div>

            <div>
                <label for="password" class="block text-sm">Password</label>
                <input x-model="password" id="password" name="password" type="password" required
                    class="w-full border p-2 rounded" />
            </div>

            <div class="flex items-center justify-between">
                <label class="text-sm flex items-center gap-2">
                    <input type="checkbox" name="remember" />
                    <span>Remember me</span>
                </label>
            </div>

            <p x-text="message" class="text-red-600"></p>

            <button type="submit" class="w-full p-2 rounded bg-black text-white">Sign in</button>
        </form>
    </main>
@endsection

@section('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('loginForm', () => ({
                email: '',
                password: '',
                remember: false,
                loading: false,
                message: '',
                async login() {
                    try {
                        const response = await axios.post('/login', {
                            email: this.email,
                            password: this.password,
                        });

                        this.message = 'Login successful! Redirecting...';
                        console.log(response.data);
                    } catch (error) {
                        if (error.response) {
                            this.message = error.response.data.message || 'Login failed';
                            console.error(error.response.data);
                        } else {
                            this.message = 'Network error';
                        }
                    } finally {
                        this.loading = false;
                    }
                }
            }));
        });
    </script>
@endsection