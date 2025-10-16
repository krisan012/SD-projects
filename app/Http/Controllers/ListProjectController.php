<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ListProjectController extends Controller
{
    public function __invoke()
    {
        $projects = Project::with(['tasks', 'user'])->latest()->get();

        return view('project.list', compact('projects'));
    }
}
