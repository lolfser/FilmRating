<?php
use App\Models\Triggerkinds;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder for table triggerkinds
 * TODO: Don't forget to include `$this->call(\TriggerkindsSeeder::class);` in DatabaseSeeder.php::run() method
*/
class TriggerkindsSeeder extends Seeder {

    /**
     * 
     * @return \Illuminate\Http\Response
     */
    public function run() {
        try {
            DB::beginTransaction();

            Triggerkinds::create(['name' => "Epellepsie", 'name' => "Epellepsie"]);
            Triggerkinds::create(['name' => "Gewalt", 'name' => "Gewalt"]);
            Triggerkinds::create(['name' => "Pornografie", 'name' => "Pornografie"]);

            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
            echo $e;
            exit;
        }
    }
}