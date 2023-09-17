<?php

namespace App\Policies;

use App\Models\Loan;
use App\Models\User;

class LoanPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Loan $loan): bool
    {
        if ($user->is_admin) {
            return true;
        }

        return $user->id === $loan->user_id;
    }
}
