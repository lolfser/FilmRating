<?php
namespace Database\Seeders;
use App\Models\Grades;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder for table grades
*/
class GradesSeeder extends Seeder {

    public function run(): void {
        try {
            DB::beginTransaction();

            Grades::create(['value' => 1, 'trend' => "+"]);
            Grades::create(['value' => 1, 'trend' => ""]);
            Grades::create(['value' => 1, 'trend' => "-"]);
            Grades::create(['value' => 2, 'trend' => "+"]);
            Grades::create(['value' => 2, 'trend' => ""]);
            Grades::create(['value' => 2, 'trend' => "-"]);
            Grades::create(['value' => 3, 'trend' => "+"]);
            Grades::create(['value' => 3, 'trend' => ""]);
            Grades::create(['value' => 3, 'trend' => "-"]);
            Grades::create(['value' => 4, 'trend' => "+"]);
            Grades::create(['value' => 4, 'trend' => ""]);
            Grades::create(['value' => 4, 'trend' => "-"]);
            Grades::create(['value' => 5, 'trend' => "+"]);
            Grades::create(['value' => 5, 'trend' => ""]);
            Grades::create(['value' => 5, 'trend' => "-"]);

            DB::commit();
        } catch(\Throwable $t) {
            DB::rollback();
            echo $t;
            exit;
        }
    }
}
