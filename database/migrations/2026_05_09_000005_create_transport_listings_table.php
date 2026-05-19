<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transport_listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transporter_id')->constrained('users')->cascadeOnDelete();
            $table->string('route_from');
            $table->string('route_to');
            $table->decimal('route_from_lat', 10, 7)->nullable();
            $table->decimal('route_from_lng', 10, 7)->nullable();
            $table->decimal('route_to_lat', 10, 7)->nullable();
            $table->decimal('route_to_lng', 10, 7)->nullable();
            $table->date('available_date');
            $table->decimal('total_capacity', 8, 2);
            $table->decimal('remaining_capacity', 8, 2);
            $table->decimal('price_per_ton', 10, 2);
            $table->enum('status', ['available', 'partially_booked', 'fully_booked', 'in_transit', 'completed', 'cancelled'])->default('available');
            $table->timestamps();

            $table->index(['status', 'available_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transport_listings');
    }
};
