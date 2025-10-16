<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignProjectRequest;
use App\Models\Project;
use App\Models\User;

class AssignProjectController extends Controller
{
    public function __invoke(AssignProjectRequest $request, Project $project)
    {
        $this->authorize('assign', $project);

        $user = User::findOrFail($request->user_id);
        
        $project->update(['user_id' => $user->id]);

        return response()->json([
            'message' => 'Project assigned successfully.',
            'project' => $project->fresh(['user', 'tasks']),
        ]);
    }
}
