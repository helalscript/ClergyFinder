<?php

namespace App\Providers\V1;

use App\Models\Booking;
use App\Models\ParkingSpace;
use App\Models\Review;
use App\Models\VehicleDetail;
use App\Policies\V1\OwnershipPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        VehicleDetail::class => OwnershipPolicy::class,
        Booking::class => OwnershipPolicy::class,
        ParkingSpace::class => OwnershipPolicy::class,
        Review::class => OwnershipPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies(); // This hooks up the policies to models
    }
}