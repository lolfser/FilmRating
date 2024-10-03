<?php
namespace Database\Seeders;
use App\Models\Filmmodifications;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder for table filmmodifications
*/
class FilmmodificationsSeeder extends Seeder {

    public function run(): void {
        try {
            DB::beginTransaction();

            Filmmodifications::create(['key' => 'child9', 'name' => 'bis 9']);
            Filmmodifications::create(['key' => 'child13', 'name' => 'bis 13']);
            Filmmodifications::create(['key' => 'child17', 'name' => 'bis 17']);
            Filmmodifications::create(['key' => 'queer', 'name' => '⚥']);
            Filmmodifications::create(['key' => 'super8', 'name' => 'S8']);

            DB::commit();
        } catch(\Throwable $t) {
            DB::rollback();
            echo $t;
            exit;
        }
    }
}
