<?php
namespace Database\Seeders;
use App\Models\Programmblocks;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder for table programmblocks
*/
class ProgrammblocksSeeder extends Seeder {

    public function run(): void {
        try {
            DB::beginTransaction();


            DB::commit();
        } catch(\Throwable $t) {
            DB::rollback();
            echo $t;
            exit;
        }
    }
}
