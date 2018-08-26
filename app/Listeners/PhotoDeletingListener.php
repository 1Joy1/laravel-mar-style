<?php

namespace App\Listeners;

use App\Events\PhotoDeleting;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PhotoDeletingListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  PhotoDeleting  $event
     * @return void
     */
    public function handle(PhotoDeleting $event)
    {
        $event->photo->groups()->detach();
    }
}
