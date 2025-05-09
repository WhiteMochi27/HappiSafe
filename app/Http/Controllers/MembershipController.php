<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MembershipController extends Controller
{
    /**
     * Display the membership plans page.
     */
    public function plans()
    {
        $plans = Membership::where('is_active', true)
            ->orderBy('price')
            ->get();

        return Inertia::render('Membership/Plans', [
            'plans' => $plans,
            'currentPlan' => auth()->user()->membership_tier
        ]);
    }

    /**
     * Display details for a specific membership plan.
     */
    public function planDetails($tier)
    {
        $plan = Membership::where('tier', $tier)
            ->where('is_active', true)
            ->firstOrFail();

        return Inertia::render('Membership/Details', [
            'plan' => $plan,
            'currentPlan' => auth()->user()->membership_tier
        ]);
    }

    /**
     * Show the checkout page for a membership plan.
     */
    public function checkout($tier)
    {
        $plan = Membership::where('tier', $tier)
            ->where('is_active', true)
            ->firstOrFail();

        return Inertia::render('Membership/Checkout', [
            'plan' => $plan
        ]);
    }

    /**
     * Process membership purchase.
     */
    public function purchase(Request $request)
    {
        $validated = $request->validate([
            'plan_id' => 'required|exists:memberships,id',
            'payment_method' => 'required|string',
            'card_number' => 'required_if:payment_method,credit_card',
            'card_holder' => 'required_if:payment_method,credit_card',
            'expiry_month' => 'required_if:payment_method,credit_card',
            'expiry_year' => 'required_if:payment_method,credit_card',
            'cvv' => 'required_if:payment_method,credit_card',
            'promo_code' => 'nullable|string',
        ]);

        $plan = Membership::findOrFail($validated['plan_id']);
        $finalPrice = $plan->price;

        // Apply promo code if provided
        if (!empty($validated['promo_code'])) {
            // In a real app, you would validate the promo code and calculate the discount
            // For this example, let's assume a 10% discount for any promo code
            $discount = $plan->price * 0.1;
            $finalPrice = $plan->price - $discount;
        }

        // Record transaction
        $transaction = Transaction::create([
            'user_id' => auth()->id(),
            'transaction_type' => 'membership_purchase',
            'reference_id' => $plan->id,
            'amount' => $finalPrice,
            'payment_method' => $validated['payment_method'],
            'payment_status' => 'completed',
            'transaction_id' => 'TRX-' . rand(100000, 999999),
            'transaction_data' => json_encode([
                'promo_code' => $validated['promo_code'] ?? null,
                'original_price' => $plan->price,
                'discount' => $plan->price - $finalPrice,
            ]),
        ]);

        // Update user membership
        auth()->user()->update([
            'membership_tier' => $plan->tier,
            'membership_expires_at' => now()->addDays($plan->duration_days),
        ]);

        // Award HappiCoins
        if ($plan->happi_coins_reward > 0) {
            auth()->user()->coinTransactions()->create([
                'amount' => $plan->happi_coins_reward,
                'transaction_type' => 'earned',
                'reference_id' => $transaction->id,
                'description' => 'Earned from purchasing ' . $plan->name . ' membership',
                'expires_at' => now()->addYear(),
            ]);

            auth()->user()->increment('happi_coins', $plan->happi_coins_reward);
        }

        return redirect()->route('profile.index')
            ->with('success', 'Membership purchased successfully.');
    }
}
