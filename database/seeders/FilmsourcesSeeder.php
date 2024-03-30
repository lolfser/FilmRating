<?php
namespace Database\Seeders;
use App\Models\Filmsources;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder for table filmsources
*/
class FilmsourcesSeeder extends Seeder {

    public function run(): void {
        try {
            DB::beginTransaction();

            Filmsources::create(['name' => "andere"]);
            Filmsources::create(['name' => "click4festival"]);
            Filmsources::create(['name' => "film free way"]);

            DB::commit();
        } catch(\Throwable $t) {
            DB::rollback();
            echo $t;
            exit;
        }
    }
}
