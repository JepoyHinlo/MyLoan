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
        Schema::create('loan_products', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // e.g., "Personal Loan", "Business Loan"
        $table->decimal('base_interest_rate', 5, 2); // For new loaners
        $table->decimal('returning_interest_rate', 5, 2); // For returning clients
        $table->integer('max_term_months');
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_products');
    }
};
