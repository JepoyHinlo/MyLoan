<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\User;
use App\Models\LoanProduct;
use App\Models\DirectPayment; // 👈 1. Import DirectPayment model
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; // 👈 2. Import DB facade for aggregates

class AdminController extends Controller
{
    public function dashboard()
    {
        $loans = Loan::with(['client', 'user', 'staff', 'loanProduct', 'payments'])->latest()->get();
        $staffMembers = User::where('role', 'staff')->get();
        $clients = User::where('role', 'client')->with('loans')->latest()->get();
        $loanProduct = LoanProduct::first();

        $totalLoansCount = $loans->count();
        
        $totalDisbursed = $loans->filter(function($loan) {
            return in_array(strtolower($loan->status), ['approved', 'disbursed', 'completed']);
        })->sum(function($loan) {
            return $loan->amount ?? $loan->amount_requested ?? 0;
        });

        $totalPendingCI = $loans->filter(function($loan) {
            return in_array(strtolower($loan->status), ['pending', 'Pending', 'ci_in_progress', 'ci_completed']);
        })->count();

        // ==========================================
        // 📊 3. DIRECT PAYMENT ANALYTICS CALCULATIONS
        // ==========================================
        $totalDirectCollected = DirectPayment::sum('amount_paid');
        $totalTransactions = DirectPayment::count();
        
        $paymentsByStaff = DirectPayment::with('staff')
            ->select('collected_by_staff_id', DB::raw('sum(amount_paid) as total'), DB::raw('count(*) as count'))
            ->groupBy('collected_by_staff_id')
            ->get();

        $recentPayments = DirectPayment::with(['client', 'staff', 'loan'])->latest()->take(5)->get();

        // Pass everything to the admin view (including analytics variables)
        return view('admin.dashboard', compact(
            'loans', 
            'staffMembers', 
            'clients', 
            'loanProduct', 
            'totalLoansCount', 
            'totalDisbursed', 
            'totalPendingCI',
            'totalDirectCollected',
            'totalTransactions',
            'paymentsByStaff',
            'recentPayments'
        ));
    }

    /**
     * Store a new staff member created by the admin.
     */
    public function storeStaff(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role'     => 'staff',
        ]);

        return back()->with('success', 'New staff member registered successfully.');
    }

    public function assignStaff(Request $request, Loan $loan)
    {
        $request->validate([
            'staff_id' => 'required|exists:users,id',
        ]);

        $loan->update([
            'staff_id' => $request->staff_id,
            'status' => 'ci_in_progress',
        ]);

        return back()->with('success', 'Staff assigned successfully.');
    }

    public function updateStatus(Request $request, Loan $loan)
    {
        $request->validate([
            'status' => 'required|in:approved,declined,pending,Pending,Approved,Declined,ci_in_progress,ci_completed',
        ]);

        $loan->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Loan application status updated successfully.');
    }

    public function updateRates(Request $request, LoanProduct $product)
    {
        $request->validate([
            'base_interest_rate' => 'required|numeric|min:0',
            'returning_interest_rate' => 'required|numeric|min:0',
        ]);

        $product->update([
            'base_interest_rate' => $request->base_interest_rate,
            'returning_interest_rate' => $request->returning_interest_rate,
        ]);

        return back()->with('success', 'Loan product interest rates updated successfully.');
    }
}