<?php

namespace App\Policies;

use App\User;
use App\Prize;
use Illuminate\Auth\Access\HandlesAuthorization;

class PrizePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the given user can delete the given prize.
     *
     * @param  User  $user
     * @param  Prize  $prize
     * @return bool
     */
    public function destroy(User $user, Prize $prize)
    {
        return $user->id === $prize->user_id;
    }
}
