	<!DOCTYPE html>
	<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>@yield('title', config('app.name', 'Laravel'))</title>
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
								logo<span class="font-semibold text-lg text-gray-800">{{ config('app.name', 'Laravel') }}</span>
							</a>
						</div>
						<div class="flex items-center space-x-4">
							<a href="{{ route('login.form') }}" class="text-sm text-gray-700 hover:text-blue-600">Login</a>
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
	</body>
	</html>
