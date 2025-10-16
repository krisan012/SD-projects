<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ShowProjectController extends Controller
{
    public function __invoke(Project $project)
    {
        $project->load('tasks');
        return view('project.show', compact('project'));
    }
}
