<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_id',
        'amount_paid',
        'month_number',
        'reference_no',
        'received_by', // <-- This allows mass assignment for the staff ID
    ];

    /**
     * Get the loan that owns the payment.
     */
    public function loan()
    {
        return $this->belongsTo(Loan::class, 'loan_id');
    }

    /**
     * Get the staff user who received the payment.
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}