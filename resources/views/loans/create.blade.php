<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Loan / Re-loan - MyLoan</title>
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
                Loan Application Portal
            </span>
        </div>
        <div class="flex items-center gap-4">
            <a href="{{ route('client.dashboard') }}" class="text-sm font-bold text-slate-600 hover:text-orangeBrand transition">
                ← Back to Dashboard
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 font-semibold text-xs hover:bg-slate-200 transition">
                    Sign Out
                </button>
            </form>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-6 py-10 w-full flex-grow">
        
        <div class="bg-gradient-to-r from-orangeBrand to-greenBrand p-8 rounded-3xl text-white shadow-lg mb-8">
            <h1 class="text-3xl font-extrabold">New Loan / Re-loan Application</h1>
            <p class="mt-2 text-white/90 text-sm">
                Configure your desired loan amount and term. The maximum loanable amount is capped at ₱200,000.
            </p>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-100 border border-red-200 text-red-700 rounded-2xl text-sm font-semibold">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-8">
            <form action="{{ route('loans.store') }}" method="POST" oninput="calculateLoan()">
                @csrf

                <!-- Loan Amount Input -->
                <div class="mb-6">
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Loan Amount (₱) - Max: ₱200,000</label>
                    <input type="number" name="amount" id="calcAmount" min="1000" max="200000" step="500" value="25000" required 
                           class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl text-lg font-extrabold text-darkBrand focus:outline-none focus:ring-2 focus:ring-greenBrand">
                </div>

                <!-- Term Slider -->
                <div class="mb-8">
                    <div class="flex justify-between items-center mb-2">
                        <label class="text-xs font-bold text-slate-500 uppercase">Term (Months)</label>
                        <span class="text-sm font-extrabold text-orangeBrand"><span id="termVal">12</span> Months</span>
                    </div>
                    <input type="range" name="term_months" id="calcTerm" min="1" max="36" value="12" step="1" 
                           class="w-full accent-orangeBrand cursor-pointer">
                </div>

                <!-- Calculation Breakdown Box -->
                <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <span class="text-xs font-bold uppercase text-slate-400 block mb-1">Estimated Monthly Installment</span>
                        <div id="monthlyResult" class="text-2xl font-extrabold text-greenBrand">₱2,187.50</div>
                    </div>
                    <div>
                        <span class="text-xs font-bold uppercase text-slate-400 block mb-1">Total Payable Amount</span>
                        <div id="totalResult" class="text-2xl font-extrabold text-darkBrand">₱26,250.00</div>
                    </div>
                </div>

                <!-- Hidden inputs synced with calculator -->
                <input type="hidden" name="amount_requested" id="formAmount" value="25000">

                <button type="submit" class="w-full py-4 bg-gradient-to-r from-orangeBrand to-greenBrand text-white font-extrabold rounded-2xl shadow-lg hover:opacity-95 transition uppercase tracking-wider text-sm">
                    Submit Loan Application
                </button>
            </form>
        </div>
    </main>

    <!-- Footer -->
    <footer class="text-center py-8 text-slate-400 text-xs border-t border-slate-100 mt-12">
        &copy; 2026 MyLoan System. All rights reserved.
    </footer>

    <!-- Calculator Script with ₱200,000 Cap -->
    <script>
        function calculateLoan() {
            let amount = parseFloat(document.getElementById('calcAmount').value) || 0;

            // Enforce ₱200,000 maximum ceiling
            if (amount > 200000) {
                amount = 200000;
                document.getElementById('calcAmount').value = 200000;
            }

            const term = parseInt(document.getElementById('calcTerm').value) || 12;
            
            document.getElementById('termVal').innerText = term;
            document.getElementById('formAmount').value = amount;

            const interestRate = 0.05; // 5% base rate
            const totalInterest = amount * interestRate * (term / 12);
            const totalPayable = amount + totalInterest;
            const monthlyPayment = totalPayable / term;

            document.getElementById('monthlyResult').innerText = '₱' + monthlyPayment.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            document.getElementById('totalResult').innerText = '₱' + totalPayable.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        }

        // Run on load
        window.onload = calculateLoan;
    </script>
</body>
</html>