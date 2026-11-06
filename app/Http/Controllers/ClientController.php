<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Loan;

class ClientController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        // Fetch the client's latest loan with payments, loan product, and staff details eager-loaded
        $loan = Loan::with(['payments', 'loanProduct', 'staff'])
                    ->where('client_id', $user->id)
                    ->latest()
                    ->first();

        // Dynamically calculate months paid from recorded payment rows
        $monthsPaid = $loan ? $loan->payments->count() : 0;
        
        // Calculate remaining months securely (ensuring it never drops below 0)
        $monthsRemaining = $loan ? max(0, $loan->term_months - $monthsPaid) : 0;

        // Pass both variables to the client dashboard view
        return view('client.dashboard', compact('loan', 'monthsPaid', 'monthsRemaining'));
    }
}