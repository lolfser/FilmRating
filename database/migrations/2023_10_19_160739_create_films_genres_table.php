<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilmsGenresTable extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('films_genres', function (Blueprint $table) {
            $table->unsignedInteger('films_id');
            $table->unsignedInteger('genres_id');

            $table->index(["films_id","genres_id"]); // isSimpleIndex => films_genres_films_id_genres_id_index
        });
    }
}