<?php
namespace Database\Seeders;
use App\Models\Triggerkinds;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder for table triggerkinds
*/
class TriggerkindsSeeder extends Seeder {

    public function run(): void {
        try {
            DB::beginTransaction();

            Triggerkinds::create(['name' => 'Epellepsie']);
            Triggerkinds::create(['name' => 'Gewalt']);
            Triggerkinds::create(['name' => 'Pornografie']);

            DB::commit();
        } catch(\Throwable $t) {
            DB::rollback();
            echo $t;
            exit;
        }
    }
}
