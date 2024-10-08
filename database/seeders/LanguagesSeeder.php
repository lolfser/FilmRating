<?php
namespace Database\Seeders;
use App\Models\Languages;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder for table languages
*/
class LanguagesSeeder extends Seeder {

    public function run(): void {
        try {
            DB::beginTransaction();

            Languages::create(['language' => 'de', 'type' => 'audio']);
            Languages::create(['language' => 'de', 'type' => 'subtitle']);
            Languages::create(['language' => 'en', 'type' => 'audio']);
            Languages::create(['language' => 'en', 'type' => 'subtitle']);

            Languages::create(['language' => 'ot', 'type' => 'audio']);
            Languages::create(['language' => 'stumm', 'type' => 'audio']);
            Languages::create(['language' => 'ohne', 'type' => 'audio']);

            DB::commit();
        } catch(\Throwable $t) {
            DB::rollback();
            echo $t;
            exit;
        }
    }
}
