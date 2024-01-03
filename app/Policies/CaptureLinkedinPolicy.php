<?php

namespace App\Policies;

use App\Models\CaptureLinkedin;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CaptureLinkedinPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CaptureLinkedin $captureLinkedin): Response
    {
        return $user->id === $captureLinkedin->owner_id
            ? Response::allow()
            : Response::deny('You do not own this capture linkedin.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CaptureLinkedin $captureLinkedin): bool
    {
        return $user->id === (int)$captureLinkedin->owner_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CaptureLinkedin $captureLinkedin): bool
    {
        return $user->id === $captureLinkedin->owner_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CaptureLinkedin $captureLinkedin): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CaptureLinkedin $captureLinkedin): bool
    {
        return false;
    }
}
