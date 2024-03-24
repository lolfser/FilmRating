<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilmmodificationsFilmsTable extends Migration {

    public function up(): void {
        Schema::create('filmmodifications_films', function (Blueprint $table) {
            $table->unsignedInteger('filmmodifications_id');
            $table->unsignedInteger('films_id');

            $table->unique(["filmmodifications_id", "films_id"]); // isUnique => filmmodifications_films_filmmodifications_id_films_id_unique
        });
    }
}
