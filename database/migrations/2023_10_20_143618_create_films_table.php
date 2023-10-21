<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilmsTable extends Migration {

    /**
     * Run the migrations.
     * @return void
     * 
     * @return \Illuminate\Http\Response
     */
    public function up() {
        Schema::create('films', function (Blueprint $table) {
            $table->increments('id');
            $table->string('film_identifier', 50)->default('')->comment('import identifier');
            $table->string('name', 250);
            $table->string('description', 1000)->default('');
            $table->unsignedInteger('filmsources_id');
            $table->unsignedInteger('year');
            $table->integer('duration')->nullable()->comment('in seconds');
            $table->unsignedInteger('filmstatus_id')->nullable();
            $table->timestamps();

        });
    }
}