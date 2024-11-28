<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('img')->default('user.png');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('isActive')->default(false);
            $table->boolean('isPasswordChanged')->default(false);
            $table->datetime('isLoggedIn_at')->nullable(true);
            $table->datetime('isLoggedOut_at')->nullable(true);
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
};
