<?php
namespace Database\Seeders;
use App\Models\Genres;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder for table genres
*/
class GenresSeeder extends Seeder {

    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function run() {
        try {
            DB::beginTransaction();

            Genres::create(['name' => "Dokumentation", 'name' => "Dokumentation"]);
            Genres::create(['name' => "Horror", 'name' => "Horror"]);
            Genres::create(['name' => "Thriller", 'name' => "Thriller"]);
            Genres::create(['name' => "Kömodie", 'name' => "Kömodie"]);
            Genres::create(['name' => "Tragödie", 'name' => "Tragödie"]);

            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
            echo $e;
            exit;
        }
    }
}
