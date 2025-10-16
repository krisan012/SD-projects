<?php

namespace App\Http\Controllers;

use App\Models\Project;

class DeleteProjectController extends Controller
{
    public function __invoke(Project $project)
    {
        $this->authorize('delete', $project);

        $project->delete();

        return response()->json(['message' => 'Project deleted successfully']);
    }
}
