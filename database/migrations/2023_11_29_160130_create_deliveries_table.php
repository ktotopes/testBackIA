<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable()->constrained('orders');

            $table->string('to_address');
            $table->string('from_address');

            $table->string('from_coordinates');
            $table->string('to_coordinates');

            $table->decimal('distance');
            $table->decimal('price');
            $table->decimal('weights');

            $table->string('sender');
            $table->string('sender_contact');

            $table->string('recipient');
            $table->string('recipient_contact');

            $table->dateTime('dispatch_at')->nullable();
            $table->dateTime('delivered_at')->nullable();

            $table->dateTime('should_delivered');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
