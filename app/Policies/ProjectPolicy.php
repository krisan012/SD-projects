<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    public function delete(User $user, Project $project): bool
    {
        return $user->hasRole('admin') || $project->user_id === $user->id;
    }

    public function update(User $user, Project $project): bool
    {
        return $user->hasRole('admin') || $project->user_id === $user->id;
    }

    public function assign(User $user, Project $project): bool
    {
        return $user->hasRole('admin');
    }
}
