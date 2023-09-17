<?php

namespace App\Policies;

use App\Models\Repayment;
use App\Models\User;

class RepaymentPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Repayment $repayment): bool
    {
        if ($user->is_admin) {
            return true;
        }

        return $user->id === $repayment->user_id;
    }
}
