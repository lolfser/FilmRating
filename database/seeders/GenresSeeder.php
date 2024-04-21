<?php
namespace Database\Seeders;
use App\Models\Genres;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder for table genres
*/
class GenresSeeder extends Seeder {

    public function run(): void {
        try {
            DB::beginTransaction();

            Genres::create(['name' => 'Dokumentation',]);
            Genres::create(['name' => 'Horror']);
            Genres::create(['name' => 'Thriller']);
            Genres::create(['name' => 'Kömodie']);
            Genres::create(['name' => 'Tragödie']);

            DB::commit();
        } catch(\Throwable $t) {
            DB::rollback();
            echo $t;
            exit;
        }
    }
}
