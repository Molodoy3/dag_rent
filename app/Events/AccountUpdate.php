<?php

namespace App\Events;

use App\Models\Account;
use App\Models\Statistic;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use PhpParser\Node\Expr\Array_;
use function Pest\Laravel\put;

class AccountUpdate implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        //public $statistics,
    )
    {
    }

    public function broadcastOn(): Channel
    {
        return new Channel('account-update');
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    /*public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }*/
}
