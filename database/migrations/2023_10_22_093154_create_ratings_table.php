<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingsTable extends Migration {

    public function up(): void {
        Schema::create('ratings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('films_id');
            $table->unsignedInteger('viewers_id');
            $table->string('comment', 500)->default('');
            $table->unsignedInteger('grades_id');
            $table->timestamps();

        });
    }
}
