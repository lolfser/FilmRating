<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDaysTable extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('days', function (Blueprint $table) {
            $table->increments('id');
            $table->datetime('date');

            $table->unique(["date"]); // isUnique => date
        });
    }
}