<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilmsLanguagesTable extends Migration {

    /**
     * Run the migrations.
     * @return void
     * 
     * @return \Illuminate\Http\Response
     */
    public function up() {
        Schema::create('films_languages', function (Blueprint $table) {
            $table->unsignedInteger('films_id');
            $table->unsignedInteger('languages_id');

            $table->unique(["films_id","languages_id"]); // isUnique => film_language_films_id_languages_id_unique
        });
    }
}