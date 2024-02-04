<?php

namespace App\Listeners;

use App\Events\StoreNoteEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StoreNoteLiteners implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(StoreNoteEvent $event): void
    {
        //
    }
}
