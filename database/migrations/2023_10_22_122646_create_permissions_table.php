<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionsTable extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('viewers_id')->default(0);
            $table->unsignedInteger('permission')->default(0);

        });
    }
}