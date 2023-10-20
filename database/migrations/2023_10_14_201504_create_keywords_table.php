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
            $table->increments('id');
            $table->string('name', 100);

            $table->unique(["name"]); // isUnique => keywords_name_unique
        });
    }
}