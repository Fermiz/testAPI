<?php

namespace App\Repositories;

use App\User;
use APP\Prize;

class PrizeRepository
{
    /**
     * Get all of the tasks for a given user.
     *
     * @param  User  $user
     * @return Collection
     */
    public function forUser(User $user)
    {
        return $user->prizes()
                    ->orderBy('created_at', 'asc')
                    ->get();
    }
}