<?php

namespace App\Events;

use App\Models\Tree;

use Illuminate\Broadcasting\Broadcasters\PusherBroadcaster;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TreeUpdated extends Event implements ShouldBroadcast
{

    /**
     * Information about the tree status update.
     *
     * @var string
     */
    public $update;

    /**
     * Model we're working with
     *
     * @var Tree $tree
     */
    private $tree;

    /**
     * Create a new event instance.
     *
     * @param  Tree  $tree
     */
    public function __construct(Tree $tree)
    {
        //set variables
        $this->tree = $tree;
        $output = is_array($this->tree->data)?json_encode($this->tree->data):$this->tree->data;
        $this->update = $output;
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'factories\updated';
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return Channel
     *
     */
    public function broadcastOn()
    {
        return new Channel('factories.'.$this->tree->key);
    }

}
