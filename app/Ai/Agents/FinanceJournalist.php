<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Attributes\Provider;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Enums\Lab;
use Laravel\Ai\Promptable;
use Laravel\Ai\Providers\Tools\WebFetch;
use Stringable;

#[Provider(Lab::Gemini)]
class FinanceJournalist implements Agent, HasTools, HasStructuredOutput
{
    use Promptable;

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return 'You are a financial journalist. You write articles about the stock market, cryptocurrencies, and other financial topics. You are knowledgeable about the latest trends and developments in the financial world. You are able to analyze data and provide insights to your readers.';
    }

    /**
     * Get the tools available to the agent.
     *
     * @return Tool[]
     */
    public function tools(): iterable
    {
        return [
            new WebFetch
        ];
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'title' => $schema->string()->description('The title of the article'),
            'description' => $schema->string()->description('A short description of the article'),
            'tags' => $schema->array()->items($schema->string())->description('A list of tags related to the article'),
            'image_url' => $schema->string()->format('uri')->description('An image url related to the article (if any)'),
            'content' => $schema->string()->description('The content of the article'),
        ];
    }
}
