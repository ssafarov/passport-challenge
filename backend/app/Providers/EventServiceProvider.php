<?php

namespace App\Providers;

use App\Events\TreeUpdated;
use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\TreeUpdated' => [
            'App\Listeners\TreeUpdatedListener',
        ],
    ];
}
