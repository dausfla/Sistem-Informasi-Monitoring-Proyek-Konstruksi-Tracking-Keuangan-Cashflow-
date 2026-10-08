<?php

namespace App\Policies;

use App\Models\Cashflow;
use App\Models\User;

class CashflowPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isFullAccess();
    }

    public function update(User $user, Cashflow $cashflow): bool
    {
        return $user->isFullAccess();
    }

    public function delete(User $user, Cashflow $cashflow): bool
    {
        return $user->isFullAccess();
    }
}
