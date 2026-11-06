<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Command Center - MyLoan</title>
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
            <span class="text-xs px-3 py-1 bg-green-100 text-greenBrand font-bold rounded-full uppercase tracking-wider">
                Staff Command Center
            </span>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-sm font-bold text-slate-700">Officer: {{ Auth::user()->name }}</span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 font-semibold text-xs hover:bg-slate-200 transition">
                    Sign Out
                </button>
            </form>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-6 py-10 w-full flex-grow">
        
        <!-- Welcome Banner -->
        <div class="bg-gradient-to-r from-darkBrand to-slate-800 p-8 rounded-3xl text-white shadow-xl mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-extrabold">Staff Operations & Verification</h1>
                <p class="mt-2 text-slate-300 text-sm">
                    Manage assigned credit investigations, evaluate client eligibility, and record cash payments.
                </p>
            </div>
            <div class="hidden md:block bg-white/10 px-6 py-4 rounded-2xl border border-white/10 text-right">
                <span class="text-xs text-slate-400 block uppercase font-bold tracking-wider">Assigned Queue</span>
                <span class="text-2xl font-extrabold text-greenBrand">{{ isset($loans) ? $loans->count() : 0 }} Loans</span>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-200 text-green-700 rounded-2xl font-semibold text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-100 border border-red-200 text-red-700 rounded-2xl text-sm">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Assigned Loans Queue & Processing Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left: Loan Applications List (2 Cols) -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-6">
                    <h3 class="text-lg font-extrabold text-darkBrand mb-4">Assigned Client Loan Applications</h3>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 text-slate-400 text-xs uppercase font-bold tracking-wider border-b">
                                    <th class="p-3">Client</th>
                                    <th class="p-3">Amount</th>
                                    <th class="p-3">Monthly Income</th>
                                    <th class="p-3">Status</th>
                                    <th class="p-3 text-right">Action / Tools</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm">
                                @isset($loans)
                                    @forelse($loans as $loanItem)
                                        <tr class="hover:bg-slate-50/50 transition">
                                            <td class="p-3 font-bold text-darkBrand">
                                                {{ $loanItem->client->name ?? 'N/A' }}
                                                <span class="block text-xs font-normal text-slate-400">{{ $loanItem->client->email ?? '' }}</span>
                                            </td>
                                            <td class="p-3 font-extrabold text-orangeBrand">₱{{ number_format($loanItem->amount_requested ?? $loanItem->amount, 2) }}</td>
                                            <td class="p-3 text-slate-600 font-medium">₱{{ number_format($loanItem->monthly_income ?? 0, 2) }}</td>
                                            <td class="p-3">
                                                <span class="px-2.5 py-1 text-xs font-bold rounded-full uppercase 
                                                    {{ $loanItem->status == 'approved' ? 'bg-green-100 text-green-700' : 
                                                       ($loanItem->status == 'declined' ? 'bg-red-100 text-red-700' : 'bg-orange-100 text-orangeBrand') }}">
                                                    {{ str_replace('_', ' ', $loanItem->status) }}
                                                </span>
                                            </td>
                                            <td class="p-3 text-right">
                                                <!-- Form to Update C.I. Status -->
                                                <form action="{{ route('staff.loans.ci', $loanItem->id) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    <button type="submit" class="px-3 py-1.5 bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-xl font-bold text-xs transition">
                                                        {{ $loanItem->is_ci_done ? 'C.I. Done ✓' : 'Mark C.I. Done' }}
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="p-8 text-center text-slate-400 font-medium">
                                                No loan applications are currently assigned to your staff account.
                                            </td>
                                        </tr>
                                    @endforelse
                                @endisset
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right: Cash Payment Recording Form (1 Col) -->
            <div class="space-y-6">
                <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-6 sticky top-24">
                    <h3 class="text-lg font-extrabold text-darkBrand mb-2">Record Cash Payment</h3>
                    <p class="text-xs text-slate-500 mb-6">Log physical cash collected from a client to instantly update their repayment progress.</p>

                    <form action="{{ route('staff.payments.store') }}" method="POST" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Select Active Loan</label>
                            <select name="loan_id" required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-greenBrand">
                                <option value="">-- Choose Client Loan --</option>
                                @isset($loans)
                                    @foreach($loans as $loanOpt)
                                        @if($loanOpt->status == 'approved')
                                            <option value="{{ $loanOpt->id }}">
                                                {{ $loanOpt->client->name ?? 'Client' }} (₱{{ number_format($loanOpt->amount_requested ?? $loanOpt->amount, 2) }})
                                            </option>
                                        @endif
                                    @endforeach
                                @endisset
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Amount Paid (₱)</label>
                            <input type="number" step="0.01" name="amount_paid" placeholder="e.g. 2187.50" required 
                                   class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-greenBrand">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Month Number / Installment</label>
                            <input type="number" name="month_number" value="1" min="1" required 
                                   class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-greenBrand">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Reference / Receipt Number</label>
                            <input type="text" name="reference_no" placeholder="Cash Receipt # / Ref No." 
                                   class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-greenBrand">
                        </div>

                        <button type="submit" class="w-full py-3.5 bg-greenBrand text-white font-extrabold rounded-xl shadow-lg shadow-green-500/20 hover:bg-green-600 transition text-sm uppercase tracking-wider">
                            Submit Cash Payment
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="text-center py-8 text-slate-400 text-xs border-t border-slate-100 mt-12">
        &copy; 2026 MyLoan System. All rights reserved.
    </footer>
</body>
</html>