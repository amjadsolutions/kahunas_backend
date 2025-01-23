<?php
namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class SessionController extends Controller
{



    public function index(Request $request)
    {

        $coachId = Auth::id();
        $pageNum = $request->query('pageNum', 1);

        // Validate the pageNum to ensure it's a positive integer
        if (!is_numeric($pageNum) || $pageNum <= 0) {
            return response()->json(['error' => 'Invalid pageNum. It must be a positive integer.'], 400);
        }

 
     

        // Define the number of items per page
        $records = $pageNum * 50;

        // Fetch clients for the authenticated coach with pagination
        $sessions = Session::where('coach_id', Auth::id())->paginate($records);

        $customizedsessions = [
            'current_page' => $sessions->currentPage(),
            'total' => $sessions->total(),
            'data' => $sessions->items(),
        ];

   
        return response()->json(['sessions' => $customizedsessions], 200);
    }

// Create a session for a client
    public function store(Request $request)
    {
        // Validate incoming data, including the new 'session_time' field
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'session_date' => 'required|date',
            'session_time' => 'required|date_format:H:i', // Validate time format (e.g., 10:00)
            'duration' => 'required|integer|min:1', // Validate duration
        ]);

        // Ensure the coach is creating the session for the correct client
        $client = Client::findOrFail($validated['client_id']);
        if ($client->coach_id != Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Check if a session already exists for the same coach, client, session date, and session time
        $existingSession = Session::where('coach_id', Auth::id())
            ->where('client_id', $validated['client_id'])
            ->where('session_date', $validated['session_date'])
            ->where('session_time', $validated['session_time']) // Check the session time too
            ->exists();

        if ($existingSession) {
            return response()->json(['error' => 'Session already exists for the same date and time'], 400);
        }

        // Create the session if no duplicates found, including the 'session_time' and 'duration'
        $session = Session::create([
            'coach_id' => Auth::id(),
            'client_id' => $validated['client_id'],
            'session_date' => $validated['session_date'],
            'session_time' => $validated['session_time'], // Save session time
            'duration' => $validated['duration'], // Save duration
        ]);

        return response()->json(['session' => $session], 201);
    }

    public function update(Request $request, $sessionId)
    {
        // Find the session by ID
        $session = Session::where('coach_id', Auth::id())->find($sessionId);

        if (!$session) {
            return response()->json(['error' => 'Session not found or unauthorized'], 404);
        }

        // Check if the client associated with this session belongs to the authenticated coach
        $client = $session->client;
        if ($client->coach_id != Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Check if a session already exists for the same coach and client at the new session date and time
        $existingSession = Session::where('coach_id', Auth::id())
            ->where('client_id', $client->id)
            ->where('session_date', $request->session_date)
            ->where('session_time', $request->session_time) // Check the session time too
            ->exists();

        if ($existingSession) {
            return response()->json(['error' => 'Session already exists for the same date and time'], 400);
        }

        // Conditionally update the session fields
        if ($request->has('session_date')) {
            $session->session_date = $request->session_date;
        }

        if ($request->has('session_time')) {
            $session->session_time = $request->session_time;
        }

        if ($request->has('duration')) {
            $session->duration = $request->duration;
        }

        // Save the session
        $session->save();

        return response()->json(['session' => $session], 200);
    }

    // Delete a session
    public function destroy($sessionId)
    {

      
        // Find the session by ID
        $session = Session::where('coach_id', Auth::id())->find($sessionId);

        if (!$session) {
            return response()->json(['error' => 'Session not found or unauthorized'], 404);
        }

        // Check if the client associated with this session belongs to the authenticated coach
        $client = $session->client;
        if ($client->coach_id != Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Delete the session
        $session->delete();
        return response()->json(['message' => 'Session deleted successfully'], 200);
    }

    public function getUncompletedSessions(Request $request)
    {
        $pageNum = $request->query('pageNum', 1);

        $user = Auth::user();
        $userId = $user->id;

        // Validate the pageNum to ensure it's a positive integer
        if (!is_numeric($pageNum) || $pageNum <= 0) {
            return response()->json(['error' => 'Invalid pageNum. It must be a positive integer.'], 400);
        }

  

        $records = $pageNum * 50;
        // If the user is a client, fetch sessions based on the client_id
        if ($user instanceof Client) {
            $sessions = Session::where('client_id', $userId)
                ->where('status', '!=', 'completed') // Exclude completed sessions
                ->paginate($records);

            $customizedsessions = [
                'current_page' => $sessions->currentPage(),
                'total' => $sessions->total(),
                'data' => $sessions->items(),
            ];

       
            return response()->json(['sessions' => $customizedsessions], 200);

            return response()->json(['sessions' => $sessions], 200);
        }
        return response()->json(['message' => 'Unauthorized'], 401);

   

    }

    // Mark a session as completed for the authenticated client
    public function markAsCompleted($sessionId)
    {

        // Get the authenticated client
        $client = Auth::user(); // Ensure this is the authenticated client

        // Ensure the user is a client and not a coach
        if (!($client instanceof Client)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Find the session by its ID
        $session = Session::where('id', $sessionId)
            ->where('client_id', $client->id) // Ensure the session belongs to this client
            ->first();

        if (!$session) {
            return response()->json(['message' => 'Session not found or unauthorized'], 404);
        }

        // Update the session status to 'completed'
        $session->status = 'completed';
        $session->save();

        // Return the updated session details
        return response()->json(['session' => $session, 'message' => 'Session marked as completed.'], 200);
    }

}
