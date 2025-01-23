<?php

namespace App\Http\Controllers;

use App\Models\Session;

class CoachAnalyticsController extends Controller
{

    public function index()
    {

        $data = [];
        $coachId = auth()->id();

        // Get the total number of sessions conducted by the coach
        $totalSessions = Session::where('coach_id', $coachId)->count();

        // Get the total number of completed sessions for the coach
        $completedSessions = Session::where('coach_id', auth()->id())
            ->where('status', 'completed')
            ->count();

        // Calculate the percentage of completed sessions
        $completionPercentage = $totalSessions > 0 ? ($completedSessions / $totalSessions) * 100 : 0;
        $data['total_sessions'] = $totalSessions;
        $data['completed_sessions_percentage'] = round($completionPercentage, 2) . '%';

        // Store the customized clients in Redis as a hash

        return response()->json(['analytics' => $data], 200);
    }

}
