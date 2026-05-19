<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transport_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farmer_id')->constrained('users')->cascadeOnDelete();
            $table->string('crop_type');
            $table->decimal('quantity_tons', 8, 2);
            $table->enum('packaging_type', ['sacks', 'crates', 'loose', 'boxes'])->default('sacks');
            $table->decimal('pickup_lat', 10, 7)->nullable();
            $table->decimal('pickup_lng', 10, 7)->nullable();
            $table->string('pickup_address');
            $table->foreignId('destination_market_id')->constrained('markets')->cascadeOnDelete();
            $table->date('required_date');
            $table->boolean('is_perishable')->default(false);
            $table->enum('status', ['pending', 'matched', 'booked', 'in_transit', 'delivered', 'cancelled'])->default('pending');
            $table->text('special_instructions')->nullable();
            $table->timestamps();

            $table->index(['status', 'required_date']);
            $table->index('crop_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transport_requests');
    }
};
