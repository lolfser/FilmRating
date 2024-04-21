<?php
namespace Database\Seeders;
use App\Models\Programblockmetas;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder for table programblockmetas
*/
class ProgramblockmetasSeeder extends Seeder {

    public function run(): void {
        try {
            DB::beginTransaction();

            Programblockmetas::create(['locations_id' => 1, 'days_id' => 1, 'start' => "21:30:00", 'total_length' => 180, 'locations_id' => 1, 'days_id' => 1, 'start' => "21:30:00", 'total_length' => 180, 'locations_id' => 1, 'days_id' => 1, 'start' => "21:30:00", 'total_length' => 180, 'locations_id' => 1, 'days_id' => 1, 'start' => "21:30:00", 'total_length' => 180, 'locations_id' => 1, 'days_id' => 1, 'start' => "21:30:00", 'total_length' => 180, 'locations_id' => 1, 'days_id' => 1, 'start' => "21:30:00", 'total_length' => 180]);

            DB::commit();
        } catch(\Throwable $t) {
            DB::rollback();
            echo $t;
            exit;
        }
    }
}
