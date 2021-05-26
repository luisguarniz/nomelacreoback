<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class noMelacreo implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $response;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data)
    {
    
        $this->response = [
            'idSessionGame'=> $data['idSessionGame'],
            'idAdmin'   => $data['idAdmin'],
            'to'           => $data['to'],
            'from'         => auth()->user(),
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()// ver si este metodo recibe parametros. si es asi podriamos enviar el id del canal a donde nos queremos unir
                                // y a su ves ese id lo concatenamos con channel-game
    {
        return new PrivateChannel("noMelacreo.{$this->response['to']}");
    }
}
