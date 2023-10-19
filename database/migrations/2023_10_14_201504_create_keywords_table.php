<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKeywordsTable extends Migration {

    /**
     * Run the migrations.
     * @return void
     * 
     * @return \Illuminate\Http\Response
     */
    public function up() {
        Schema::create('keywords', function (Blueprint $table) {
            $table->unsignedInteger('id');
            $table->string('name', 100);

            $table->unique(["name"]); // isUnique => name
            $table->primary(["id"]); // isPrimary => PRIMARY
        });
    }
}