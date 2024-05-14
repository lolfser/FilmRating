<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgramblocksTable extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('programblocks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('films_id');
            $table->unsignedInteger('programblockmetas_id');
        });
    }
}
