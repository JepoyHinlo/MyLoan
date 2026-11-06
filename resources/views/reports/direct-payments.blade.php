<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Direct Payment Monitoring Report</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-900 antialiased">

    <div class="max-w-7xl mx-auto px-6 py-10">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900">Direct Payment Monitoring Report</h1>
                <p class="text-sm text-slate-500">Tracking physical cash collections and direct payments made to staff.</p>
            </div>
            <a href="{{ url('/dashboard') }}" class="px-4 py-2 bg-slate-800 text-white font-bold rounded-xl text-xs hover:bg-slate-700 transition">
                Back to Dashboard
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 bg-emerald-100 text-emerald-700 rounded-xl font-semibold text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-100 text-slate-600 text-xs uppercase tracking-wider">
                        <th class="p-4 font-bold">Date</th>
                        <th class="p-4 font-bold">Client Name</th>
                        <th class="p-4 font-bold">Collector Staff</th>
                        <th class="p-4 font-bold">Amount Paid</th>
                        <th class="p-4 font-bold">Method</th>
                        <th class="p-4 font-bold">Reference / Notes</th>
                        <th class="p-4 font-bold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($payments as $payment)
                        <tr>
                            <td class="p-4 text-slate-600">{{ $payment->payment_date }}</td>
                            <td class="p-4 font-bold text-slate-800">{{ $payment->client->name ?? 'N/A' }}</td>
                            <td class="p-4 text-slate-600">{{ $payment->staff->name ?? 'N/A' }}</td>
                            <td class="p-4 font-extrabold text-emerald-600">₱{{ number_format($payment->amount_paid, 2) }}</td>
                            <td class="p-4 text-slate-600">{{ $payment->payment_method }}</td>
                            <td class="p-4 text-slate-500 text-xs">{{ $payment->reference_number ?? $payment->remarks ?? 'None' }}</td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-6 text-center text-slate-400">No direct payment records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $payments->links() }}
        </div>
    </div>

</body>
</html>