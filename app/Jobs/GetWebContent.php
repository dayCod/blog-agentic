<?php

namespace App\Jobs;

use App\Ai\Agents\FinanceJournalist;
use App\Events\BlogCreated;
use App\Events\JobProgress;
use App\Models\Blog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GetWebContent implements ShouldQueue
{
    use Queueable;

    protected FinanceJournalist $agent;

    protected string $url;

    public string $jobId;

    /**
     * Create a new job instance.
     */
    public function __construct(FinanceJournalist $agent, string $url, string $jobId)
    {
        $this->agent = $agent;
        $this->url = $url;
        $this->jobId = $jobId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        broadcast(new JobProgress(progress: 10, jobId: $this->jobId, status: 'Menghubungi AI Agent...'));
        $prompt = "Please fetch the content from the following url: {$this->url}";

        $response = $this->agent->prompt(
            prompt: $prompt,
            timeout: 120,
        );

        broadcast(new JobProgress(progress: 50, jobId: $this->jobId, status: 'Memproses data dari Agent...'));

        $responseData = json_decode($response->text, true);

        $blog = Blog::query()->create([
            'title' => $responseData['title'],
            'description' => $responseData['description'],
            'tags' => $responseData['tags'],
            'image' => $responseData['image_url'],
            'content' => $responseData['content'],
        ]);

        broadcast(new JobProgress(progress: 100, jobId: $this->jobId, status: 'Selesai! Blog berhasil dibuat...'));

        broadcast(new BlogCreated($blog, $this->jobId));
    }
}
