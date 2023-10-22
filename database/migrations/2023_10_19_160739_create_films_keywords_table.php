<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilmsKeywordsTable extends Migration {

    /**
     * Run the migrations.
     * @return void
     *
     * @return \Illuminate\Http\Response
     */
    public function up() {
        Schema::create('films_keywords', function (Blueprint $table) {
            $table->unsignedInteger('film_id');
            $table->unsignedInteger('keyword_id');

            $table->unique(["film_id","keyword_id"]); // isUnique => films_keywords_film_id_keyword_id_unique
        });
    }
}
