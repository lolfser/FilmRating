<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilmsGenreTable extends Migration {

    /**
     * Run the migrations.
     * @return void
     * 
     * @return \Illuminate\Http\Response
     */
    public function up() {
        Schema::create('films_genre', function (Blueprint $table) {
            $table->unsignedInteger('films_id');
            $table->unsignedInteger('genres_id');

            $table->index(["films_id","genres_id"]); // isSimpleIndex => Uniqe
        });
    }
}