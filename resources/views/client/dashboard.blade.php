<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Dashboard - MyLoan</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        orangeBrand: '#FF6B00',
                        greenBrand: '#10B981',
                        darkBrand: '#0F172A',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased flex flex-col min-h-screen justify-between">

    <!-- Navbar -->
    <header class="flex justify-between items-center px-8 py-4 bg-white shadow-sm sticky top-0 z-50">
        <div class="flex items-center gap-3">
            <div class="text-2xl font-extrabold text-orangeBrand tracking-wide">
                MY<span class="text-greenBrand">LOAN</span>
            </div>
            <span class="text-xs px-3 py-1 bg-orange-100 text-orangeBrand font-bold rounded-full uppercase tracking-wider">
                Client Portal
            </span>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-sm font-bold text-slate-700">Welcome, {{ Auth::user()->name }}</span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 font-semibold text-xs hover:bg-slate-200 transition">
                    Sign Out
                </button>
            </form>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-6xl mx-auto px-6 py-10 w-full flex-grow">
        
        <!-- Welcome Banner -->
        <div class="bg-gradient-to-r from-orangeBrand to-greenBrand p-8 rounded-3xl text-white shadow-lg mb-8">
            <h1 class="text-3xl font-extrabold">Client Dashboard</h1>
            <p class="mt-2 text-white/90 text-sm">
                Here is the real-time status of your active loan, repayment progress, and assigned staff handler.
            </p>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-200 text-green-700 rounded-2xl font-semibold text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if($loan)
            @php
                // Calculate total amount paid from recorded payments
                $totalPaid = $loan->payments->sum('amount_paid');
                $totalPayable = $loan->total_payable ?? ($loan->monthly_installment * $loan->term_months);
                
                // Determine if fully paid based on total payable amount or term months
                $isFullyPaid = ($totalPaid >= $totalPayable) || ($monthsPaid >= $loan->term_months) || ($monthsRemaining <= 0);
            @endphp

            <!-- Re-loan Banner (Appears only when total payable is reached/fully paid) -->
            @if($isFullyPaid)
                <div class="bg-gradient-to-r from-greenBrand to-emerald-600 p-8 rounded-3xl text-white shadow-xl mb-8 flex flex-col md:flex-row justify-between items-center gap-6 animate-fade-in">
                    <div>
                        <span class="px-3 py-1 bg-white/20 text-white font-bold text-xs rounded-full uppercase tracking-wider inline-block mb-2">
                            Congratulations! 🎉
                        </span>
                        <h2 class="text-2xl font-extrabold">You are officially Fully Paid!</h2>
                        <p class="text-white/90 text-sm mt-1">
                            Your loan of ₱{{ number_format($totalPayable, 2) }} has been completely settled. You are now eligible to re-loan up to ₱200,000.
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('loans.create') }}" class="px-8 py-4 bg-white text-greenBrand font-extrabold rounded-2xl shadow-lg hover:bg-slate-100 transition inline-block uppercase text-sm tracking-wider">
                            Apply for Re-loan Now →
                        </a>
                    </div>
                </div>
            @endif

            <!-- Top Metrics Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                
                <!-- Loan Status Card -->
                <div class="bg-white p-6 rounded-3xl shadow-xl border border-slate-100 flex flex-col justify-between">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Loan Status</span>
                        <div class="text-2xl font-extrabold capitalize mt-2 {{ $isFullyPaid ? 'text-greenBrand' : 'text-orangeBrand' }}">
                            {{ $isFullyPaid ? 'Fully Paid' : str_replace('_', ' ', $loan->status) }}
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-slate-100 text-xs text-slate-500 font-medium">
                        Amount Requested: <strong class="text-slate-800">₱{{ number_format($loan->amount_requested ?? $loan->amount, 2) }}</strong>
                    </div>
                </div>

                <!-- Assigned Staff Card -->
                <div class="bg-white p-6 rounded-3xl shadow-xl border border-slate-100 flex flex-col justify-between">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Assigned Staff Handler</span>
                        <div class="text-lg font-bold text-darkBrand mt-2">
                            {{ $loan->staff ? $loan->staff->name : 'Pending Assignment' }}
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-slate-100 text-xs text-slate-500 font-medium">
                        {{ $loan->staff ? $loan->staff->email : 'Will be assigned by Admin shortly' }}
                    </div>
                </div>

                <!-- Repayment Progress Card (Dynamically Updated) -->
                <div class="bg-white p-6 rounded-3xl shadow-xl border border-slate-100 flex flex-col justify-between">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Repayment Progress</span>
                        <div class="flex justify-between items-baseline mt-2">
                            <span class="text-3xl font-extrabold text-greenBrand">
                                {{ $monthsPaid }} / {{ $loan->term_months }}
                            </span>
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Months Paid</span>
                        </div>
                    </div>

                    <!-- Dynamic Progress Bar -->
                    @php
                        $totalTerm = $loan->term_months > 0 ? $loan->term_months : 12;
                        $percentage = min(100, round(($totalPaid / max($totalPayable, 1)) * 100));
                    @endphp
                    <div class="w-full bg-slate-100 h-2.5 rounded-full mt-4 overflow-hidden">
                        <div class="bg-greenBrand h-2.5 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Detailed Loan Breakdown Table / Grid -->
            <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-8 mb-8">
                <h3 class="text-lg font-extrabold text-darkBrand mb-6">Loan Account Details & Breakdown</h3>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-sm bg-slate-50 p-6 rounded-2xl border border-slate-100">
                    <div>
                        <span class="text-slate-400 text-xs font-bold uppercase tracking-wider block mb-1">Term Months</span>
                        <strong class="text-darkBrand text-lg font-extrabold">{{ $loan->term_months }} Months</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 text-xs font-bold uppercase tracking-wider block mb-1">Monthly Installment</span>
                        <strong class="text-darkBrand text-lg font-extrabold">₱{{ number_format($loan->monthly_installment, 2) }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 text-xs font-bold uppercase tracking-wider block mb-1">Total Payable</span>
                        <strong class="text-darkBrand text-lg font-extrabold">₱{{ number_format($totalPayable, 2) }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 text-xs font-bold uppercase tracking-wider block mb-1">Total Paid So Far</span>
                        <strong class="text-greenBrand text-lg font-extrabold">₱{{ number_format($totalPaid, 2) }}</strong>
                    </div>
                </div>
            </div>

            <!-- Payment Transaction History Table -->
            <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="text-lg font-extrabold text-darkBrand">Official Payment History</h3>
                    <span class="px-3 py-1 bg-slate-100 text-slate-600 text-xs font-bold rounded-full">
                        Total Recorded: {{ $loan->payments->count() }}
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-400 text-xs uppercase font-bold tracking-wider border-b">
                                <th class="p-4">Month Number</th>
                                <th class="p-4">Amount Paid</th>
                                <th class="p-4">Reference / Receipt No.</th>
                                <th class="p-4">Date Logged</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @forelse($loan->payments as $payment)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="p-4 font-bold text-darkBrand">Month #{{ $payment->month_number }}</td>
                                    <td class="p-4 font-extrabold text-greenBrand">₱{{ number_format($payment->amount_paid, 2) }}</td>
                                    <td class="p-4 font-mono text-slate-600 text-xs">{{ $payment->reference_no ?? 'Cash Payment' }}</td>
                                    <td class="p-4 text-slate-500 text-xs">{{ $payment->created_at->format('M d, Y - h:i A') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-10 text-center text-slate-400 font-medium">
                                        No cash payments have been recorded by your assigned staff yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        @else
            <div class="bg-white p-12 rounded-3xl text-center shadow-xl border border-slate-100">
                <h3 class="text-xl font-bold text-darkBrand mb-2">No Active Loan Found</h3>
                <p class="text-slate-500 text-sm">You currently do not have an active loan record registered in the system.</p>
                <a href="{{ route('loans.create') }}" class="mt-4 inline-block px-6 py-3 bg-greenBrand text-white font-bold rounded-xl text-sm">
                    Apply for a Loan
                </a>
            </div>
        @endif
    </main>

    <!-- Footer -->
    <footer class="text-center py-8 text-slate-400 text-xs border-t border-slate-100 mt-12">
        &copy; 2026 MyLoan System. All rights reserved.
    </footer>
</body>
</html>