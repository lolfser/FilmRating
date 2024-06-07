<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGenresTable extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('genres', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->string('bgcolor', 7)->default('');
            $table->string('fontcolor', 7)->default('');

            $table->unique(['name']); // isUnique => genres_name_unique
        });
    }
}
