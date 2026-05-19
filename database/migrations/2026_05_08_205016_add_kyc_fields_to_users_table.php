<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('kyc_status')->default('not_submitted')->after('is_verified'); // not_submitted, pending, verified, rejected
            $table->string('aadhaar_number', 12)->nullable()->after('kyc_status');
            $table->string('aadhaar_document')->nullable()->after('aadhaar_number');
            $table->string('pan_number', 10)->nullable()->after('aadhaar_document');
            $table->string('pan_document')->nullable()->after('pan_number');
            $table->string('bank_account_number')->nullable()->after('pan_document');
            $table->string('bank_ifsc')->nullable()->after('bank_account_number');
            $table->string('bank_name')->nullable()->after('bank_ifsc');
            $table->text('address')->nullable()->after('phone');
            $table->string('city')->nullable()->after('address');
            $table->string('state')->nullable()->after('city');
            $table->string('pincode', 6)->nullable()->after('state');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'kyc_status', 'aadhaar_number', 'aadhaar_document',
                'pan_number', 'pan_document', 'bank_account_number',
                'bank_ifsc', 'bank_name', 'address', 'city', 'state', 'pincode'
            ]);
        });
    }
};
