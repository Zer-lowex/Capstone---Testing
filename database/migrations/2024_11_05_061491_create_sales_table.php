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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreignId('customer_id')->nullable();
            $table->decimal('delivery_fee', 10, 2)->nullable();
            $table->decimal('total_amount', 10, 2);
            $table->decimal('net_amount', 10, 2);
            $table->decimal('paid_amount', 10, 2);
            $table->decimal('sukli', 10, 2);  
            $table->decimal('discount', 10, 2)->default(0);  
            $table->decimal('vat_amount', 10, 2)->default(0);
            $table->string('discount_type')->nullable();
            $table->string('customer_address')->nullable();
            $table->string('delivery')->default('NO');
            $table->string('status')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
