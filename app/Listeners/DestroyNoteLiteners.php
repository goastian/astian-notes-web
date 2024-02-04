<?php

namespace App\Listeners;

use App\Events\DestroyNoteEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DestroyNoteLiteners implements ShouldQueue
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
    public function handle(DestroyNoteEvent $event): void
    {
        //
    }
}
