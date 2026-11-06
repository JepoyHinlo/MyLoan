<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\DirectPayment;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    /**
     * Show the form for creating a new loan or re-loan.
     */
    public function create()
    {
        // Return the view where clients apply for a loan or re-loan
        return view('loans.create'); // Change 'loans.create' to match your actual blade filename if it differs (e.g., 'client.apply')
    }

    // 1. Handle C.I. Actions & Declining Loans
    public function action(Request $request, Loan $loan)
    {
        $user = Auth::user();

        if ($user->role !== 'admin' && $loan->staff_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($request->input('action') === 'decline') {
            $loan->update(['status' => 'declined']);
            return back()->with('success', 'Loan application declined.');
        }

        $loan->update([
            'staff_remarks' => $request->input('staff_remarks'),
            'is_ci_done'    => $request->has('is_ci_done') ? 1 : 0,
        ]);

        return back()->with('success', 'C.I. details successfully updated!');
    }

    // 2. Handle Logging Direct Payments from Staff Dashboard
    public function storeDirectPayment(Request $request, Loan $loan)
    {
        $user = Auth::user();

        if ($user->role !== 'admin' && $loan->staff_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'amount_paid'      => ['required', 'numeric', 'min:1'],
            'payment_date'     => ['required', 'date'],
            'reference_number' => ['nullable', 'string', 'max:255'],
        ]);

        DirectPayment::create([
            'loan_id'               => $loan->id,
            'client_id'             => $loan->client_id,
            'collected_by_staff_id' => $user->id,
            'amount_paid'           => $validated['amount_paid'],
            'payment_method'        => 'Cash to Staff',
            'reference_number'      => $validated['reference_number'] ?? 'Direct Cash',
            'payment_date'          => $validated['payment_date'],
            'status'                => 'verified',
        ]);

        return back()->with('success', 'Direct payment successfully recorded!');
    }
}