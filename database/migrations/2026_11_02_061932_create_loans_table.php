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
        Schema::create('loans', function (Blueprint $table) {
        $table->id();
        $table->foreignId('client_id')->constrained('users')->cascadeOnDelete();
        $table->foreignId('loan_product_id')->constrained('loan_products');
        $table->foreignId('staff_id')->nullable()->constrained('users')->nullOnDelete();
        $table->decimal('amount_requested', 10, 2);
        $table->integer('term_months');
        $table->decimal('interest_rate', 5, 2); 
        $table->decimal('total_payable', 10, 2);
        $table->decimal('monthly_installment', 10, 2);
        
        // Tracking statuses required by your system
        $table->enum('status', ['pending', 'ci_in_progress', 'ci_completed', 'approved', 'declined'])->default('pending');
        $table->boolean('is_ci_done')->default(false); // Staff C.I. checkbox/flag
        $table->text('staff_remarks')->nullable();
        
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
