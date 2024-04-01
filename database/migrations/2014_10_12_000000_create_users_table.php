<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('name');
            $table->string('email');
            $table->datetime('email_verified_at')->nullable();
            $table->string('password');
            $table->text('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->datetime('two_factor_confirmed_at')->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->bigInteger('current_team_id')->nullable()->unsigned();
            $table->string('profile_photo_path', 2048)->nullable();
            $table->timestamps();

            $table->unique(["email"]); // isUnique => users_email_unique
        });
    }
}