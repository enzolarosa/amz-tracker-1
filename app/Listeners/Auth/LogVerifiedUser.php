<?php

namespace App\Listeners\Auth;

use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class LogVerifiedUser
{
    public function handle(Verified $event)
    {
        if ($event->user instanceof MustVerifyEmail && !$event->user->hasVerifiedEmail()) {
            $event->user->markEmailAsVerified();
        }
    }
}
