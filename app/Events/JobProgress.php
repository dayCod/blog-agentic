<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class JobProgress implements ShouldBroadcast
{
    use SerializesModels, Dispatchable, InteractsWithSockets;

    public function __construct(public int $progress, public string $jobId, public string $status = '') {}

    public function broadcastOn(): array
    {
        return [new Channel('job-progress.' . $this->jobId)];
    }

    public function broadcastAs(): string
    {
        return 'JobUpdated';
    }
}
