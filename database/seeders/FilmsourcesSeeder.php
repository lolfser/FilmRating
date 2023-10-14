<?php
use App\Models\Filmsources;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder for table filmsources
 * TODO: Don't forget to include `$this->call(\FilmsourcesSeeder::class);` in DatabaseSeeder.php::run() method
*/
class FilmsourcesSeeder extends Seeder {

    /**
     * 
     * @return \Illuminate\Http\Response
     */
    public function run() {
        try {
            DB::beginTransaction();

            Filmsources::create(['name' => "andere", 'name' => "andere"]);
            Filmsources::create(['name' => "click4festival", 'name' => "click4festival"]);
            Filmsources::create(['name' => "film free way", 'name' => "film free way"]);

            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
            echo $e;
            exit;
        }
    }
}