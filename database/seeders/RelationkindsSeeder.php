<?php
namespace Database\Seeders;
use App\Models\Relationkinds;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder for table relationkinds
*/
class RelationkindsSeeder extends Seeder {

    public function run(): void {
        try {
            DB::beginTransaction();

            Relationkinds::create(['name' => "duplicate"]);
            Relationkinds::create(['name' => "similar"]);

            DB::commit();
        } catch(\Throwable $t) {
            DB::rollback();
            echo $t;
            exit;
        }
    }
}
