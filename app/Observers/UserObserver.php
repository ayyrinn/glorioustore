<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Customer;

class UserObserver
{
    public function created(User $user)
    {
        // Logika untuk menangani event created
        Customer::create([
            'custname' => $user->name,
            'custemail' => $user->email,
            'custaddress' => '000',
            'custnum' => '08',
        ]);
    }

}

