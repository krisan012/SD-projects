@extends('layouts.app')

@section('content')
	<main class="max-w-2xl mx-auto p-6">
		<h2 class="text-lg font-medium mb-4">Create new Project</h2>

		<form x-data="projectCreate" @submit.prevent="submitProject">
			@csrf

			<div class="space-y-2">
				<div>
					<label for="title" class="block text-sm font-semibold">Title *</label>
					<input x-model="title" name="title" type="title" class="w-full border p-2 rounded" />
				</div>

				<div>
					<label for="description" class="block text-sm font-semibold">Description</label>
					<textarea name="description" x-model="description" class="w-full border p-2 rounded"></textarea>
				</div>
				<div>
					<label for="deadline" class="block text-sm font-semibold">Deadline *</label>
					<input name="deadline" x-model="deadline" type="date" class="w-full border p-2 rounded"></textarea>
				</div>
			</div>

			<div class="my-5 py-2 px-5 border-2 border-dashed">
				<div class="flex justify-between">
					<h2 class="text-2xl">Tasks</h2>

					<button class="p-2 mb-2 rounded bg-black text-white flex" @click="addTask" type="button">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
							<path fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
								stroke-linejoin="round" d="M12 5v14M5 12h14" />
						</svg>
						Add Task
					</button>
				</div>

				<template x-for="(task, index) in tasks" :key="index">
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

			<button type="submit" class="w-full p-2 rounded bg-black text-white mt-2" :disabled="isloading">
				<template x-if="isloading">
					<span class="spinner"></span>
				</template>
				Submit
			</button>
		</form>
	</main>
@endsection

@section('scripts')
	<script>
		document.addEventListener('alpine:init', () => {
			Alpine.data('projectCreate', () => ({
				title: '',
				description: '',
				deadline: '',
				message: '',
				tasks: [],
				isloading: false,
				addTask() {
					this.tasks.push({ title: '', status: 'todo', due_date: '' });
				},
				removeTask(index) {
					this.tasks.splice(index, 1);
				},
				async submitProject() {
					try {
						const data = {
							title: this.title,
							description: this.description,
							deadline: this.deadline,
							tasks: this.tasks
						}
						this.isloading = true
						const response = await axios.post('{{ route('project.store') }}', data, { withCredentials: true })
						this.isloading = false
						toast(response.data.message)
					} catch (error) {
						console.log(error)
                        if (error.response) {
                            this.message = error.response.data.message || 'Project Creation Failed';
                            console.error(error.response.data);
                        } else {
                            this.message = 'Network error';
                        }
                    } finally {
                        this.isloading = false;
                    }

				}
			}));
		});
	</script>
@endsection