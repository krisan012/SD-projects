@extends('layouts.app')

@section('title', 'Register')

@section('content')

	<main class="max-w-md mx-auto p-6">
		<h2 class="text-lg font-medium mb-4">Create your account</h2>

		@if ($errors->any())
			<div class="mb-4 text-sm text-red-600">
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif

		<form x-data="registerForm()" class="space-y-4" @submit.prevent="register">
			@csrf

			<div>
				<label for="name" class="block text-sm">Name</label>
				<input x-model="name" name="name" type="text" required autofocus value="{{ old('name') }}"
					class="w-full border p-2 rounded" />
			</div>

			<div>
				<label for="email" class="block text-sm">Email</label>
				<input x-model="email" name="email" type="email" required autofocus value="{{ old('email') }}"
					class="w-full border p-2 rounded" />
			</div>

			<div>
				<label for="password" class="block text-sm">Password</label>
				<input x-model="password" name="password" type="password" required class="w-full border p-2 rounded" />
			</div>

			<div>
				<label for="password_confirmation" class="block text-sm">Confirm Password</label>
				<input x-model="password_confirmation" name="password_confirmation" type="password" required
					class="w-full border p-2 rounded" />
			</div>

			<p x-text="message" class="text-red-600"></p>

			<button type="submit" class="w-full p-2 rounded bg-black text-white" :disabled="loading">Create account</button>
		</form>
	</main>

@endsection

@section('scripts')
	<script>
		document.addEventListener('alpine:init', () => {
			Alpine.data('registerForm', () => ({
				name: '',
				email: '',
				password: '',
				password_confirmation: '',
				loading: false,
				message: '',
				success: false,
				async register() {
					this.loading = true;
					this.message = '';
					this.success = false;

					try {
						const response = await axios.post('/register', {
							name: this.name,
							email: this.email,
							password: this.password,
							password_confirmation: this.password_confirmation,
						}, { withCredentials: true });

						this.success = true;
						this.message = 'Registration successful! Redirecting...';
						window.location.href = '/'
					} catch (error) {
						if (error.response) {
							if (error.response.status === 422) {
								const errors = error.response.data.errors;
								this.message = Object.values(errors).flat().join(' ');
							} else {
								this.message = error.response.data.message || 'Registration failed';
							}
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