<?php

namespace App\Http\Controllers;

use App\Models\InsuranceCategory;
use App\Models\InsuranceProduct;
use App\Models\UserInsurance;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InsuranceController extends Controller
{
    /**
     * Display the insurance products listing page.
     */
    public function index()
    {
        $categories = InsuranceCategory::where('is_active', true)
            ->orderBy('display_order')
            ->get();

        $featuredProducts = InsuranceProduct::where('is_featured', true)
            ->where('is_active', true)
            ->with('category')
            ->get();

        return Inertia::render('Insurance/Index', [
            'categories' => $categories,
            'featuredProducts' => $featuredProducts
        ]);
    }

    /**
     * Display insurance products by category.
     */
    public function category($slug)
    {
        $category = InsuranceCategory::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $products = InsuranceProduct::where('category_id', $category->id)
            ->where('is_active', true)
            ->get();

        return Inertia::render('Insurance/Category', [
            'category' => $category,
            'products' => $products
        ]);
    }

    /**
     * Display the details of a specific insurance product.
     */
    public function show($id)
    {
        $product = InsuranceProduct::with('category')
            ->findOrFail($id);

        // Get user vehicles for auto insurance
        $vehicles = [];
        if ($product->category->slug === 'auto') {
            $vehicles = Vehicle::where('user_id', auth()->id())->get();
        }

        return Inertia::render('Insurance/Product', [
            'product' => $product,
            'vehicles' => $vehicles
        ]);
    }

    /**
     * Show the checkout page for an insurance product.
     */
    public function checkout($id, Request $request)
    {
        $product = InsuranceProduct::with('category')->findOrFail($id);

        // Get vehicle if provided (for auto insurance)
        $vehicle = null;
        if ($request->has('vehicle') && $product->category->slug === 'auto') {
            $vehicle = Vehicle::where('user_id', auth()->id())
                ->findOrFail($request->vehicle);
        }

        return Inertia::render('Insurance/Checkout', [
            'product' => $product,
            'vehicle' => $vehicle
        ]);
    }

    /**
     * Process the insurance purchase.
     */
    public function purchase(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:insurance_products,id',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'payment_method' => 'required|string',
            'card_number' => 'required_if:payment_method,credit_card',
            'card_holder' => 'required_if:payment_method,credit_card',
            'expiry_month' => 'required_if:payment_method,credit_card',
            'expiry_year' => 'required_if:payment_method,credit_card',
            'cvv' => 'required_if:payment_method,credit_card',
        ]);

        $product = InsuranceProduct::findOrFail($validated['product_id']);

        // Create insurance policy
        $policy = UserInsurance::create([
            'user_id' => auth()->id(),
            'insurance_product_id' => $product->id,
            'policy_number' => 'HAP-' . strtoupper(substr($product->category->slug, 0, 3)) . '-' . rand(100000, 999999),
            'price_paid' => $product->base_price,
            'starts_at' => now(),
            'expires_at' => now()->addDays($product->duration_days),
            'status' => 'active',
            'policy_data' => json_encode([]),
            'vehicle_id' => $validated['vehicle_id'] ?? null,
        ]);

        // Record transaction
        auth()->user()->transactions()->create([
            'transaction_type' => 'insurance_purchase',
            'reference_id' => $policy->id,
            'amount' => $product->base_price,
            'payment_method' => $validated['payment_method'],
            'payment_status' => 'completed',
            'transaction_id' => 'TRX-' . rand(100000, 999999),
        ]);

        // Award HappiCoins
        $membershipTier = auth()->user()->membership_tier;
        $coinMultiplier = 1;

        if ($membershipTier === 'silver') {
            $coinMultiplier = 2;
        } elseif ($membershipTier === 'gold') {
            $coinMultiplier = 3;
        } elseif ($membershipTier === 'platinum') {
            $coinMultiplier = 5;
        }

        $happiCoins = $product->happi_coins_reward * $coinMultiplier;

        auth()->user()->coinTransactions()->create([
            'amount' => $happiCoins,
            'transaction_type' => 'earned',
            'reference_id' => $policy->id,
            'description' => 'Earned from purchasing ' . $product->name,
            'expires_at' => now()->addYear(),
        ]);

        auth()->user()->increment('happi_coins', $happiCoins);

        return redirect()->route('profile.index')
            ->with('success', 'Insurance policy purchased successfully.');
    }

    /**
     * Show the renewal page for an insurance policy.
     */
    public function renew($id)
    {
        $policy = UserInsurance::with(['insuranceProduct', 'vehicle'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        return Inertia::render('Insurance/Renew', [
            'policy' => $policy
        ]);
    }

    /**
     * Process insurance policy renewal.
     */
    public function processRenewal($id, Request $request)
    {
        $policy = UserInsurance::with('insuranceProduct')
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        $validated = $request->validate([
            'payment_method' => 'required|string',
            'card_number' => 'required_if:payment_method,credit_card',
            'card_holder' => 'required_if:payment_method,credit_card',
            'expiry_month' => 'required_if:payment_method,credit_card',
            'expiry_year' => 'required_if:payment_method,credit_card',
            'cvv' => 'required_if:payment_method,credit_card',
        ]);

        // Update policy expiration
        $policy->update([
            'expires_at' => now()->addDays($policy->insuranceProduct->duration_days),
            'status' => 'active',
        ]);

        // Record transaction
        auth()->user()->transactions()->create([
            'transaction_type' => 'insurance_renewal',
            'reference_id' => $policy->id,
            'amount' => $policy->insuranceProduct->base_price,
            'payment_method' => $validated['payment_method'],
            'payment_status' => 'completed',
            'transaction_id' => 'TRX-' . rand(100000, 999999),
        ]);

        // Award HappiCoins
        $membershipTier = auth()->user()->membership_tier;
        $coinMultiplier = 1;

        if ($membershipTier === 'silver') {
            $coinMultiplier = 2;
        } elseif ($membershipTier === 'gold') {
            $coinMultiplier = 3;
        } elseif ($membershipTier === 'platinum') {
            $coinMultiplier = 5;
        }

        $happiCoins = $policy->insuranceProduct->happi_coins_reward * $coinMultiplier;

        auth()->user()->coinTransactions()->create([
            'amount' => $happiCoins,
            'transaction_type' => 'earned',
            'reference_id' => $policy->id,
            'description' => 'Earned from renewing ' . $policy->insuranceProduct->name,
            'expires_at' => now()->addYear(),
        ]);

        auth()->user()->increment('happi_coins', $happiCoins);

        return redirect()->route('profile.index')
            ->with('success', 'Insurance policy renewed successfully.');
    }
}
