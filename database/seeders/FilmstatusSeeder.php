<?php
namespace Database\Seeders;
use App\Models\Filmstatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder for table filmstatus
*/
class FilmstatusSeeder extends Seeder {

    public function run(): void {
        try {
            DB::beginTransaction();

            Filmstatus::create(['name' => 'open']);
            Filmstatus::create(['name' => 'dabei']);
            Filmstatus::create(['name' => 'raus']);
            Filmstatus::create(['name' => 'nur kpj']);
            Filmstatus::create(['name' => 'vielleicht']);

            DB::commit();
        } catch(\Throwable $t) {
            DB::rollback();
            echo $t;
            exit;
        }
    }
}
