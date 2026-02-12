<?php

namespace App\Policies;

use App\Models\QualificationRequest;
use App\Models\User;

class QualificationRequestPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view qualification requests');
    }

    public function view(User $user, QualificationRequest $qualificationRequest): bool
    {
        return $user->hasPermissionTo('view qualification requests');
    }

    public function create(User $user): bool
    {
        return false; // Requests are created through registration
    }

    public function update(User $user, QualificationRequest $qualificationRequest): bool
    {
        return $user->hasPermissionTo('review qualification requests');
    }

    public function delete(User $user, QualificationRequest $qualificationRequest): bool
    {
        return $user->hasPermissionTo('delete companies');
    }

    public function review(User $user, QualificationRequest $qualificationRequest): bool
    {
        return $user->hasPermissionTo('review qualification requests');
    }

    public function approve(User $user, QualificationRequest $qualificationRequest): bool
    {
        return $user->hasPermissionTo('approve qualification requests');
    }

    public function reject(User $user, QualificationRequest $qualificationRequest): bool
    {
        return $user->hasPermissionTo('reject qualification requests');
    }
}
