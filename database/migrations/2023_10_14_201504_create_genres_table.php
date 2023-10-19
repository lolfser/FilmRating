<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGenresTable extends Migration {

    /**
     * Run the migrations.
     * @return void
     * 
     * @return \Illuminate\Http\Response
     */
    public function up() {
        Schema::create('genres', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);

            $table->unique(["name"]); // isUnique => name
        });
    }
}