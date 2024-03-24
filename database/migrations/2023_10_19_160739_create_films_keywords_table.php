<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilmsKeywordsTable extends Migration {

    public function up(): void {
        Schema::create('films_keywords', function (Blueprint $table) {
            $table->unsignedInteger('films_id');
            $table->unsignedInteger('keywords_id');

            $table->unique(["films_id","keywords_id"]); // isUnique => films_keywords_films_id_keywords_id_unique
        });
    }
}
