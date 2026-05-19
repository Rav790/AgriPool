<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained('transport_requests')->cascadeOnDelete();
            $table->foreignId('listing_id')->constrained('transport_listings')->cascadeOnDelete();
            $table->foreignId('farmer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('transporter_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('allocated_tons', 8, 2);
            $table->decimal('total_price', 10, 2);
            $table->enum('status', ['pending', 'confirmed', 'picked_up', 'in_transit', 'delivered', 'cancelled', 'disputed'])->default('pending');
            $table->enum('payment_status', ['unpaid', 'held', 'released', 'refunded'])->default('unpaid');
            $table->enum('payment_mode', ['upi', 'cash', 'wallet'])->nullable();
            $table->timestamp('pickup_confirmed_at')->nullable();
            $table->timestamp('delivery_confirmed_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'payment_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
