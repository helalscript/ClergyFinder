<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Clergy;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Clergy',
            'email' => 'clergy@clergy.com',
            'password' => Hash::make('12345678'),
            'role' => 'clergy',
        ]);
        User::factory()->create([
            'name' => 'Seeker',
            'email' => 'seeker@seeker.com',
            'password' => Hash::make('12345678'),
            'role' => 'seeker',
        ]);

        Clergy::create([
            'user_id' => 2,
            'religion' => 'Christianity',
            'preferred_language' => 'English',
            'address' => '123 Main St, Anytown, USA',
            'city' => 'Anytown',
            'state' => 'CA',
            'zip_code' => '12345',
            'country' => 'USA',
            'latitude' => 37.7749,
            'longitude' => -122.4194,
            'bio' => 'I am a clergy',
            'status' => 'active',
        ]);

        $this->call(SystemSettingSeeder::class);
        $this->call(CMSSeeder::class);
        $this->call(DynamicPageSeeder::class);
        $this->call(QuestionSeeder::class);
        $this->call(FaqSeeder::class);
    }
}
