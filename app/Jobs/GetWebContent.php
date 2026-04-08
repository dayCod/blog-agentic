<?php

namespace App\Jobs;

use App\Ai\Agents\FinanceJournalist;
use App\Models\Blog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class GetWebContent implements ShouldQueue
{
    use Queueable;

    protected FinanceJournalist $agent;

    protected string $url;

    /**
     * Create a new job instance.
     */
    public function __construct(FinanceJournalist $agent, string $url)
    {
        $this->agent = $agent;
        $this->url = $url;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $prompt = "Please fetch the content from the following url: {$this->url}";

        $response = $this->agent->prompt(
            prompt: $prompt,
            timeout: 120,
        );

        $responseData = json_decode($response->text, true);

        Blog::query()->create([
            'title' => $responseData['title'],
            'description' => $responseData['description'],
            'tags' => $responseData['tags'],
            'image' => $responseData['image_url'],
            'content' => $responseData['content'],
        ]);
    }
}
