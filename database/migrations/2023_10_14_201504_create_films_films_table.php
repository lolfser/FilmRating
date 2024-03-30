<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilmsFilmsTable extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('films_films', function (Blueprint $table) {
            $table->integer('films1_id');
            $table->integer('films2_id');
            $table->integer('relationkinds_id');

            $table->unique(["films1_id","films2_id","relationkinds_id"]); // isUnique => films_films_films1_id_films2_id_relationkinds_id_unique
        });
    }
}