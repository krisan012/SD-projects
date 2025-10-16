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

	<form method="POST" action="{{ route('register') }}" class="space-y-4">
		@csrf

		<div>
			<label for="name" class="block text-sm">Name</label>
			<input id="name" name="name" type="text" required autofocus value="{{ old('name') }}" class="w-full border p-2 rounded" />
		</div>

		<div>
			<label for="email" class="block text-sm">Email</label>
			<input id="email" name="email" type="email" required autofocus value="{{ old('email') }}" class="w-full border p-2 rounded" />
		</div>

		<div>
			<label for="password" class="block text-sm">Password</label>
			<input id="password" name="password" type="password" required class="w-full border p-2 rounded" />
		</div>

		<div>
			<label for="password_confirmation" class="block text-sm">Confirm Password</label>
			<input id="password_confirmation" name="password_confirmation" type="password" required class="w-full border p-2 rounded" />
		</div>

		<button type="submit" class="w-full p-2 rounded bg-black text-white">Create account</button>
	</form>
</main>

       
@endsection
