<?php

namespace App\Policies\V1;

use App\Models\User;

class OwnershipPolicy
{
    public function update(User $user, $model)
    {
        return $user->id === $model->user_id;
    }

    public function delete(User $user, $model)
    {
        return $user->id === $model->user_id;
    }
}
