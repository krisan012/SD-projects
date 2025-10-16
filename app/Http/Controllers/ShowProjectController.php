<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class ShowProjectController extends Controller
{
    public function __invoke(Project $project)
    {
        $project->load(['tasks', 'user']);
        $users = User::all();
        return view('project.show', compact('project', 'users'));
    }
}
