<?php

namespace App;

use App\Notifications\ActivatePasswordNotification;

class UserActivate extends User
{
    protected $table = "users";

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ActivatePasswordNotification($token));
    }

}
