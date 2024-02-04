<?php

namespace App\Listeners;

use App\Events\UpdateNoteEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateNoteLiteners implements ShouldQueue
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
    public function handle(UpdateNoteEvent $event): void
    {
        //
    }
}
