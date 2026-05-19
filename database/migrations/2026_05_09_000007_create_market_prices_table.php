<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('market_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('market_id')->constrained('markets')->cascadeOnDelete();
            $table->string('crop_type');
            $table->decimal('price_per_quintal', 10, 2);
            $table->date('recorded_date');
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['market_id', 'crop_type', 'recorded_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('market_prices');
    }
};
