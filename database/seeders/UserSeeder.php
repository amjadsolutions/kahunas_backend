<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Get the number of existing coaches (users)
        $existingCoachCount = User::count();
    
        // Determine the starting number for new coach users
        $startNumber = $existingCoachCount + 1;
    
        // Loop to create new coach users starting from the next available number
        foreach (range($startNumber, $startNumber + 5) as $index) {
            User::create([
                'name' => 'Coach ' . $index,  
                'email' => 'coach' . $index . '@example.com', 
                'password' => Hash::make('password123'), 
            ]);
        }
    
        $this->command->info('Coaches seeded successfully!');
    }
    
}

