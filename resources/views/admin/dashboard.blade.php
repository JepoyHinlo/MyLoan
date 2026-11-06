<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Command Center - MyLoan System</title>
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
<body class="bg-slate-50 text-darkBrand antialiased flex flex-col min-h-screen justify-between">

    <!-- Navbar -->
    <header class="flex justify-between items-center px-8 py-6 bg-white shadow-sm sticky top-0 z-50">
        <div class="flex items-center gap-3">
            <div class="text-2xl font-extrabold text-orangeBrand tracking-wide">
                MY<span class="text-greenBrand">LOAN</span>
            </div>
            <span class="px-3 py-1 bg-darkBrand text-white text-xs font-bold rounded-full uppercase tracking-wider">
                Admin Portal
            </span>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-sm font-bold text-slate-700">Admin: {{ auth()->user()->name }}</span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 font-semibold text-xs hover:bg-slate-200 transition">
                    Logout
                </button>
            </form>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-6 py-12 w-full flex-grow space-y-8">
        
        <!-- Command Center Banner -->
        <div class="p-8 bg-darkBrand rounded-3xl shadow-2xl text-white">
            <h1 class="text-3xl font-extrabold">Admin Command Center</h1>
            <p class="mt-2 text-slate-400 text-sm">
                Oversee analytics, view automated staff assignments, grant final loan approvals, and configure product interest rates across the platform.
            </p>
        </div>

        @if(session('success'))
            <div class="p-4 bg-green-100 border border-green-200 text-green-700 rounded-xl font-semibold text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 bg-red-100 border border-red-200 text-red-700 rounded-xl font-semibold text-sm">
                <ul class="list-disc pl-4 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Analytics Metrics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-3xl shadow-xl border border-slate-100">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Applications</span>
                <div class="text-4xl font-extrabold text-darkBrand mt-2">{{ $totalLoansCount ?? 0 }}</div>
            </div>
            <div class="bg-white p-6 rounded-3xl shadow-xl border border-slate-100">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Approved Value</span>
                <div class="text-4xl font-extrabold text-greenBrand mt-2">₱{{ number_format($totalDisbursed ?? 0, 2) }}</div>
            </div>
            <div class="bg-white p-6 rounded-3xl shadow-xl border border-slate-100">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Active / Pending Pipeline</span>
                <div class="text-4xl font-extrabold text-orangeBrand mt-2">{{ $totalPendingCI ?? 0 }}</div>
            </div>
        </div>

        <!-- MAXIMUM AREA LAYOUT: Left Side (Direct Payment Analytics) & Right Side (Management Tools) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- LEFT SIDE (7 Columns): Direct Payment Monitoring Analytics -->
            <div class="lg:col-span-7 space-y-6">
                <div class="flex justify-between items-center bg-white p-6 rounded-3xl shadow-xl border border-slate-100">
                    <div>
                        <h2 class="text-base font-extrabold text-darkBrand">Direct Payment Analytics & Reports</h2>
                        <p class="text-xs text-slate-400">Overview of physical cash & direct collections.</p>
                    </div>
                    <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full">
                        Live
                    </span>
                </div>

                <!-- Top Summary Mini Cards -->
                <div class="grid grid-cols-3 gap-4">
                    <div class="bg-white p-4 rounded-2xl shadow-lg border border-slate-100">
                        <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Collected</div>
                        <div class="text-base font-extrabold text-emerald-600 mt-1">
                            ₱{{ number_format($totalDirectCollected ?? 0, 2) }}
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-2xl shadow-lg border border-slate-100">
                        <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Transactions</div>
                        <div class="text-base font-extrabold text-slate-800 mt-1">
                            {{ $totalTransactions ?? 0 }} Logs
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-2xl shadow-lg border border-slate-100">
                        <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Collectors</div>
                        <div class="text-base font-extrabold text-slate-800 mt-1">
                            {{ isset($paymentsByStaff) ? $paymentsByStaff->count() : 0 }} Active
                        </div>
                    </div>
                </div>

                <!-- Breakdown by Staff Member -->
                <div class="bg-white p-6 rounded-3xl shadow-xl border border-slate-100 space-y-4">
                    <h3 class="text-xs font-extrabold text-darkBrand uppercase tracking-wider">Collections By Staff Member</h3>
                    <div class="space-y-2 max-h-48 overflow-y-auto">
                        @forelse($paymentsByStaff ?? [] as $stat)
                            <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex justify-between items-center text-xs">
                                <div>
                                    <span class="font-extrabold text-slate-800 block">{{ $stat->staff->name ?? 'Unknown Staff' }}</span>
                                    <span class="text-slate-400 text-[10px]">{{ $stat->count }} payments processed</span>
                                </div>
                                <span class="font-extrabold text-emerald-600 text-sm">₱{{ number_format($stat->total, 2) }}</span>
                            </div>
                        @empty
                            <div class="p-4 text-center text-slate-400 text-xs">No collections recorded by staff yet.</div>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Direct Payment Records -->
                <div class="bg-white p-6 rounded-3xl shadow-xl border border-slate-100 space-y-4">
                    <h3 class="text-xs font-extrabold text-darkBrand uppercase tracking-wider">Recent Direct Payment Records</h3>
                    <div class="space-y-2 max-h-48 overflow-y-auto">
                        @forelse($recentPayments ?? [] as $payment)
                            <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex justify-between items-center text-xs">
                                <div>
                                    <span class="font-bold text-slate-800 block">{{ $payment->client->name ?? 'Client' }}</span>
                                    <span class="text-slate-400 text-[10px]">Staff: {{ $payment->staff->name ?? 'Staff' }} | {{ $payment->payment_date }}</span>
                                </div>
                                <div class="text-right">
                                    <span class="font-extrabold text-emerald-600 text-xs block">₱{{ number_format($payment->amount_paid, 2) }}</span>
                                    <span class="text-[9px] text-slate-400">Ref: {{ $payment->reference_number ?: 'Cash' }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="p-4 text-center text-slate-400 text-xs">No recent payment logs found.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- RIGHT SIDE (5 Columns): Add Staff Form & Interest Rate Manager -->
            <div class="lg:col-span-5 space-y-6">
                
                <!-- Add New Staff Form -->
                <div class="bg-white p-6 rounded-3xl shadow-xl border border-slate-100">
                    <h3 class="text-sm font-extrabold text-darkBrand mb-1">Add New Staff Member</h3>
                    <p class="text-[11px] text-slate-400 mb-4">Create a new loan officer account.</p>

                    <form action="{{ route('admin.staff.store') }}" method="POST" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">Full Name</label>
                            <input type="text" name="name" required placeholder="Staff Full Name" class="w-full p-2.5 bg-slate-50 border rounded-xl text-xs outline-none focus:border-orangeBrand">
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">Username</label>
                                <input type="text" name="username" required placeholder="username" class="w-full p-2.5 bg-slate-50 border rounded-xl text-xs outline-none focus:border-orangeBrand">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">Email</label>
                                <input type="email" name="email" required placeholder="staff@myloan.test" class="w-full p-2.5 bg-slate-50 border rounded-xl text-xs outline-none focus:border-orangeBrand">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">Temporary Password</label>
                            <input type="password" name="password" required placeholder="••••••••" class="w-full p-2.5 bg-slate-50 border rounded-xl text-xs outline-none focus:border-orangeBrand">
                        </div>
                        <button type="submit" class="w-full py-2.5 bg-greenBrand text-white font-bold rounded-xl shadow hover:bg-emerald-600 transition text-xs">
                            Register Staff Account
                        </button>
                    </form>
                </div>

                <!-- Interest Rate Manager -->
                @if(isset($loanProduct))
                <div class="bg-white p-6 rounded-3xl shadow-xl border border-slate-100">
                    <h3 class="text-sm font-extrabold text-darkBrand mb-1">Interest Rate Manager</h3>
                    <p class="text-[11px] text-slate-400 mb-4">Configure platform-wide base rates.</p>

                    <form action="{{ route('admin.rates.update', $loanProduct->id) }}" method="POST" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">New Loaner Rate (%)</label>
                            <input type="number" step="0.01" name="base_interest_rate" value="{{ $loanProduct->base_interest_rate }}" required class="w-full p-2.5 bg-slate-50 border rounded-xl font-bold text-xs outline-none focus:border-orangeBrand">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">Returning Client Rate (%)</label>
                            <input type="number" step="0.01" name="returning_interest_rate" value="{{ $loanProduct->returning_interest_rate }}" required class="w-full p-2.5 bg-slate-50 border rounded-xl font-bold text-xs outline-none focus:border-orangeBrand">
                        </div>
                        <button type="submit" class="w-full py-2.5 bg-darkBrand text-white font-bold rounded-xl shadow hover:bg-slate-800 transition text-xs">
                            Update Rates
                        </button>
                    </form>
                </div>
                @endif

            </div>

        </div>

        <!-- All Client Loan Applications Table -->
        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h3 class="text-lg font-extrabold text-darkBrand">All Client Loan Applications</h3>
                <span class="px-3 py-1 bg-slate-100 text-slate-600 text-xs font-bold rounded-full">
                    Total Records: {{ $loans->count() }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-400 text-xs uppercase font-bold tracking-wider border-b">
                            <th class="p-4">Client</th>
                            <th class="p-4">Amount & Term</th>
                            <th class="p-4">Assigned Staff</th>
                            <th class="p-4">C.I. Status</th>
                            <th class="p-4">Loan Status</th>
                            <th class="p-4 text-center">Actions / Control</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($loans as $loan)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="p-4">
                                    <div class="font-bold text-darkBrand">{{ $loan->client->name ?? $loan->user->name ?? 'N/A' }}</div>
                                    <div class="text-xs text-slate-400">{{ $loan->client->email ?? $loan->user->email ?? '' }}</div>
                                </td>
                                <td class="p-4">
                                    <div class="font-extrabold text-orangeBrand">₱{{ number_format($loan->amount ?? $loan->amount_requested ?? 0, 2) }}</div>
                                    <div class="text-xs text-slate-500">{{ $loan->term_months }} Months Term</div>
                                </td>
                                <td class="p-4">
                                    <!-- Automatically Assigned Staff Display (Manual Dropdown Removed) -->
                                    @if($loan->staff)
                                        <span class="px-3 py-1.5 bg-slate-100 text-darkBrand rounded-xl text-xs font-bold inline-block">
                                            {{ $loan->staff->name }}
                                        </span>
                                    @else
                                        <span class="px-3 py-1.5 bg-amber-50 text-amber-700 rounded-xl text-xs font-bold inline-block">
                                            Auto-assigning...
                                        </span>
                                    @endif
                                </td>
                                <td class="p-4">
                                    <span class="text-xs font-semibold {{ $loan->is_ci_done ? 'text-green-600' : 'text-amber-600' }}">
                                        {{ $loan->is_ci_done ? 'Completed' : 'Pending / In Progress' }}
                                    </span>
                                </td>
                                <td class="p-4">
                                    <form action="{{ route('admin.loans.status', $loan->id) }}" method="POST">
                                        @csrf
                                        <select name="status" onchange="this.form.submit()" class="p-2 text-xs font-bold uppercase rounded-xl border outline-none
                                            {{ in_array(strtolower($loan->status), ['approved', 'disbursed']) ? 'bg-green-100 text-green-700' : '' }}
                                            {{ in_array(strtolower($loan->status), ['pending']) ? 'bg-amber-100 text-amber-700' : '' }}
                                            {{ in_array(strtolower($loan->status), ['declined']) ? 'bg-red-100 text-red-700' : '' }}
                                            {{ in_array(strtolower($loan->status), ['ci_in_progress', 'ci_completed']) ? 'bg-blue-100 text-blue-700' : '' }}">
                                            <option value="pending" {{ strtolower($loan->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="ci_in_progress" {{ strtolower($loan->status) == 'ci_in_progress' ? 'selected' : '' }}>C.I. In Progress</option>
                                            <option value="ci_completed" {{ strtolower($loan->status) == 'ci_completed' ? 'selected' : '' }}>C.I. Completed</option>
                                            <option value="approved" {{ strtolower($loan->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                                            <option value="declined" {{ strtolower($loan->status) == 'declined' ? 'selected' : '' }}>Declined</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="p-4 text-center">
                                    <span class="text-xs text-slate-400 font-medium">Managed</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-12 text-center text-slate-400 font-medium">
                                    No loan applications found in the system.
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