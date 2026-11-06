<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - MyLoan System</title>
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

    <!-- Top Navigation -->
    <header class="flex justify-between items-center px-8 py-6 bg-white shadow-sm">
        <div class="flex items-center gap-3">
            <div class="text-2xl font-extrabold text-orangeBrand tracking-wide">
                MY<span class="text-greenBrand">LOAN</span>
            </div>
        </div>
        <div>
            <a href="{{ route('welcome') }}" class="text-sm font-semibold text-slate-600 hover:text-orangeBrand transition flex items-center gap-1">
                &larr; Back to Home
            </a>
        </div>
    </header>

    <!-- Main Login Card Container -->
    <main class="flex-grow flex items-center justify-center px-4 py-12">
        <div class="max-w-md w-full bg-white p-8 rounded-3xl shadow-2xl border border-slate-100">
            
            <div class="text-center mb-8">
                <span class="px-3 py-1 bg-orange-100 text-orange-700 text-xs font-bold rounded-full uppercase tracking-wider">
                    {{ ucfirst($role ?? 'client') }} Portal
                </span>
                <h2 class="text-3xl font-extrabold text-darkBrand mt-3">Welcome Back</h2>
                <p class="text-slate-500 text-sm mt-1">Please sign in to access your dashboard.</p>
            </div>

            @if($errors->any())
                <div class="mb-4 p-4 bg-red-100 border border-red-200 text-red-700 rounded-xl font-semibold text-sm">
                    <ul class="list-disc pl-4 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Login Form (Posts to Laravel Authentication Handler) -->
            <form action="{{ url('/login') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="role" value="{{ $role ?? 'client' }}">

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Email or Username</label>
                    <input type="text" name="email" value="{{ old('email') }}" required autofocus placeholder="Enter your email or username" class="w-full p-3 bg-slate-50 border rounded-xl focus:ring-2 focus:ring-orangeBrand outline-none text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Password</label>
                    <input type="password" name="password" required placeholder="••••••••" class="w-full p-3 bg-slate-50 border rounded-xl focus:ring-2 focus:ring-orangeBrand outline-none text-sm">
                </div>

                <div class="flex items-center justify-between text-sm py-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded text-orangeBrand focus:ring-orangeBrand">
                        <span class="text-slate-600 text-xs font-medium">Remember me</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-4 bg-orangeBrand text-white font-bold rounded-xl shadow-lg hover:bg-orange-600 transition tracking-wide text-sm mt-2">
                    Sign In as {{ ucfirst($role ?? 'client') }}
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-slate-100 text-center text-xs text-slate-400">
                Switch portal: 
                <a href="{{ url('/login?role=client') }}" class="text-orangeBrand font-semibold hover:underline mx-1">Client</a> | 
                <a href="{{ url('/login?role=staff') }}" class="text-orangeBrand font-semibold hover:underline mx-1">Staff</a> | 
                <a href="{{ url('/login?role=admin') }}" class="text-orangeBrand font-semibold hover:underline mx-1">Admin</a>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="text-center py-8 text-slate-400 text-xs border-t border-slate-100">
        &copy; 2026 MyLoan System. All rights reserved.
    </footer>

</body>
</html>