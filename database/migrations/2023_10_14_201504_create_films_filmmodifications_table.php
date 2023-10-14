<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilmsFilmmodificationsTable extends Migration {

    /**
     * Run the migrations.
     * @return void
     * 
     * @return \Illuminate\Http\Response
     */
    public function up() {
        Schema::create('films_filmmodifications', function (Blueprint $table) {
            $table->unsignedInteger('films_id');
            $table->unsignedInteger('filmmodifications_id');

            $table->unique(["films_id","filmmodifications_id"]); // isUnique => Unique
        });
    }
}