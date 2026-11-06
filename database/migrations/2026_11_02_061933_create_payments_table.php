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
        Schema::create('payments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('loan_id')->constrained('loans')->cascadeOnDelete();
        $table->decimal('amount_paid', 10, 2);
        $table->integer('month_number'); // Tracks which month's payment this covers
        $table->string('reference_no')->nullable();
        $table->foreignId('received_by')->constrained('users'); // Staff member who logged it
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
