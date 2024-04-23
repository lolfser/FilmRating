<?php
namespace Database\Seeders;
use App\Models\Programblocks;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder for table programmblocks
*/
class ProgramblocksSeeder extends Seeder {

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
