<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Loan;
use App\Models\Payment;

class StaffController extends Controller
{
    /**
     * Display the staff dashboard with assigned loans.
     */
    public function dashboard()
    {
        $staffId = Auth::id();

        // Fetch loans assigned to this staff member
        $loans = Loan::with(['client', 'payments'])
                    ->where('staff_id', $staffId)
                    ->latest()
                    ->get();

        return view('staff.dashboard', compact('loans'));
    }

    /**
     * Update the credit investigation (C.I.) status for a loan.
     */
    public function updateCiStatus(Request $request, $id)
    {
        $loan = Loan::where('id', $id)
                    ->where('staff_id', Auth::id())
                    ->firstOrFail();

        // Toggle C.I. status completion
        $loan->is_ci_done = !$loan->is_ci_done;
        $loan->status = $loan->is_ci_done ? 'ci_completed' : 'ci_in_progress';
        $loan->save();

        return back()->with('success', 'Credit Investigation status updated successfully!');
    }

    /**
     * Store a cash payment recorded by the staff member.
     */
    public function storePayment(Request $request)
    {
        $request->validate([
            'loan_id' => 'required|exists:loans,id',
            'amount_paid' => 'required|numeric|min:0',
            'month_number' => 'nullable|integer',
            'reference_no' => 'nullable|string',
        ]);

        // Verify the loan belongs to a client assigned to this staff or fetch the loan
        $loan = Loan::where('id', $request->loan_id)->where('staff_id', Auth::id())->firstOrFail();

        // Automatically determine the next month number if not provided
        $nextMonth = $loan->payments()->count() + 1;

        // Create the payment record with received_by populated to satisfy the NOT NULL constraint
        Payment::create([
            'loan_id' => $request->loan_id,
            'amount_paid' => $request->amount_paid,
            'month_number' => $request->month_number ?? $nextMonth,
            'reference_no' => $request->reference_no ?? 'Cash Payment - Staff Verified',
            'received_by' => Auth::id(), // Satisfies database constraint: payments.received_by NOT NULL
        ]);

        return back()->with('success', 'Cash payment recorded successfully! Client repayment progress has been updated.');
    }
}