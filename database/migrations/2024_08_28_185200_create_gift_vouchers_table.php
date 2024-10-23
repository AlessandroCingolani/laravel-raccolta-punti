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
        Schema::create('gift_vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('recipient_first_name');
            $table->string('recipient_last_name');
            $table->string('code')->unique();
            $table->decimal('amount', 10, 2);
            $table->date('expiration_date');
            $table->timestamp('used_at')->nullable();
            $table->enum('status', ['valid', 'used', 'expired'])->default('valid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gift_vouchers');
    }
};
