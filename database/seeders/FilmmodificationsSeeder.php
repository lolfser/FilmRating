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

            Filmmodifications::create(['name' => 'child9']);
            Filmmodifications::create(['name' => 'child13']);
            Filmmodifications::create(['name' => 'child17']);
            Filmmodifications::create(['name' => 'queer']);

            DB::commit();
        } catch(\Throwable $t) {
            DB::rollback();
            echo $t;
            exit;
        }
    }
}
