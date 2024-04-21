<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Programblockmetas;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\Users::factory(10)->create();
        $this->call([
            FilmmodificationsSeeder::class,
            FilmsourcesSeeder::class,
            FilmstatusSeeder::class,
            GenresSeeder::class,
            GradesSeeder::class,
            LanguagesSeeder::class,
            RelationkindsSeeder::class,
            TriggerkindsSeeder::class,
            DaysSeeder::class,
            ProgramblockmetasSeeder::class,
            ProgrammblocksSeeder::class,
            ProgramsSeeder::class,
            LocationsSeeder::class,
        ]);

        // \App\Models\Users::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
