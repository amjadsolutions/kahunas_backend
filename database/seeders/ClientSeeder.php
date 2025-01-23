<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Str;
use App\Models\Client;

class ClientSeeder extends Seeder
{
    public function run()
    {
        // Fetch all coach IDs from the users table
        $coachIds = User::pluck('id')->toArray();
    
        // Ensure there are users in the table
        if (empty($coachIds)) {
            $this->command->info('No coaches found in the users table. Please seed users first.');
            return;
        }
    
        // Get the current count of clients in the database
        $existingClientsCount = Client::count();
    
        // Create clients, starting from the next available client number
        $clientCount = $existingClientsCount;
    
        while ($clientCount < $existingClientsCount + 1000) {
            $email = 'client' . ($clientCount + 1) . '@example.com';
    
            // Check if the email already exists (though it shouldn't in this case)
            if (Client::where('email', $email)->exists()) {
                continue; // Skip if email already exists
            }
    
            Client::create([
                // 'coach_id' => $coachIds[array_rand($coachIds)], // Randomly pick a coach_id
                  'coach_id' => 1,
                'name' => 'Client ' . ($clientCount + 1),
                'email' => $email,
                'password' => Hash::make('password123'), 
            ]);
    
            $clientCount++; 
        }
    
        $this->command->info('Clients seeded successfully!');
    }
    
    
}
