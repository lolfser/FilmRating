<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilmsViewersTable extends Migration {

    /**
     * Run the migrations.
     * @return void
     * 
     * @return \Illuminate\Http\Response
     */
    public function up() {
        Schema::create('films_viewers', function (Blueprint $table) {
            $table->unsignedInteger('films_id');
            $table->unsignedInteger('viewers_id');
            $table->string('comment', 500)->default('');
            $table->unsignedInteger('grades_id');
        });
    }
}