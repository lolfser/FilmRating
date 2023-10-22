<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLanguagesTable extends Migration {

    /**
     * Run the migrations.
     * @return void
     * 
     * @return \Illuminate\Http\Response
     */
    public function up() {
        Schema::create('languages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('language', 20);
            $table->string('type', 50)->comment('like audio or subtitle');

            $table->unique(["language","type"]); // isUnique => languages_language_unique
        });
    }
}