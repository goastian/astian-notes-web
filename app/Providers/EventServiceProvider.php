<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [

        \App\Events\StoreNoteEvent::class => [
            \App\Listeners\StoreNoteLiteners::class,
        ],

        \App\Events\UpdateNoteEvent::class => [
            \App\Listeners\UpdateNoteLiteners::class,
        ],

        \App\Events\DestroyNoteEvent::class => [
            \App\Listeners\DestroyNoteLiteners::class,
        ],

        \App\Events\StoreTagEvent::class => [
            \App\Listeners\StoreTagListener::class,
        ],
        \App\Events\UpdateTagEvent::class => [
            \App\Listeners\UpdateTagListener::class,
        ],
        \App\Events\DestroyTagEvent::class => [
            \App\Listeners\DestroyTagListener::class,
        ],

    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
