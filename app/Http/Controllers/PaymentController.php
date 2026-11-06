<?php

use App\Models\DirectPayment;

public function adminAnalytics()
{
    // Fetch analytics data for direct payments
    $totalDirectCollected = DirectPayment::sum('amount_paid');
    $totalTransactions = DirectPayment::count();
    
    // Group payments by staff member
    $paymentsByStaff = DirectPayment::with('staff')
        ->select('collected_by_staff_id', \DB::raw('sum(amount_paid) as total'), \DB::raw('count(*) as count'))
        ->groupBy('collected_by_staff_id')
        ->get();

    // Recent direct payments list
    $recentPayments = DirectPayment::with(['client', 'staff', 'loan'])->latest()->take(5)->get();

    return view('admin.dashboard', compact(
        'totalDirectCollected', 
        'totalTransactions', 
        'paymentsByStaff', 
        'recentPayments'
    ));
}

// 1. Store a payment handed directly to staff
public function storeDirectPayment(Request $request, Loan $loan)
{
    $user = Auth::user();

    // Security check: Only admin or the assigned staff can record direct collection
    if ($user->role !== 'admin' && $loan->staff_id !== $user->id) {
        abort(403, 'Unauthorized. You are not assigned to this client.');
    }

    $validated = $request->validate([
        'amount_paid'      => ['required', 'numeric', 'min:1'],
        'payment_method'   => ['required', 'string'],
        'reference_number' => ['nullable', 'string', 'max:255'],
        'payment_date'     => ['required', 'date'],
        'remarks'          => ['nullable', 'string'],
    ]);

    DirectPayment::create([
        'loan_id'               => $loan->id,
        'client_id'             => $loan->client_id,
        'collected_by_staff_id' => $user->id,
        'amount_paid'           => $validated['amount_paid'],
        'payment_method'        => $validated['payment_method'],
        'reference_number'      => $validated['reference_number'],
        'payment_date'          => $validated['payment_date'],
        'status'                => 'verified', // Auto-verified since staff collected it directly
        'remarks'               => $validated['remarks'],
    ]);

    return back()->with('success', 'Direct payment successfully recorded and logged in the monitoring report.');
}

// 2. Display the Payment Monitoring Report
public function paymentReport()
{
    $user = Auth::user();

    // If staff, view only payments collected by them or their assigned loans. If admin, view all.
    if ($user->role === 'admin') {
        $payments = DirectPayment::with(['client', 'staff', 'loan'])->latest()->paginate(15);
    } else {
        $payments = DirectPayment::with(['client', 'staff', 'loan'])
            ->where('collected_by_staff_id', $user->id)
            ->latest()
            ->paginate(15);
    }

    return view('reports.direct-payments', compact('payments'));
}