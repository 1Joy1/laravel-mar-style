<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\PhotoCreated' => [
            'App\Listeners\PhotoCreatedListener',
        ],
        'App\Events\PhotoDeleted' => [
            'App\Listeners\PhotoDeletedListener',
        ],
        'App\Events\PhotoDeleting' => [
            'App\Listeners\PhotoDeletingListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
