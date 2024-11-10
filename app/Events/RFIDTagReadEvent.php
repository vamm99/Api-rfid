<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RFIDTagReadEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */

    public $uid;
    public $userData;

    public function __construct($uid, $userData = null)
    {
        $this->uid = $uid;
        $this->userData = $userData;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('rfid-tag-read'),
        ];
    }

    // Información que se transmitirá al frontend
    public function broadcastWith()
    {
        return [
            'uid' => $this->uid,
            'userData' => $this->userData,
            'message' => $this->userData ? 'UID encontrado' : 'UID no registrado'
        ];
    }
}
