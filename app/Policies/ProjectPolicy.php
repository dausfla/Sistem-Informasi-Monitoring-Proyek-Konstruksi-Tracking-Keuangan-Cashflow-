<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // All roles can view
    }

    public function view(User $user, Project $project): bool
    {
        return true; // All roles can view
    }

    public function create(User $user): bool
    {
        return $user->isFullAccess() || $user->isPartialAccess();
    }

    public function update(User $user, Project $project): bool
    {
        return $user->isFullAccess() || $user->isPartialAccess();
    }

    public function delete(User $user, Project $project): bool
    {
        return $user->isFullAccess();
    }

    public function updateFinancials(User $user): bool
    {
        return $user->isFullAccess();
    }
}
