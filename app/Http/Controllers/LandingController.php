<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Loan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LandingController extends Controller
{
    /**
     * Display the public welcome landing page.
     */
    public function index()
    {
        return view('welcome');
    }

    /**
     * Store a new client registration and loan application from the landing page.
     */
    public function storeApplication(Request $request)
    {
        // 1. Validate incoming form data (including user profile and dynamic loan details)
        $validated = $request->validate([
            'name'               => ['required', 'string', 'max:255'],
            'email'              => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'username'           => ['required', 'string', 'max:255', 'unique:users'],
            'password'           => ['required', 'string', 'min:6'],
            'work_status'        => ['required', 'string', 'max:255'],
            'monthly_income'     => ['required', 'numeric', 'min:0'],
            'amount'             => ['required', 'numeric', 'min:1'], // Captures user's selected loan amount
            'term_months'        => ['required', 'integer', 'min:1'], // Captures user's selected term
            'interest_rate'      => ['nullable', 'numeric', 'min:0'], // Optional override
        ]);

        // 2. Create the new Client User account
        $user = User::create([
            'name'           => $validated['name'],
            'email'          => $validated['email'],
            'username'       => $validated['username'],
            'password'       => Hash::make($validated['password']),
            'role'           => 'client',
            'work_status'    => $validated['work_status'],
            'monthly_income' => $validated['monthly_income'],
        ]);

        // 3. Resolve a default loan product ID (fallback to 1 if table is empty)
        $defaultProductId = DB::table('loan_products')->value('id') ?? 1;

        // 4. Assign dynamic values from the validated request
        $amount = $validated['amount'];
        $termMonths = $validated['term_months'];
        $interestRate = $validated['interest_rate'] ?? 5.00; // Default to 5.00% if not specified
        
        // Corrected calculation syntax (added missing multiplication `*` operator)
        $totalPayable = $amount + ($amount * ($interestRate / 100));
        $monthlyInstallment = $totalPayable / $termMonths;

        // 5. Create the corresponding Loan application record
        Loan::create([
            'client_id'           => $user->id,
            'loan_product_id'     => $defaultProductId,
            'amount'              => $amount,
            'amount_requested'    => $amount,
            'term_months'         => $termMonths,
            'interest_rate'       => $interestRate,
            'total_payable'       => $totalPayable,
            'monthly_installment' => $monthlyInstallment,
            'status'              => 'pending',
            'monthly_income'      => $validated['monthly_income'],
            'work_status'         => $validated['work_status'],
        ]);

        // 6. Automatically log the client in
        Auth::login($user);

        // 7. Redirect to dashboard gateway with a success message
        return redirect()->route('dashboard')->with('success', 'Your account and loan application have been successfully submitted!');
    }
}