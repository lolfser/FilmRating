<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgramblockmetasTable extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('programblockmetas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('locations_id')->nullable();
            $table->integer('days_id')->nullable();
            $table->time('start')->nullable();
            $table->float('total_length', 10, 0)->nullable();
            $table->float('puffer_per_item', 10, 0)->nullable();

            $table->unique(["locations_id","days_id","start"]); // isUnique => lcoations_days_start
        });
    }
}