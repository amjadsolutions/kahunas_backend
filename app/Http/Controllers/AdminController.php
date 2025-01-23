<?php

namespace App\Http\Controllers;

use App\Models\AiPrompt;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function showDashboard()
    {
        // Fetch the most used prompts
        $mostUsedPrompts = AiPrompt::select('user_prompt', DB::raw('count(*) as usage_count'))
            ->groupBy('user_prompt')
            ->orderByDesc('usage_count')
            ->take(10)
            ->get();

        // Fetch the average response quality score
        $averageQualityScore = AiPrompt::avg('response_quality_score');

        return view('admin.dashboard', compact('mostUsedPrompts', 'averageQualityScore'));
    }
}
