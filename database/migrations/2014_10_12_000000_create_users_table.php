<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('password');
            $table->string('email')->unique();
            $table->string('unique_key')->unique();
            $table->string('api_token')->unique();
            $table->timestamp('accepted_date')->nullable();
            $table->timestamp('declined_date')->nullable();
            $table->timestamp('approved_date')->nullable();
            $table->timestamp('denied_date')->nullable();
            $table->boolean('is_admin')->default(false)->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
