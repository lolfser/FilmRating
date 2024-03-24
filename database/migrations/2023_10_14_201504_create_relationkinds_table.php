<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRelationkindsTable extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('relationkinds', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->default('');

            $table->unique(["name"]); // isUnique => relationkinds_name_unique
        });
    }
}