<?php
namespace Database\Seeders;
use App\Models\Locations;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder for table locations
*/
class LocationsSeeder extends Seeder {

    public function run(): void {
        try {
            DB::beginTransaction();
            Locations::create(['name' => 'Open-Air']);
            Locations::create(['name' => 'Zelt']);
            DB::commit();
        } catch(\Throwable $t) {
            DB::rollback();
            echo $t;
            exit;
        }
    }
}
