<?php

namespace App\Listeners;

use App\Events\PhotoDeleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Support\Facades\Storage;

class PhotoDeletedListener
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
     * @param  PhotoDeleted  $event
     * @return void
     */
    public function handle(PhotoDeleted $event)
    {
        Storage::disk('public')->delete($event->photo->big_photo_path);
        Storage::disk('public')->delete($event->photo->midi_photo_path);
        Storage::disk('public')->delete($event->photo->mini_photo_path);
        Storage::disk('public')->delete($event->photo->thumb_photo_path);
    }
}
