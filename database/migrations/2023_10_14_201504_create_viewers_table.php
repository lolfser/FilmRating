<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViewersTable extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('viewers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('users_id');
            $table->string('initials', 5)->default('');
            $table->string('comment', 50)->default('');

            $table->unique(["initials"]); // isUnique => initials
        });
    }
}