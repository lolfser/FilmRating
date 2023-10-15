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
            $table->string('name', 250);
            $table->string('description', 1000)->default('');
            $table->unsignedInteger('sources_id');
            $table->unsignedInteger('film_nr');
            $table->unsignedInteger('year');
            $table->integer('duration')->nullable()->comment('in seconds');
            $table->string('audio_lang', 10)->nullable();
            $table->string('subtitle_lang', 10)->nullable();
            $table->string('filmstatus_id', 250);

        });
    }
}
