@extends('layouts.app')

@section('content')
	<main class="max-w-xl mx-auto p-6">

		<div class="flex justify-between mb-5">
			<div></div>
			<a href="{{ route(('project.create')) }}" class="flex p-2 rounded bg-black text-white mt-2">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
					<path fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
						d="M12 5v14M5 12h14" />
				</svg>
				Create Project
			</a>
		</div>

		<div x-data="projects" class="space-y-4">
			@foreach($projects as $project)
				<div class="border rounded-lg p-4 shadow-md">
					<div class="flex justify-between items-center cursor-pointer"
						@click="openProject === {{ $project->id }} ? openProject = null : openProject = {{ $project->id }}">
						<a href="{{ route('project.show', $project->id) }}" class="hover:text-blue-600">
							<h3 class="font-semibold text-lg">{{ $project->title }}</h3>
						</a>
						<span class="text-gray-500 text-sm transform transition-transform duration-300"
							:class="openProject == {{ $project->id }} ? 'rotate-180' : ''">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
								stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
							</svg>
						</span>
					</div>

					<div x-show="openProject === {{ $project->id }}" x-transition class="mt-3 space-y-2 font-semibold">
						<p class="text-gray-700">{{ $project->description ?? 'No description' }}</p>
						<p class="text-gray-500 text-sm">deadline: {{ $project->deadline }}</p>
						<p class="text-gray-500 text-sm">Created by: {{ $project->user->name }}</p>

						<div class="mt-4 p-3 bg-gray-50 rounded-lg">
							<div class="flex justify-between items-center mb-2">
								<span class="text-sm font-medium text-gray-700">Progress</span>
								<span class="text-sm text-gray-600">
									{{ $project->tasks->where('status', 'done')->count() }} of {{ $project->tasks->count() }} tasks completed
								</span>
							</div>
							<div class="w-full bg-gray-200 rounded-full h-2">
								@php
									$totalTasks = $project->tasks->count();
									$completedTasks = $project->tasks->where('status', 'done')->count();
									$progressPercentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
								@endphp
								<div class="bg-green-500 h-2 rounded-full transition-all duration-300" 
									 style="width: {{ $progressPercentage }}%"></div>
							</div>
							<div class="mt-1 text-center">
								<span class="text-sm font-semibold text-gray-800">{{ $progressPercentage }}%</span>
							</div>
						</div>

						<h4 class="font-medium mt-2">Tasks</h4>

						@forelse($project->tasks as $task)
							<div class="text-sm border border-dashed p-5 relative space-y-2">
								<div class="font-semibold">Title: {{ $task->title }}</div>
								<div class="font-semibold">Status: {{ $task->status }}</div>
								<div class="font-semibold">Due: {{ $task->due_date ?? '-' }}</div>
							</div>

						@empty
							<div class="text-gray-400">No tasks yet</div>
						@endforelse
					</div>
				</div>
			@endforeach

		</div>
	</main>
@endsection

@section('scripts')
	<script>
		document.addEventListener('alpine:init', () => {
			Alpine.data('projects', () => ({
				openProject: null,
				init() {
					window.addEventListener('confirm-delete', event => {
						this.deleteTask(event.detail);
					});
				},
				deleteTask(id) {
					console.log(id)
				}
			}))
		})
	</script>
@endsection