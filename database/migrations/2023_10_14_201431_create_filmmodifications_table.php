<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilmmodificationsTable extends Migration {

    public function up(): void {
        Schema::create('filmmodifications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->default('');

            $table->unique(["name"]); // isUnique => name
        });
    }
}
