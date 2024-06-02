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

            Genres::create(['name' => 'Dokumentation', 'bgcolor' => '#111111', 'fontcolor' => '#EEEEEE']);
            Genres::create(['name' => 'Horror', 'bgcolor' => '#555555', 'fontcolor' => '#EEEEEE']);
            Genres::create(['name' => 'Thriller', 'bgcolor' => '#FF0000', 'fontcolor' => '#000000']);
            Genres::create(['name' => 'Komödie', 'bgcolor' => '#00FF00', 'fontcolor' => '#000000']);
            Genres::create(['name' => 'Tragödie', 'bgcolor' => '#000011', 'fontcolor' => '#FFFFFF']);

            DB::commit();
        } catch(\Throwable $t) {
            DB::rollback();
            echo $t;
            exit;
        }
    }
}
