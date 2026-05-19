<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cooperative_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cooperative_group_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('role')->default('member'); // admin, member
            $table->timestamp('joined_at')->useCurrent();
            $table->timestamps();

            $table->unique(['cooperative_group_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cooperative_members');
    }
};
