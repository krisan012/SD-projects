<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Models\Project;
use Illuminate\Http\Request;

class CreateProjectController extends Controller
{
    public function __invoke(StoreProjectRequest $projectRequest)
    {
        $project = Project::create([
            'user_id' => auth()->id(),
            'title' => $projectRequest->title,
            'description' => $projectRequest->description,
            'deadline' => $projectRequest->deadline,
        ]);

        if ($projectRequest->has('tasks')) {
            $project->tasks()->createMany(records: $projectRequest->tasks);
        }

        $project->refresh();

        return response()->json([
            'message' => 'Project created successfully!',
            'project' => $project,
        ], 201);
    }
}
