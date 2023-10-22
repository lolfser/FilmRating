<?php
namespace Database\Seeders;
use App\Models\Relationkinds;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder for table relationkinds
*/
class RelationkindsSeeder extends Seeder {

    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function run() {
        try {
            DB::beginTransaction();

            Relationkinds::create(['name' => "duplicate", 'name' => "duplicate"]);
            Relationkinds::create(['name' => "similar", 'name' => "similar"]);

            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
            echo $e;
            exit;
        }
    }
}
