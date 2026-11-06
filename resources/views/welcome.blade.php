<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyLoan - Fast & Reliable Personal Loans</title>
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
<body class="bg-slate-50 text-darkBrand antialiased">

    <!-- Navbar -->
    <header class="flex justify-between items-center px-8 py-6 bg-white shadow-sm sticky top-0 z-50">
        <div class="flex items-center gap-3">
            <div class="text-2xl font-extrabold text-orangeBrand tracking-wide">
                MY<span class="text-greenBrand">LOAN</span>
            </div>
            <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full uppercase tracking-wider">
                Fast Approval
            </span>
        </div>
        <div class="flex items-center gap-3">
            @auth
                <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 rounded-xl bg-orangeBrand text-white font-bold shadow-md hover:bg-orange-600 transition text-sm">
                    Go to Dashboard
                </a>
            @else
                <!-- Role-Specific Login Buttons -->
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mr-1 hidden md:inline">Portal Login:</span>
                <a href="{{ url('/login?role=client') }}" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 font-semibold text-xs hover:bg-slate-200 transition">
                    Client Login
                </a>
                <a href="{{ url('/login?role=staff') }}" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 font-semibold text-xs hover:bg-slate-200 transition">
                    Staff Login
                </a>
                <a href="{{ url('/login?role=admin') }}" class="px-4 py-2 rounded-xl bg-darkBrand text-white font-bold text-xs hover:bg-slate-800 transition shadow-sm">
                    Admin Login
                </a>
            @endauth
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-6 py-8 space-y-10">

        <!-- Slideshow Banner Section with Background Images -->
        <div class="relative w-full h-[400px] rounded-3xl overflow-hidden shadow-2xl bg-slate-900">
            
            <!-- Slide 1 -->
            <div class="absolute inset-0 transition-opacity duration-1000 ease-in-out slide opacity-100 flex items-center bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=1600&auto=format&fit=crop');">
                <!-- Dark gradient overlay for readability -->
                <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-950/80 to-transparent"></div>
                <div class="relative z-10 px-10 md:px-16 max-w-xl space-y-4">
                    <span class="text-orangeBrand font-bold uppercase tracking-widest text-xs px-3 py-1 bg-orange-950/60 rounded-full border border-orange-500/30">Featured Offer</span>
                    <h2 class="text-3xl md:text-5xl font-extrabold text-white leading-tight">
                        Protect Your Investment, Protect Your Home.
                    </h2>
                    <p class="text-slate-300 text-sm md:text-base">
                        Fast, reliable personal loans designed to support your family's financial future and home improvement goals.
                    </p>
                    <div>
                        <a href="#apply-form" class="inline-block px-8 py-3 bg-red-600 text-white font-extrabold rounded-full shadow-lg hover:bg-red-700 transition tracking-wider text-sm uppercase">
                            APPLY NOW!
                        </a>
                    </div>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="absolute inset-0 transition-opacity duration-1000 ease-in-out slide opacity-0 flex items-center bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1560518883-ce09059eeffa?q=80&w=1600&auto=format&fit=crop');">
                <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-950/80 to-transparent"></div>
                <div class="relative z-10 px-10 md:px-16 max-w-xl space-y-4">
                    <span class="text-greenBrand font-bold uppercase tracking-widest text-xs px-3 py-1 bg-emerald-950/60 rounded-full border border-green-500/30">Low Interest Rates</span>
                    <h2 class="text-3xl md:text-5xl font-extrabold text-white leading-tight">
                        Achieve Your Dreams With 5% Base Rates.
                    </h2>
                    <p class="text-slate-300 text-sm md:text-base">
                        Transparent amortization terms with zero hidden fees, flexible terms, and hassle-free calculation.
                    </p>
                    <div>
                        <a href="#apply-form" class="inline-block px-8 py-3 bg-orangeBrand text-white font-extrabold rounded-full shadow-lg hover:bg-orange-600 transition tracking-wider text-sm uppercase">
                            GET STARTED
                        </a>
                    </div>
                </div>
            </div>

            <!-- Slide 3 -->
            <div class="absolute inset-0 transition-opacity duration-1000 ease-in-out slide opacity-0 flex items-center bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1556761175-5973dc0f32e7?q=80&w=1600&auto=format&fit=crop');">
                <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-950/80 to-transparent"></div>
                <div class="relative z-10 px-10 md:px-16 max-w-xl space-y-4">
                    <span class="text-yellow-400 font-bold uppercase tracking-widest text-xs px-3 py-1 bg-yellow-950/60 rounded-full border border-yellow-500/30">Quick Approval</span>
                    <h2 class="text-3xl md:text-5xl font-extrabold text-white leading-tight">
                        Cash Disbursement Within 24 Hours.
                    </h2>
                    <p class="text-slate-300 text-sm md:text-base">
                        Government employee or private sector worker? Enjoy tailored packages and fast verification.
                    </p>
                    <div>
                        <a href="#apply-form" class="inline-block px-8 py-3 bg-greenBrand text-white font-extrabold rounded-full shadow-lg hover:bg-emerald-600 transition tracking-wider text-sm uppercase">
                            APPLY TODAY
                        </a>
                    </div>
                </div>
            </div>

            <!-- Slide Navigation Dots -->
            <div class="absolute bottom-5 left-1/2 transform -translate-x-1/2 z-20 flex space-x-3">
                <button onclick="currentSlide(0)" class="w-3.5 h-3.5 rounded-full bg-white/50 hover:bg-white dot transition shadow"></button>
                <button onclick="currentSlide(1)" class="w-3.5 h-3.5 rounded-full bg-white/50 hover:bg-white dot transition shadow"></button>
                <button onclick="currentSlide(2)" class="w-3.5 h-3.5 rounded-full bg-white/50 hover:bg-white dot transition shadow"></button>
            </div>
        </div>

        <!-- Grid Section: Calculator & Form -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start" id="apply-form">
            
            <!-- Left Column: Info & Interactive Loan Calculator (5 Cols) -->
            <div class="lg:col-span-5 space-y-8">
                <div>
                    <h1 class="text-4xl font-extrabold text-darkBrand leading-tight">
                        Quick Financial Solutions Tailored For You. <span class="text-orangeBrand">Upto 200K Loanable Amount Available!</span>
                    </h1>
                    <p class="mt-4 text-slate-600 text-base leading-relaxed">
                        Apply for a personal loan in minutes. Explore transparent terms, compute your amortization instantly, and take control of your financial goals.
                    </p>
                </div>

                <!-- Interactive Loan Calculator Widget -->
                <div class="bg-white p-6 rounded-3xl shadow-xl border border-slate-100">
                    <h3 class="text-lg font-extrabold text-darkBrand mb-4 flex items-center justify-between">
                        <span>Loan Calculator</span>
                        <span class="text-xs px-2.5 py-1 bg-orange-100 text-orange-700 rounded-full font-bold">Estimate</span>
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Loan Amount (₱)</label>
                            <input type="number" id="calcAmount" value="25000" min="1000" max="500000" step="1000" oninput="calculateLoan()" class="w-full p-3 bg-slate-50 border rounded-xl font-bold text-slate-800 outline-none focus:ring-2 focus:ring-orangeBrand text-sm">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Term (Months): <span id="termVal" class="text-orangeBrand font-bold">12</span> mos</label>
                            <input type="range" id="calcTerm" min="3" max="36" step="3" value="12" oninput="calculateLoan()" class="w-full accent-orangeBrand cursor-pointer">
                        </div>

                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 space-y-2 mt-4">
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-500">Interest Rate:</span>
                                <span class="font-bold text-slate-700">5.0% Base</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-500">Estimated Monthly:</span>
                                <span class="font-extrabold text-green-600 text-lg" id="monthlyResult">₱0.00</span>
                            </div>
                            <div class="flex justify-between text-sm border-t pt-2">
                                <span class="text-slate-500">Total Payable:</span>
                                <span class="font-extrabold text-darkBrand" id="totalResult">₱0.00</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Registration & Loan Application Form (7 Cols) -->
            <div class="lg:col-span-7 bg-white p-8 lg:p-10 rounded-3xl shadow-2xl border border-slate-100">
                <h2 class="text-2xl font-extrabold text-darkBrand mb-2">Apply for a Loan Now</h2>
                <p class="text-slate-500 text-sm mb-6">Create your client account and submit your initial loan application instantly.</p>

                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-200 text-green-700 rounded-xl font-semibold text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-200 text-red-700 rounded-xl font-semibold text-sm">
                        <ul class="list-disc pl-4 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('client.apply') }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- Hidden Inputs synced with the Loan Calculator -->
                    <input type="hidden" name="amount" id="formAmount" value="25000">
                    <input type="hidden" name="term_months" id="formTerm" value="12">

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Full Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Juan Dela Cruz" class="w-full p-3 bg-slate-50 border rounded-xl focus:ring-2 focus:ring-orangeBrand outline-none text-sm">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Email Address</label>
                            <input type="email" name="email" value="{{ old('email') }}" required placeholder="juan@example.com" class="w-full p-3 bg-slate-50 border rounded-xl focus:ring-2 focus:ring-orangeBrand outline-none text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Username</label>
                            <input type="text" name="username" value="{{ old('username') }}" required placeholder="juandelacruz" class="w-full p-3 bg-slate-50 border rounded-xl focus:ring-2 focus:ring-orangeBrand outline-none text-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Password</label>
                            <input type="password" name="password" required placeholder="••••••••" class="w-full p-3 bg-slate-50 border rounded-xl focus:ring-2 focus:ring-orangeBrand outline-none text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Work Status</label>
                            <input type="text" name="work_status" value="{{ old('work_status') }}" required placeholder="e.g. Government Employee" class="w-full p-3 bg-slate-50 border rounded-xl focus:ring-2 focus:ring-orangeBrand outline-none text-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Monthly Income (₱)</label>
                        <input type="number" step="0.01" name="monthly_income" value="{{ old('monthly_income') }}" required placeholder="50000" class="w-full p-3 bg-slate-50 border rounded-xl focus:ring-2 focus:ring-orangeBrand outline-none text-sm">
                    </div>

                    <button type="submit" class="w-full py-4 bg-orangeBrand text-white font-bold rounded-xl shadow-lg hover:bg-orange-600 transition tracking-wide text-sm mt-2">
                        Submit Loan Application
                    </button>
                    <p class="text-slate-500 font-extrabold">All loan applicants will be contacted via email for verification, document requirements, and C.I. scheduling.</p>
                </form>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="text-center py-8 text-slate-400 text-xs border-t border-slate-100 mt-12">
        &copy; 2026 MyLoan System. All rights reserved.
    </footer>

    <!-- Calculator & Slideshow Script -->
    <script>
       // Loan Calculator Logic
function calculateLoan() {
    let amount = parseFloat(document.getElementById('calcAmount').value) || 0;

    // Limit the maximum loan amount to ₱200,000
    if (amount > 200000) {
        amount = 200000;
        document.getElementById('calcAmount').value = 200000; // Automatically updates the input box visually
    }

    const term = parseInt(document.getElementById('calcTerm').value) || 12;
    
    document.getElementById('termVal').innerText = term;
    document.getElementById('formAmount').value = amount;
    document.getElementById('formTerm').value = term;

    const interestRate = 0.05;
    const totalInterest = amount * interestRate * (term / 12);
    const totalPayable = amount + totalInterest;
    const monthlyPayment = totalPayable / term;

    document.getElementById('monthlyResult').innerText = '₱' + monthlyPayment.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('totalResult').innerText = '₱' + totalPayable.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
}
        // Slideshow Logic
        let slideIndex = 0;
        const slides = document.querySelectorAll('.slide');
        const dots = document.querySelectorAll('.dot');

        function showSlide(n) {
            slides.forEach((slide, i) => {
                slide.style.opacity = i === n ? '1' : '0';
            });
            dots.forEach((dot, i) => {
                dot.style.backgroundColor = i === n ? '#FF6B00' : 'rgba(255, 255, 255, 0.5)';
            });
            slideIndex = n;
        }

        function nextSlide() {
            slideIndex = (slideIndex + 1) % slides.length;
            showSlide(slideIndex);
        }

        function currentSlide(n) {
            showSlide(n);
        }

        // Initialize on load
        window.onload = function() {
            calculateLoan();
            showSlide(0);
            setInterval(nextSlide, 5000); // Change slide every 5 seconds
        };
    </script>
</body>
</html>