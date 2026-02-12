<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;

class CompanyPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view companies');
    }

    public function view(User $user, Company $company): bool
    {
        return $user->hasPermissionTo('view companies');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create companies');
    }

    public function update(User $user, Company $company): bool
    {
        return $user->hasPermissionTo('edit companies');
    }

    public function delete(User $user, Company $company): bool
    {
        return $user->hasPermissionTo('delete companies');
    }

    public function restore(User $user, Company $company): bool
    {
        return $user->hasPermissionTo('delete companies');
    }

    public function forceDelete(User $user, Company $company): bool
    {
        return $user->hasPermissionTo('delete companies');
    }
}
