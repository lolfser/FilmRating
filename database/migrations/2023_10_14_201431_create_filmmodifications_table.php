<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilmmodificationsTable extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('filmmodifications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key', 50)->default('');
            $table->string('name', 50)->default('');

            $table->unique(['key']); // isUnique => name
        });
    }
}
