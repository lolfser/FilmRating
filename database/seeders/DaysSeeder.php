<?php
namespace Database\Seeders;
use App\Models\Days;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder for table days
*/
class DaysSeeder extends Seeder {

    public function run(): void {
        try {
            DB::beginTransaction();
            Days::create(['date' => '2024-08-15']);
            Days::create(['date' => '2024-08-16']);
            DB::commit();
        } catch(\Throwable $t) {
            DB::rollback();
            echo $t;
            exit;
        }
    }
}
