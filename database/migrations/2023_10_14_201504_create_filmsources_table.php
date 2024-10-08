<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilmsourcesTable extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('filmsources', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);

            $table->unique(["name"]); // isUnique => name
        });
    }
}