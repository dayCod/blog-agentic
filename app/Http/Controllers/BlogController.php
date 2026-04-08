<?php

namespace App\Http\Controllers;

use App\Ai\Agents\FinanceJournalist;
use App\Jobs\GetWebContent;
use App\Models\Blog;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::query()->latest()->get();

        return Inertia::render('Blog/Index', [
            'blogs' => $blogs,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'url' => ['required', 'url'],
        ]);

        GetWebContent::dispatch(FinanceJournalist::make(), $validated['url']);

        Inertia::flash('message', 'Your request is being processed. The content will be available soon.');

        return to_route('blog.index');
    }
}
