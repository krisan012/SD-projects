<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class ListProjectController extends Controller
{
    public function __invoke()
    {
        $projects = Project::with(['tasks', 'user'])->visibleTo(Auth::user())->latest()->get();
        
        return view('project.list', compact('projects'));
    }
}
