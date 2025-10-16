<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'required|date',
            'tasks' => 'array',
            'tasks.*.id' => 'nullable|integer|exists:tasks,id',
            'tasks.*.title' => 'required|string|max:255',
            'tasks.*.status' => 'required|in:todo,in_progress,done',
            'tasks.*.due_date' => 'nullable|date',
            'deletedTasks' => 'array',
            'deletedTasks.*' => 'integer|exists:tasks,id',
        ];
    }
}
