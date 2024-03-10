<?php
namespace Database\Seeders;
use App\Models\Filmmodifications;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder for table filmmodifications
*/
class FilmmodificationsSeeder extends Seeder {

    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function run() {
        try {
            DB::beginTransaction();

            Filmmodifications::create(['name' => "child9", 'name' => "child9"]);
            Filmmodifications::create(['name' => "child13", 'name' => "child13"]);
            Filmmodifications::create(['name' => "child17", 'name' => "child17"]);
            Filmmodifications::create(['name' => "queer", 'name' => "queer"]);

            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
            echo $e;
            exit;
        }
    }
}
