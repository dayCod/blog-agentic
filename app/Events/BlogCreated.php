<?php

namespace App\Events;

use App\Models\Blog;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class BlogCreated implements ShouldBroadcast
{
    use SerializesModels, Dispatchable, InteractsWithSockets;

    public function __construct(public Blog $blog, public string $jobId) {}

    public function broadcastOn(): array
    {
        return [new Channel('job-progress.' . $this->jobId)];
    }

    public function broadcastAs(): string
    {
        return 'BlogCreated';
    }
}
