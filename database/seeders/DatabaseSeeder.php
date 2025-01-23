<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run the UserSeeder first to ensure users are available
        $this->call(UserSeeder::class);

        // Run the ClientSeeder after users are seeded
        $this->call(ClientSeeder::class);

        // Run the SessionSeeder after users are seeded
        $this->call(SessionSeeder::class);
    }
}
