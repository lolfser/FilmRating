<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilmstatusTable extends Migration {

    public function up(): void {
        Schema::create('filmstatus', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);

        });
    }
}
