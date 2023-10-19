<?php
namespace Database\Seeders;
use App\Models\Filmstatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder for table filmstatus
 * TODO: Don't forget to include `$this->call(\FilmstatusSeeder::class);` in DatabaseSeeder.php::run() method
*/
class FilmstatusSeeder extends Seeder {

    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function run() {
        try {
            DB::beginTransaction();

            Filmstatus::create(['name' => "open", 'name' => "open"]);
            Filmstatus::create(['name' => "dabei", 'name' => "dabei"]);
            Filmstatus::create(['name' => "raus", 'name' => "raus"]);
            Filmstatus::create(['name' => "nur kpj", 'name' => "nur kpj"]);
            Filmstatus::create(['name' => "vielleicht", 'name' => "vielleicht"]);

            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
            echo $e;
            exit;
        }
    }
}
