<?php

namespace App\Http\Controllers;

use App\Ai\Agents\FinanceJournalist;
use App\Jobs\GetWebContent;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $blogs = Blog::query()->latest()->get();

        return Inertia::render('Blog/Index', [
            'blogs' => $blogs,
            'jobId' => session('jobId'),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'url' => ['required', 'url'],
        ]);

        $jobId = (string) Str::uuid();

        GetWebContent::dispatch(FinanceJournalist::make(), $validated['url'], $jobId);

        return back()->with('jobId', $jobId);
    }
}
