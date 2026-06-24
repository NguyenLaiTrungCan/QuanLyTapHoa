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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->unsignedBigInteger('total_price')->comment('Tổng tiền tính bằng VNĐ');
            $table->enum('status', ['pending', 'processing', 'shipped', 'delivered', 'canceled'])->default('pending');
            $table->text('delivery_address');
            $table->string('phone', 20)->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
            $table->index('user_id');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
