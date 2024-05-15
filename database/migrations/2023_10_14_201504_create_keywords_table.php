<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKeywordsTable extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('keywords', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100); // coalation: utf8mb4_bin

            $table->unique(["name"]); // isUnique => keywords_name_unique
        });
    }
}
