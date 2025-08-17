<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('supplier_bank_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();
            $table->string('bank_name');
            $table->string('branch_name');
            $table->string('account_number');
            $table->string('bank_address');
            $table->string('account_type')->nullable();
            $table->string('swift_code')->nullable();
            $table->string('routing_number')->nullable();
            $table->string('iban_number')->nullable();
            $table->string('currency_type')->nullable();
            $table->string('mobile_banking_address')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('bank_contact_person')->nullable();
            $table->string('status')->default('active');
            $table->string('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_bank_details');
    }
};
