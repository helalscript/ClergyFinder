<?php

namespace App\Providers\V1;

use App\Models\Booking;
use App\Models\LiveSessionRequest;
use App\Models\ParkingSpace;
use App\Models\PrayerRequest;
use App\Models\Review;
use App\Models\VehicleDetail;
use App\Policies\LiveSessionRequestPolicy;
use App\Policies\PrayerRequestPolicy;
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
        PrayerRequest::class => PrayerRequestPolicy::class,
        LiveSessionRequest::class => LiveSessionRequestPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies(); // This hooks up the policies to models
    }
}