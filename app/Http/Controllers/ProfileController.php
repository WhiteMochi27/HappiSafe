<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\UserInsurance;
use App\Models\Vehicle;
use App\Models\FamilyGroup;
use App\Models\FamilyMember;
use App\Models\FamilyInvitation;
use App\Models\HappiCoin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function index()
    {
        // Get user's insurance policies
        $insurances = UserInsurance::with(['insuranceProduct', 'vehicle'])
            ->where('user_id', auth()->id())
            ->orderBy('expires_at', 'desc')
            ->get();

        // Get user's vehicles
        $vehicles = Vehicle::where('user_id', auth()->id())->get();

        // Get user's family information
        $ownedFamilyGroup = FamilyGroup::where('owner_id', auth()->id())->first();

        $memberFamilyGroup = null;
        if (!$ownedFamilyGroup) {
            $memberFamilyGroup = FamilyGroup::whereHas('members', function ($query) {
                $query->where('user_id', auth()->id());
            })->first();
        }

        $familyGroup = $ownedFamilyGroup ?? $memberFamilyGroup;

        $familyMembers = [];
        $familyInvitations = [];

        if ($familyGroup) {
            // Get family members
            $familyMembers = FamilyMember::where('family_group_id', $familyGroup->id)
                ->with('user:id,name,email')
                ->get();

            // Get pending invitations (only for owner)
            // if ($ownedFamilyGroup) {
            //     $familyInvitations = FamilyInvitation::where('family_group_id', $familyGroup->id)
            //         ->where('status', 'pending')
            //         ->get();
            // }
        }

        // Get recent transactions
        $transactions = Transaction::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get HappiCoins transactions
        $coinTransactions = HappiCoin::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return Inertia::render('Profile/Index', [
            'user' => auth()->user(),
            'insurances' => $insurances,
            'vehicles' => $vehicles,
            'familyGroups' => $familyGroup ? [$familyGroup] : [],
            'familyMembers' => $familyMembers,
            'familyInvitations' => $familyInvitations,
            'transactions' => $transactions,
            'coinTransactions' => $coinTransactions,
        ]);
    }

    /**
     * Show the form for editing the user's profile.
     */
    public function edit()
    {
        return Inertia::render('Profile/Edit', [
            'user' => auth()->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore(auth()->id())],
            'phone_number' => 'nullable|string|max:20',
        ]);

        auth()->user()->update($validated);

        return redirect()->route('profile.edit')
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password',
        ]);

        $user = auth()->user();

        // Logout
        auth()->logout();

        // Delete user
        $user->delete();

        // Invalidate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Display the user's transaction history.
     */
    public function transactions()
    {
        $transactions = Transaction::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $coinTransactions = HappiCoin::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return Inertia::render('Profile/Transactions', [
            'transactions' => $transactions,
            'coinTransactions' => $coinTransactions,
        ]);
    }
}
