<?php
namespace Database\Seeders;
use App\Models\Programs;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder for table programs
*/
class ProgramsSeeder extends Seeder {

    public function run(): void {
        try {
            DB::beginTransaction();
            Programs::create(['name' => '48. Filmfestival']);
            DB::commit();
        } catch(\Throwable $t) {
            DB::rollback();
            echo $t;
            exit;
        }
    }
}
