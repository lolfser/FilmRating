<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionsTable extends Migration {

    /**
     * Run the migrations.
     * @return void
     * 
     * @return \Illuminate\Http\Response
     */
    public function up() {
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id');
            $table->bigInteger('user_id')->nullable()->unsigned();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->text('payload');
            $table->integer('last_activity');

            $table->primary(["id"]); // isPrimary => PRIMARY
            $table->index(["last_activity"]); // isSimpleIndex => sessions_last_activity_index
            $table->index(["user_id"]); // isSimpleIndex => sessions_user_id_index
        });
    }
}