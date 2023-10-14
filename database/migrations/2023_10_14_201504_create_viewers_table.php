<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViewersTable extends Migration {

    /**
     * Run the migrations.
     * @return void
     * 
     * @return \Illuminate\Http\Response
     */
    public function up() {
        Schema::create('viewers', function (Blueprint $table) {
            $table->unsignedInteger('users_id');
            $table->string('initials', 5)->default('');
            $table->string('comment', 50)->default('');
        });
    }
}