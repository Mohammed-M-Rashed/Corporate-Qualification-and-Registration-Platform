<?php

namespace App\Policies;

use App\Models\Committee;
use App\Models\User;

class CommitteePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view committees');
    }

    public function view(User $user, Committee $committee): bool
    {
        return $user->hasPermissionTo('view committees');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create committees');
    }

    public function update(User $user, Committee $committee): bool
    {
        return $user->hasPermissionTo('edit committees');
    }

    public function delete(User $user, Committee $committee): bool
    {
        return $user->hasPermissionTo('delete committees');
    }

    public function restore(User $user, Committee $committee): bool
    {
        return $user->hasPermissionTo('delete committees');
    }

    public function forceDelete(User $user, Committee $committee): bool
    {
        return $user->hasPermissionTo('delete committees');
    }
}
