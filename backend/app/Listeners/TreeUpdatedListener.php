<?php

namespace App\Listeners;

use App\Events\TreeUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;


class TreeUpdatedListener
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
     * @param  TreeUpdated  $event
     * @return void
     */
    public function handle(TreeUpdated $event)
    {
        //
    }
}
