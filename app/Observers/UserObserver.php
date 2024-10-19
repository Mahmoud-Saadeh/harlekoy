<?php

namespace App\Observers;

use App\Models\User;
use App\Models\UserUpdateQueue;
use Illuminate\Support\Arr;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        UserUpdateQueue::create([
            'email' => $user->email,
            'changes' => Arr::except($user->toArray(), ['email']),
        ]);
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        // Get the changed attributes
        $changes = $user->getChanges();

        // If changes exist, queue them for batch processing
        if (!empty($changes)) {
            UserUpdateQueue::create([
                'email' => $user->email,
                'changes' => $changes,
            ]);
        }
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
