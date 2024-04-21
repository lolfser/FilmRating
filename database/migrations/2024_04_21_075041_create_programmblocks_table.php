<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgrammblocksTable extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('programmblocks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('films_id');

        });
    }
}