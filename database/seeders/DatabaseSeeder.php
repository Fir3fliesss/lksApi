<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Demo User',
            'username' => 'demouser',
            'email' => 'demo@example.com',
            'address' => 'Jl. Sudirman No. 1, Jakarta Pusat, DKI Jakarta 10220',
        ]);

        $this->call([
            ProductSeeder::class,
        ]);
    }
}
