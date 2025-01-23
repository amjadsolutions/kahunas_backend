<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Client;
use App\Models\Session;
use Carbon\Carbon;

class SessionSeeder extends Seeder
{
    public function run()
{
    // Fetch all clients and their coaches
    $clients = Client::all();

    // Ensure clients are available
    if ($clients->isEmpty()) {
        $this->command->info('No clients found. Please seed clients first.');
        return;
    }

    // Iterate through each client and assign 20 sessions
    foreach ($clients as $client) {
        // Ensure the client has a coach
        if ($client->coach) {
            // Generate 20 sessions for each client
            for ($i = 0; $i < 20; $i++) {
                // Generate random session details
                $sessionDate = Carbon::now()->addDays(rand(1, 30));  // Random future date within the next 30 days
                $sessionTime = Carbon::createFromFormat('H:i', rand(9, 17) . ':00');  // Random time between 9:00 AM and 5:00 PM
                $sessionDuration = rand(30, 90);  // Random duration between 30 and 90 minutes

                // Check if a session already exists for this client and coach on the same day at the same time
                $existingSession = Session::where('coach_id', $client->coach_id)
                    ->where('client_id', $client->id)
                    ->whereDate('session_date', $sessionDate->format('Y-m-d'))
                    ->where('session_time', $sessionTime->format('H:i'))
                    ->exists();

                // If no existing session, create a new session
                if (!$existingSession) {
                    Session::create([
                        'coach_id' => $client->coach_id,  // Use the client's coach_id
                        'client_id' => $client->id,  // Assign sessions to clients
                        'session_date' => $sessionDate->format('Y-m-d H:i:s'),  // Store session date and time
                        'session_time' => $sessionTime->format('H:i'),  // Specific session time (e.g., 10:00 AM)
                        'duration' => $sessionDuration,  // Duration in minutes
                        'status' => 'pending',  // Default status
                    ]);
                }
            }
        }
    }

    $this->command->info('20 sessions seeded successfully for each client!');
}

}
