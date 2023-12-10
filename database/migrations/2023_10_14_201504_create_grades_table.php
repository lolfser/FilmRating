<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGradesTable extends Migration {

    /**
     * Run the migrations.
     * @return void
     * 
     * @return \Illuminate\Http\Response
     */
    public function up() {
        Schema::create('grades', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('value');
            $table->string('trend', 1)->default('');

            $table->unique(["value","trend"]); // isUnique => grades_id_value_trend_unique
        });
    }
}