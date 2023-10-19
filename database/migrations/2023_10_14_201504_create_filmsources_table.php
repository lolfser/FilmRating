<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilmsourcesTable extends Migration {

    /**
     * Run the migrations.
     * @return void
     * 
     * @return \Illuminate\Http\Response
     */
    public function up() {
        Schema::create('filmsources', function (Blueprint $table) {
            $table->unsignedInteger('id');
            $table->string('name', 50)->nullable();

            $table->primary(["id"]); // isPrimary => PRIMARY
        });
    }
}