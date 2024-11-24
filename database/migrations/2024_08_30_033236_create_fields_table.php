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
            $table->string('folder_name');
            $table->timestamps();
        });

        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('img');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->integer('qty');
            $table->decimal('price', 10, 2)->nullable(true);
            $table->enum('per', ['pcs', 'hr', 'km', 'mi', 'm', 'kg', 'g', 'mg', 'cm', 'mm', 'lbs', 'oz', 'l', 'ml'])->nullable(true);
            $table->enum('approval_level', ['admin', 'staff', 'both']);
            $table->string('assigned_personel')->nullable(true);
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
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->nullable(true);
            $table->foreignId('rentee_id')->nullable(true);
            $table->string('reservation_id')->nullable(true);
            $table->string('category_id')->nullable(true);
            $table->string('property_id')->nullable(true);
            $table->string('title');
            $table->string('description');
            $table->string('redirect_link');
            $table->enum('for', ['superadmin', 'admin', 'staff', 'cashier', 'superadmin|admin', 'admin|staff', 'staff|cashier']);
            $table->json('isReadBy')->default(json_encode([]));
            $table->json('isDeletedBy')->default(json_encode([]));
            $table->timestamps();
        });

        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->text('replied_message')->nullable(true);
            $table->text('replied_message_by_id')->nullable(true);
            $table->text('replied_message_type')->nullable(true);
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade');
            $table->text('content')->nullable(true)->default('like');
            $table->text('img')->nullable(true);
            $table->boolean('isReactedBySender')->default(false);
            $table->boolean('isReactedByReceiver')->default(false);
            $table->boolean('isReadBySender')->default(false);
            $table->boolean('isReadByReceiver')->default(false);
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
            $table->foreignId('properties_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('managed_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId(column: 'user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('rentees', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name')->nullable(true);
            $table->string('email')->nullable(true);
            $table->string('contact_no')->nullable(true);
            $table->string('address')->nullable(true);
            $table->timestamps();

        });
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_code')->nullable(false);
            $table->foreignId('rentee_id')->constrained()->onDelete('cascade');
            $table->datetime('approved_at')->nullable(true);
            $table->datetime('canceled_at')->nullable(true);
            $table->enum('reservation_type', ['rent', 'borrow'])->nullable(false);
            $table->text('purpose')->nullable(true);
            $table->enum('status', ['pending', 'canceled', 'approved', 'in progress', 'declined ', 'occupied', 'completed']);
            $table->timestamps();
        });

        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rentee_id')->constrained()->onDelete('cascade');
            $table->json('properties');
            $table->timestamps();
        });


        Schema::create('rentee_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rentee_id')->constrained()->onDelete('cascade');
            $table->boolean('isSessionStarted')->default(false);
            $table->timestamps();
        });

        Schema::create('property_reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained()->onDelete('cascade');
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->foreignId('destination_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->integer('qty');
            $table->date('date_start');
            $table->time('time_start');
            $table->date('date_end');
            $table->time('time_end');
            $table->datetime('canceledByRentee_at')->nullable(true);
            $table->datetime('declinedByAdmin_at')->nullable(true);
            $table->datetime('approvedByAdmin_at')->nullable(true);
            $table->datetime('approvedByCashier_at')->nullable(true);
            $table->string('admin_id')->nullable(true);
            $table->string('cashier_id')->nullable(true);
            $table->datetime('claimed_at')->nullable(true);
            $table->datetime('returned_at')->nullable(true);
            $table->string('receivedBy_id')->nullable(true);
            $table->text('message')->nullable(true);
            $table->string('assigned_personel')->nullable(true);
            $table->timestamps();
        });

        Schema::create('password_reset_requests', function (Blueprint $table) {
            $table->id();
            $table->string('email')->nullable(false);
            $table->dateTime('passwordChanged_at')->nullable(true);
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
