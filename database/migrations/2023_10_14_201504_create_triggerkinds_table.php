<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTriggerkindsTable extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('triggerkinds', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->default('');

            $table->unique(["name"]); // isUnique => triggerkinds_name_unique
        });
    }
}