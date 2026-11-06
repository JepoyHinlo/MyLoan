<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_id',
        'client_id',
        'collected_by_staff_id',
        'amount_paid',
        'payment_method',
        'reference_number',
        'payment_date',
        'status',
        'remarks',
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'collected_by_staff_id');
    }
}