<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OpenAIService
{
    protected $apiUrl = 'https://api.openai.com/v1/chat/completions';
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.openai.key');
    }

    public function generateResponse(string $prompt)
    {
        $response = Http::withToken($this->apiKey)
            ->post($this->apiUrl, [
                'model' => 'gpt-3.5-turbo', // Use a valid model
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a helpful assistant.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'max_tokens' => 50,
                'temperature' => 0.5,
            ]);

        if ($response->successful()) {
            return $response->json('choices.0.message.content');
        }

        return $response->json(); // Return error message if API call fails
    }
}
