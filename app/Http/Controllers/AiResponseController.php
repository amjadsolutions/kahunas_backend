<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\AiPrompt; // Import the model to log prompt-response pairs

class AiResponseController extends Controller
{
    public function getAIResponse(Request $request)
    {
        $url = 'https://api.ai21.com/studio/v1/chat/completions';
    
        // Get user input from the request
        $userMessage = $request->input('content'); // Get content from the incoming request
    
        // Make the API request to AI21
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('AI_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post($url, [
            'model' => 'jamba-1.5-large',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $userMessage, // Use the dynamic user message
                ],
            ],
            'n' => 1,
            'max_tokens' => 50,
        ]);
    
        // Handle the response
        if ($response->successful()) {
            // Extract the AI response
            $aiResponse = $response['choices'][0]['message']['content'] ?? 'No content available';
    
            // Log prompt-response pair in the database
            $qualityScore = $this->evaluateResponseQuality($aiResponse);
    
            $prompt = AiPrompt::create([
                'user_prompt' => $userMessage,
                'ai_response' => $aiResponse,
                'response_quality_score' => $qualityScore,
            ]);
    
            // Return the response as JSON
            return response()->json(['response' => $aiResponse, 'quality_score' => $qualityScore]);
        } else {
            return response()->json(['error' => 'Failed to get response from AI'], 500);
        }
    }
    
    private function evaluateResponseQuality($response)
    {
        $score = 0;
    
        // 1. Relevance Evaluation
        $relevantKeywords = ['weather', 'today', 'forecast', 'sports', 'news']; // Example keywords
        foreach ($relevantKeywords as $keyword) {
            if (stripos($response, $keyword) !== false) {
                $score += 1;  // Increase score for matching keyword
            }
        }
    
        // 2. Clarity Evaluation
        // Check if the response is not too short (assuming clear responses are typically longer)
        if (strlen($response) > 30) {
            $score += 1;
        }
    
        // Check if there are common grammar mistakes (you can integrate an external service for this too)
        if ($this->isGrammaticallyCorrect($response)) {
            $score += 1;
        }
    
        // 3. Tone Evaluation (Example: Check if response uses casual or formal language)
        if ($this->isToneAppropriate($response)) {
            $score += 1;
        }
    
        // 4. Coherence Evaluation
        if ($this->isCoherent($response)) {
            $score += 1;
        }
    
        return $score;
    }
    
    // Function to check grammatical correctness (this could integrate with a service for better results)
    private function isGrammaticallyCorrect($response)
    {
        // Basic check for sentence ending punctuation as an example (you can expand this)
        return preg_match('/[.!?]$/', trim($response)) > 0;
    }
    
    // Function to evaluate if tone is appropriate (e.g., formal, casual, professional)
    private function isToneAppropriate($response)
    {
        $formalWords = ['please', 'thank you', 'sir', 'ma’am'];
        $casualWords = ['hey', 'yo', 'what\'s up'];
    
        $isFormal = false;
        foreach ($formalWords as $word) {
            if (stripos($response, $word) !== false) {
                $isFormal = true;
                break;
            }
        }
    
        $isCasual = false;
        foreach ($casualWords as $word) {
            if (stripos($response, $word) !== false) {
                $isCasual = true;
                break;
            }
        }
        if ($isFormal) {
            return true;
        }
        return true;
    }
    
    // Function to check if the response is coherent
    private function isCoherent($response)
    {
       
        return strlen($response) > 20; // Just a basic check for non-random responses
    }
    
}

