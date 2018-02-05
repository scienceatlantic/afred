<?php

namespace App\Policies;

use App\User;
use App\Listing;
use Illuminate\Auth\Access\HandlesAuthorization;

class ListingPolicy
{
    use HandlesAuthorization;

    public function show(User $user, Listing $listing)
    {
        return true;
    }
}
