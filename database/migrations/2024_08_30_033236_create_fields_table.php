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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('approval_level');
            $table->string('folder_name');
            $table->timestamps();
        });

        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('img');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->integer('qty');
            $table->timestamps();
        });
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
            $table->string('municipality')->nullable(false);
            $table->decimal('kilometers', 8, 2)->nullable(false);
            $table->timestamps();
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('rentee_name')->nullable(false);
            $table->string('rentee_contact_no')->nullable(false);
            $table->string('rentee_email')->nullable(false);
            $table->foreignId('destination_id')->constrained()->onDelete('cascade');
            $table->date('rent_date');
            $table->time('rent_time');
            $table->date('rent_return');
            $table->time('rent_return_time');
            $table->string('status')->default('pending');
            $table->timestamps();
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('icon');
            $table->string('title');
            $table->string('description');
            $table->string('redirect_link');
            $table->enum('for', ['admin', 'staff']); // admin or staff
            $table->boolean('isRead')->default(false);
            $table->timestamps();
        });


        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->text('replied_message')->nullable(true);
            $table->text('replied_message_name')->nullable(true);
            $table->text('replied_message_type')->nullable(true);
            $table->string('sender_name');
            $table->string('receiver_name');
            $table->text('content')->nullable(true)->default('like');
            $table->text('img')->nullable(true);
            $table->boolean('isReacted')->default(false);
            $table->boolean('isRead')->default(false);
            $table->string('type')->default('like');
            $table->timestamps();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('darkMode')->default(false);
            $table->boolean('leftbarOpen')->default(false);
            $table->boolean('transition')->default(true);
            $table->timestamps();

        });

        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false);
            $table->foreignId('items_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('managed_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('pendings');
    }
};
