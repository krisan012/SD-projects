<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use App\Models\Task;

class UpdateProjectController extends Controller
{
    public function __invoke(UpdateProjectRequest $request, Project $project)
    {
        $project->update($request->only(['title', 'description', 'deadline']));

        $project->tasks()->delete();

        $project->tasks()->createMany($request->tasks);
    
        return response()->json(['message' => 'Project updated successfully']);
    }
}
