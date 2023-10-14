<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilmsKeyqwordsTable extends Migration {

    /**
     * Run the migrations.
     * @return void
     * 
     * @return \Illuminate\Http\Response
     */
    public function up() {
        Schema::create('films_keyqwords', function (Blueprint $table) {
            $table->integer('films_id')->nullable();
            $table->integer('keywords_id')->nullable();

            $table->unique(["films_id","keywords_id"]); // isUnique => Unique
        });
    }
}