<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'loan_product_id',
        'staff_id',
        'amount',
        'amount_requested',
        'term_months',
        'interest_rate',
        'total_payable',
        'monthly_installment',
        'status',
        'is_ci_done',
        'staff_remarks',
        'monthly_income',
        'work_status',
    ];

    /**
     * Get the client that owns the loan.
     */
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Alias for client relationship (fixes eager-loading errors).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Get the staff member assigned to the loan.
     */
    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    /**
     * Get the loan product associated with the loan.
     */
    public function loanProduct()
    {
        return $this->belongsTo(LoanProduct::class, 'loan_product_id');
    }

    /**
     * Get all payments recorded for this loan.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class, 'loan_id');
    }
}