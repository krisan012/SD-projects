<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>@yield('title', config('app.name', 'Laravel'))</title>
	<meta name="csrf-token" content="{{ csrf_token() }}">
	@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100">
	<div class="min-h-screen">

		{{-- Navigation --}}
		<nav class="bg-white border-b border-gray-200">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
				<div class="flex justify-between h-16">
					<div class="flex items-center">
						<a href="/" class="flex items-center space-x-2">
							<span class="font-semibold text-lg text-gray-800">{{ config('app.name', 'logo') }}</span>
						</a>
					</div>
					<div class="flex items-center space-x-4">
						@auth
							<form action="{{ route('logout') }}" method="POST">
								@csrf
								<button type="submit"
									class="font-semibold text-sm text-gray-700 hover:text-blue-600">logout</a>
							</form>

						@endauth
						@guest
							<a href="{{ route('login.form') }}"
								class="font-semibold text-sm text-gray-700 hover:text-blue-600">Login</a>
						@endguest
					</div>
				</div>
			</div>
		</nav>

		{{-- Page Content --}}
		<main class="py-8">
			{{ $slot ?? '' }}
			@yield('content')
		</main>
	</div>

	<script>
		document.addEventListener('alpine:init', () => {
			Alpine.store('toast', {
				show: false,
				message: '',
				timeout: null,
				trigger(message, type = 'success') {
					this.message = message;
					this.show = true;

					const toastEl = document.querySelector('#global-toast');
					toastEl.className = `fixed top-5 right-5 text-white px-4 py-2 rounded-lg shadow-lg ${type === 'error' ? 'bg-red-600' : 'bg-green-600'
						}`;

					clearTimeout(this.timeout);
					this.timeout = setTimeout(() => this.show = false, 3000);
				}
			});
		});
		window.toast = (message, type) => Alpine.store('toast').trigger(message, type);
	</script>

	<div id="global-toast" x-data x-show="$store.toast.show" x-transition x-text="$store.toast.message"
		class="fixed top-5 right-5 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg" style="display: none"></div>

	<div x-data="{ confirmDelete: false, deletePayload: {} }"
		x-on:open-delete.window="deletePayload = $event.detail; confirmDelete = true" x-show="confirmDelete"
		x-transition class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
		style="display: none">
		<div class="bg-white rounded-lg shadow-lg p-6 w-80">
			<h3 class="font-semibold text-lg mb-4">Confirm Delete</h3>
			<p class="text-gray-700 mb-6">
				Are you sure you want to delete this
				<span x-text="deletePayload.type || 'item'"></span>?
			</p>
			<div class="flex justify-end space-x-3">
				<button @click="confirmDelete = false"
					class="px-3 py-1 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
				<button @click="$dispatch('confirm-delete', deletePayload); confirmDelete = false"
					class="px-3 py-1 rounded bg-red-600 text-white hover:bg-red-700">Delete</button>
			</div>
		</div>
	</div>

	@yield('scripts')
</body>

</html>