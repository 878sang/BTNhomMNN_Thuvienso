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
        Schema::create('points_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 15, 2)->default(0); // Money in VND
            $table->integer('points')->default(0);
            $table->string('type'); // recharge, download, reward
            $table->string('status')->default('pending'); // pending, completed, failed
            $table->string('reference_id')->nullable(); // VNPAY transaction ID
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('points_transactions');
    }
};
