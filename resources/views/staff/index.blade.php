<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Portal - MyLoan System</title>
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
<body class="bg-slate-50 text-darkBrand antialiased">

    <!-- Navbar -->
    <header class="flex justify-between items-center px-8 py-6 bg-white shadow-sm sticky top-0 z-50">
        <div class="flex items-center gap-3">
            <div class="text-2xl font-extrabold text-orangeBrand tracking-wide">
                MY<span class="text-greenBrand">LOAN</span>
            </div>
            <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full uppercase tracking-wider">
                Staff Portal
            </span>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-sm font-bold text-slate-700">Hello, {{ Auth::user()->name }}</span>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 bg-slate-100 text-slate-700 font-semibold text-xs rounded-xl hover:bg-slate-200 transition">
                    Logout
                </button>
            </form>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-6 py-8 space-y-8">

        @if(session('success'))
            <div class="p-4 bg-emerald-100 border border-emerald-200 text-emerald-700 rounded-2xl font-semibold text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 bg-red-100 border border-red-200 text-red-700 rounded-2xl font-semibold text-sm">
                <ul class="list-disc pl-4">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Banner Header -->
        <div class="p-8 bg-gradient-to-r from-emerald-500 to-orange-600 rounded-3xl text-white shadow-xl">
            <h1 class="text-3xl font-extrabold">Staff Dashboard: Loan Officer</h1>
            <p class="mt-2 text-white/90 text-sm">Manage assigned client credit investigations (C.I.), record direct repayments, and track application statuses.</p>
        </div>

        <!-- Loans Table Container -->
        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h3 class="text-lg font-extrabold text-darkBrand">Assigned & Pending Loan Applications</h3>
                <span class="px-3 py-1 bg-slate-100 text-slate-600 text-xs font-bold rounded-full">
                    Total: {{ $loans->count() }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-400 text-xs uppercase tracking-wider border-b border-slate-100">
                            <th class="p-4 font-bold">Client</th>
                            <th class="p-4 font-bold">Amount & Term</th>
                            <th class="p-4 font-bold">Work / Income</th>
                            <th class="p-4 font-bold">Status</th>
                            <th class="p-4 font-bold">C.I. Status</th>
                            <th class="p-4 font-bold">Actions / Control & Direct Pay</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm align-top">
                        @forelse($loans as $loan)
                            <tr>
                                <!-- Client Info -->
                                <td class="p-4">
                                    <div class="font-extrabold text-slate-800">{{ $loan->client->name ?? 'N/A' }}</div>
                                    <div class="text-xs text-slate-400">{{ $loan->client->email ?? 'N/A' }}</div>
                                </td>

                                <!-- Amount & Term -->
                                <td class="p-4">
                                    <div class="font-extrabold text-orange-600">₱{{ number_format($loan->amount, 2) }}</div>
                                    <div class="text-xs text-slate-400">{{ $loan->term_months }} Months Term</div>
                                </td>

                                <!-- Work / Income -->
                                <td class="p-4">
                                    <div class="text-slate-800 font-semibold text-sm">{{ $loan->work_status }}</div>
                                    <div class="text-xs text-slate-400">₱{{ number_format($loan->monthly_income, 2) }}/mo</div>
                                </td>

                                <!-- Status -->
                                <td class="p-4">
                                    <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full">
                                        {{ strtoupper($loan->status) }}
                                    </span>
                                </td>

                                <!-- C.I. Status -->
                                <td class="p-4 text-xs font-semibold text-slate-600">
                                    @if($loan->is_ci_done)
                                        <span class="text-emerald-600 font-bold">C.I. Completed</span>
                                        <div class="text-[10px] text-slate-400 italic">"{{ $loan->staff_remarks }}"</div>
                                    @else
                                        <span class="text-orange-500 font-bold">Pending C.I.</span>
                                    @endif
                                </td>

                                <!-- Actions / Control & Direct Payment Logging -->
                                <td class="p-4 w-80">
                                    @php
                                        $isAssigned = Auth::user()->role === 'admin' || $loan->staff_id === Auth::user()->id;
                                    @endphp

                                    @if($isAssigned)
                                        <!-- Assigned Staff Box -->
                                        <div class="space-y-4 bg-slate-50 p-4 rounded-2xl border border-slate-200 shadow-sm">
                                            
                                            <!-- C.I. Form -->
                                            <form action="{{ route('loans.action', $loan->id) }}" method="POST" class="space-y-2">
                                                @csrf
                                                <div>
                                                    <input type="text" name="staff_remarks" placeholder="C.I. remarks..." value="{{ $loan->staff_remarks }}" class="w-full p-2 bg-white border rounded-xl text-xs outline-none focus:ring-2 focus:ring-orange-500">
                                                </div>
                                                <div class="flex items-center justify-between">
                                                    <label class="flex items-center gap-1 text-xs font-bold text-slate-700 cursor-pointer">
                                                        <input type="checkbox" name="is_ci_done" value="1" {{ $loan->is_ci_done ? 'checked' : '' }}> Done
                                                    </label>
                                                    <div class="flex gap-1">
                                                        <button type="submit" name="action" value="save" class="px-3 py-1 bg-orange-600 text-white rounded-lg text-xs font-bold hover:bg-orange-700 transition">
                                                            Save C.I.
                                                        </button>
                                                        <button type="submit" name="action" value="decline" class="px-3 py-1 bg-red-100 text-red-700 rounded-lg text-xs font-bold hover:bg-red-200 transition">
                                                            Decline
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>

                                            <hr class="border-slate-200">

                                            <!-- Direct Payment Form -->
                                            <form action="{{ route('loans.direct-payment', $loan->id) }}" method="POST" class="space-y-2">
                                                @csrf
                                                <div class="text-[11px] font-extrabold uppercase text-slate-600 tracking-wider">Record Direct Payment</div>
                                                <div class="grid grid-cols-2 gap-2">
                                                    <input type="number" step="0.01" name="amount_paid" placeholder="Amount (₱)" required class="p-2 bg-white border rounded-xl text-xs outline-none">
                                                    <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" required class="p-2 bg-white border rounded-xl text-xs outline-none">
                                                </div>
                                                <div class="flex gap-2">
                                                    <input type="text" name="reference_number" placeholder="Ref / Receipt No." class="w-full p-2 bg-white border rounded-xl text-xs outline-none">
                                                    <button type="submit" class="px-3 py-1 bg-emerald-600 text-white rounded-xl text-xs font-bold hover:bg-emerald-700 transition whitespace-nowrap">
                                                        Log Pay
                                                    </button>
                                                </div>
                                            </form>

                                            <!-- Direct Payment History Ledger -->
                                            @if($loan->directPayments && $loan->directPayments->count() > 0)
                                                <div class="pt-2 border-t border-slate-200">
                                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Direct Payment History:</span>
                                                    <div class="space-y-1 max-h-28 overflow-y-auto">
                                                        @foreach($loan->directPayments as $payment)
                                                            <div class="flex justify-between items-center bg-white p-2 rounded-lg border border-slate-100 text-[11px]">
                                                                <div>
                                                                    <span class="font-bold text-emerald-600">₱{{ number_format($payment->amount_paid, 2) }}</span>
                                                                    <span class="text-slate-400 text-[10px]">({{ $payment->payment_date }})</span>
                                                                </div>
                                                                <span class="text-slate-500 text-[10px]">Ref: {{ $payment->reference_number ?: 'Cash' }}</span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif

                                        </div>
                                    @else
                                        <!-- Unassigned Staff Locked Box -->
                                        <div class="bg-slate-100 p-4 rounded-2xl opacity-50 pointer-events-none space-y-2">
                                            <input type="text" placeholder="C.I. remarks..." disabled class="w-full p-2 bg-white border rounded-xl text-xs">
                                            <div class="text-[10px] text-red-500 font-bold text-center">Locked: Not Assigned to You</div>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-slate-400 font-semibold">
                                    No assigned loan applications found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="text-center py-8 text-slate-400 text-xs border-t border-slate-100 mt-12">
        &copy; 2026 MyLoan System. All rights reserved.
    </footer>

</body>
</html>