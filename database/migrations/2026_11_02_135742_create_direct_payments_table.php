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
        Schema::create('direct_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->constrained('loans')->onDelete('cascade');
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('collected_by_staff_id')->constrained('users')->onDelete('cascade');
            $table->decimal('amount_paid', 12, 2);
            $table->string('payment_method')->default('Cash to Staff'); // Cash, GCash, Bank Transfer, etc.
            $table->string('reference_number')->nullable();
            $table->date('payment_date');
            $table->enum('status', ['pending_verification', 'verified', 'rejected'])->default('pending_verification');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('direct_payments');
    }
};
