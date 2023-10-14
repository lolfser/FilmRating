<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilmsKeysordsTable extends Migration {

    /**
     * Run the migrations.
     * @return void
     * 
     * @return \Illuminate\Http\Response
     */
    public function up() {
        Schema::create('films_keysords', function (Blueprint $table) {
            $table->integer('film_id')->nullable();
            $table->integer('keyword_id')->nullable();

            $table->unique(["film_id","keyword_id"]); // isUnique => Unique
        });
    }
}