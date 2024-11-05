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



        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('icon');
            $table->string('title');
            $table->string('description');
            $table->string('redirect_link');
            $table->enum('for', ['superadmin', 'admin', 'staff', 'cashier', 'all']);
            $table->string('category_id')->nullable();
            $table->json('isReadBy')->default(json_encode([]));
            $table->json('isDeletedBy')->default(json_encode([]));
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

        Schema::create('rentees', function (Blueprint $table) {
            $table->id();
            $table->string('rentee_code');
            $table->string('first_name')->nullable(true);
            $table->string('last_name')->nullable(true);
            $table->string('middle_name')->nullable(true);
            $table->string('email')->nullable(true);
            $table->string('contact_no')->nullable(true);
            $table->string('address_1')->nullable(true);
            $table->string('address_2')->nullable(true);
            $table->timestamps();

        });
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_code')->nullable(false);
            $table->foreignId('rentee_id')->constrained()->onDelete('cascade');
            $table->datetime('approved_at')->nullable(true);
            $table->enum('status', ['pending', 'approved', 'in progress', 'rejected', 'completed']);
            $table->timestamps();
        });

        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rentee_id')->constrained()->onDelete('cascade');
            $table->json('items');
            $table->timestamps();
        });


        Schema::create('rentee_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rentee_id')->constrained()->onDelete('cascade');
            $table->boolean('isSessionStarted')->default(false);
            $table->timestamps();
        });


        Schema::create('items_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade');
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->foreignId('destination_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->integer('qty');
            $table->date('rent_date');
            $table->time('rent_time');
            $table->date('rent_return');
            $table->time('rent_return_time');
            $table->datetime('declinedByAdmin_at')->nullable(true);
            $table->datetime('approvedByAdmin_at')->nullable(true);
            $table->datetime('approvedByCashier_at')->nullable(true);
            $table->string('admin_id')->nullable(true);
            $table->string('cashier_id')->nullable(true);
            $table->datetime('claimed_at')->nullable(true);
            $table->datetime('returned_at')->nullable(true);
            $table->string('receivedBy_id')->nullable(true);
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
