@extends('layouts.app')

@section('content')
	<main class="max-w-2xl mx-auto p-6">
		<form x-data="projectShow" @submit.prevent="submitUpdate" data-update-url="{{ route('project.update', $project->id) }}">
			@csrf

			<div class="space-y-2">
				<div>
					<label for="title" class="block text-sm font-semibold">Title *</label>
					<input x-model="project.title" name="title" type="title" class="w-full border p-2 rounded" />
				</div>

				<div>
					<label for="description" class="block text-sm font-semibold">Description</label>
					<textarea name="description" x-model="project.description" class="w-full border p-2 rounded"></textarea>
				</div>
				<div>
					<label for="deadline" class="block text-sm font-semibold">Deadline *</label>
					<input name="deadline" x-model="project.deadline" type="date"
						class="w-full border p-2 rounded"></textarea>
				</div>
			</div>

			@if(auth()->user()->hasRole('admin'))
			<div class="my-5 py-4 px-5 border-2 border-blue-200 bg-blue-50 rounded-lg">
				
				<div class="flex items-center space-x-4">
					<div class="flex-1">
						<label for="assign-user" class="block text-sm font-medium text-gray-700 mb-1">Assign to User:</label>
						<select id="assign-user" x-model="selectedUserId" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
							<option value="">Select a user...</option>
							@foreach($users as $user)
								<option value="{{ $user->id }}" {{ $user->id == $project->user_id ? 'disabled' : '' }}>
									{{ $user->name }} ({{ $user->email }})
									@if($user->id == $project->user_id) - Current Owner @endif
								</option>
							@endforeach
						</select>
					</div>
					
					<button type="button" 
							@click="assignProject()" 
							:disabled="!selectedUserId || isAssigning"
							class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed flex items-center">
						<template x-if="isAssigning">
							<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
								<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
								<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
							</svg>
						</template>
						<span x-text="isAssigning ? 'Assigning...' : 'Assign Project'"></span>
					</button>
				</div>
				
				<div x-show="assignmentMessage" x-text="assignmentMessage" class="mt-2 text-sm" :class="assignmentSuccess ? 'text-green-600' : 'text-red-600'"></div>
			</div>
			@endif

			<div class="my-5 py-2 px-5 border-2 border-dashed">
				<div class="flex justify-between items-center mb-4">
					<h2 class="text-2xl">Tasks</h2>

					<button class="p-2 mb-2 rounded bg-black text-white flex" type="button" @click="addtask()">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
							<path fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
								stroke-linejoin="round" d="M12 5v14M5 12h14" />
						</svg>
						Add Task
					</button>
				</div>

				<div class="mb-6 p-4 bg-gray-50 rounded-lg">
					<div class="flex justify-between items-center mb-2">
						<span class="text-sm font-medium text-gray-700">Project Progress</span>
						<span class="text-sm text-gray-600" x-text="`${completedTasks} of ${totalTasks} tasks completed`"></span>
					</div>
					<div class="w-full bg-gray-200 rounded-full h-3">
						<div class="bg-green-500 h-3 rounded-full transition-all duration-300" 
							 :style="`width: ${progressPercentage}%`"></div>
					</div>
					<div class="mt-2 text-center">
						<span class="text-lg font-semibold text-gray-800" x-text="`${progressPercentage}%`"></span>
					</div>
				</div>

				<template x-for="(task, index) in project.tasks" :key="index">
					<div class="border shadow-lg p-3 rounded space-y-2 mb-5">
						<label class="block">
							<span class="text-sm font-semibold">Title *</span>
							<input type="text" x-model="task.title" class="w-full border p-2 rounded">
						</label>

						<label class="block">
							<span class="text-sm font-semibold">Status *</span>
							<select x-model="task.status" class="w-full border p-2 rounded">
								<option value="todo">Todo</option>
								<option value="in_progress">In Progress</option>
								<option value="done">Done</option>
							</select>
						</label>

						<label class="block">
							<span class="text-sm font-semibold">Due Date</span>
							<input type="date" x-model="task.due_date" class="w-full border p-2 rounded">
						</label>

						<button @click="removeTask(index)" class="p-1 mb-2 rounded text-red-600 hover:text-red-700 flex"
							type="button">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
								<path fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
									d="M6 6L18 18M6 18L18 6" />
							</svg>
							Remove
						</button>
					</div>
				</template>
			</div>

			<p x-text="message" class="text-red-600"></p>

			<div class="flex justify-between space-x-6">
				<button type="submit" class="w-1/4 p-2 rounded bg-black text-white mt-2" :disabled="isloading">
					<template x-if="isloading">
						<span class="spinner"></span>
					</template>
					Update
				</button>

				<button type="button" class="w-1/6 p-2 rounded bg-red-600 text-white mt-2" :disabled="isloading" @click="deleteProject()">
					<template x-if="isloading">
						<span class="spinner"></span>
					</template>
					Delete
				</button>
			</div>
			
		</form>
	</main>
@endsection

@section('scripts')
	<script>
		document.addEventListener('alpine:init', () => {
			Alpine.data('projectShow', () => ({
				project: @json($project),
				message: '',
				isloading: false,
				
				selectedUserId: '',
				isAssigning: false,
				assignmentMessage: '',
				assignmentSuccess: false,
				
				get totalTasks() {
					return this.project.tasks.length;
				},
				
				get completedTasks() {
					return this.project.tasks.filter(task => task.status === 'done').length;
				},
				
				get progressPercentage() {
					if (this.totalTasks === 0) return 0;
					return Math.round((this.completedTasks / this.totalTasks) * 100);
				},
				addtask(){
					this.project.tasks.unshift({ title: '', due_date: '', status: 'todo' });
				},
				removeTask(index) {
					this.project.tasks.splice(index, 1);
				},
				async deleteProject()
				{
					this.isloading = true
					const deleteUrl = @json(route('project.delete', $project->id));
					await axios.post(deleteUrl);
					toast('Project Deleted successfully!');

					window.location.replace('/');
				},
				async submitUpdate() {
					this.isloading = true
					try {
						const data = {
							title: this.project.title,
							description: this.project.description,
							deadline: this.project.deadline,
							tasks: this.project.tasks,
						};

						const updateUrl = @json(route('project.update', $project->id));
						axios.post(updateUrl, data, { withCredentials: true });
						toast('Project updated successfully!');
					} catch (error) {
						console.error(error);
						toast('Failed to update project', 'error');
					} finally {
						this.isloading = false
					}
				},
				
				async assignProject() {
					if (!this.selectedUserId) return;
					
					this.isAssigning = true;
					this.assignmentMessage = '';
					
					try {
						await axios.get('/sanctum/csrf-cookie', { withCredentials: true });
						
						const response = await axios.post(@json(route('project.assign', $project->id)), {
							user_id: this.selectedUserId
						}, { withCredentials: true });
						
						this.assignmentMessage = response.data.message;
						this.assignmentSuccess = true;
						
						this.project = response.data.project;
						
						this.selectedUserId = '';
						
						toast('Project assigned successfully!');

						window.location.reload()
						
					} catch (error) {
						console.error('Assignment error:', error);
						
						if (error.response) {
							this.assignmentMessage = error.response.data.message || 'Failed to assign project';
						} else {
							this.assignmentMessage = 'Network error occurred';
						}
						this.assignmentSuccess = false;
						
						toast('Failed to assign project', 'error');
					} finally {
						this.isAssigning = false;
					}
				}
			}));
		});
	</script>
@endsection