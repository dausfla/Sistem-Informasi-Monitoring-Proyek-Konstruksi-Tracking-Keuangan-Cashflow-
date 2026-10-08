<?php

namespace App\Policies;

use App\Models\ProjectDocument;
use App\Models\User;

class ProjectDocumentPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, ProjectDocument $document): bool
    {
        // Restricted access can only view approved or paid documents, or all if full/partial
        if ($user->isRestrictedAccess()) {
            return in_array($document->status, ['approved', 'paid', 'pending_payment']);
        }
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isFullAccess() || $user->isPartialAccess();
    }

    public function approve(User $user, ProjectDocument $document): bool
    {
        return $user->isFullAccess();
    }

    public function delete(User $user, ProjectDocument $document): bool
    {
        return $user->isFullAccess();
    }
}
