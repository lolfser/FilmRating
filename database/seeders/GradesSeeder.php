<?php
namespace Database\Seeders;
use App\Models\Grades;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder for table grades
 * TODO: Don't forget to include `$this->call(\GradesSeeder::class);` in DatabaseSeeder.php::run() method
*/
class GradesSeeder extends Seeder {

    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function run() {
        try {
            DB::beginTransaction();

            Grades::create(['value' => 1, 'trend' => "+", 'value' => 1, 'trend' => "+", 'value' => 1, 'trend' => "+"]);
            Grades::create(['value' => 1, 'trend' => "", 'value' => 1, 'trend' => "", 'value' => 1, 'trend' => ""]);
            Grades::create(['value' => 1, 'trend' => "-", 'value' => 1, 'trend' => "-", 'value' => 1, 'trend' => "-"]);
            Grades::create(['value' => 2, 'trend' => "+", 'value' => 2, 'trend' => "+", 'value' => 2, 'trend' => "+"]);
            Grades::create(['value' => 2, 'trend' => "", 'value' => 2, 'trend' => "", 'value' => 2, 'trend' => ""]);
            Grades::create(['value' => 2, 'trend' => "-", 'value' => 2, 'trend' => "-", 'value' => 2, 'trend' => "-"]);
            Grades::create(['value' => 3, 'trend' => "+", 'value' => 3, 'trend' => "+", 'value' => 3, 'trend' => "+"]);
            Grades::create(['value' => 3, 'trend' => "", 'value' => 3, 'trend' => "", 'value' => 3, 'trend' => ""]);
            Grades::create(['value' => 3, 'trend' => "-", 'value' => 3, 'trend' => "-", 'value' => 3, 'trend' => "-"]);
            Grades::create(['value' => 4, 'trend' => "+", 'value' => 4, 'trend' => "+", 'value' => 4, 'trend' => "+"]);
            Grades::create(['value' => 4, 'trend' => "", 'value' => 4, 'trend' => "", 'value' => 4, 'trend' => ""]);
            Grades::create(['value' => 5, 'trend' => "-", 'value' => 5, 'trend' => "-", 'value' => 5, 'trend' => "-"]);
            Grades::create(['value' => 5, 'trend' => "+", 'value' => 5, 'trend' => "+", 'value' => 5, 'trend' => "+"]);
            Grades::create(['value' => 5, 'trend' => "", 'value' => 5, 'trend' => "", 'value' => 5, 'trend' => ""]);
            Grades::create(['value' => 5, 'trend' => "-", 'value' => 5, 'trend' => "-", 'value' => 5, 'trend' => "-"]);

            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
            echo $e;
            exit;
        }
    }
}
