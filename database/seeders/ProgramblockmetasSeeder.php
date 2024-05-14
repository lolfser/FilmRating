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

            Programblockmetas::create(['locations_id' => 1, 'days_id' => 1, 'start' => "21:30:00", 'total_length' => 180, 'puffer_per_item' => 1]);
            Programblockmetas::create(['locations_id' => 1, 'days_id' => 1, 'start' => "18:45:00", 'total_length' => 250, 'puffer_per_item' => 2]);
            Programblockmetas::create(['locations_id' => 2, 'days_id' => 2, 'start' => "17:36:15", 'total_length' => 250, 'puffer_per_item' => 0]);
            Programblockmetas::create(['locations_id' => 2, 'days_id' => 1, 'start' => "16:25:15", 'total_length' => 250]);
            Programblockmetas::create(['locations_id' => 1, 'days_id' => 3, 'start' => "15:15:17", 'total_length' => 250, 'puffer_per_item' => 2]);
            Programblockmetas::create(['locations_id' => 2, 'days_id' => 3, 'start' => "14:04:17", 'total_length' => 250, 'puffer_per_item' => 2]);
            Programblockmetas::create(['locations_id' => 1, 'days_id' => 4, 'start' => "08:02:44", 'total_length' => 250, 'puffer_per_item' => 2]);
            Programblockmetas::create(['locations_id' => 2, 'days_id' => 4, 'start' => "07:01:56", 'total_length' => 250, 'puffer_per_item' => 2]);

            DB::commit();
        } catch(\Throwable $t) {
            DB::rollback();
            echo $t;
            exit;
        }
    }
}
