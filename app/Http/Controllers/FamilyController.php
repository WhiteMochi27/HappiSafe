<?php

namespace App\Http\Controllers;

use App\Models\FamilyGroup;
use App\Models\FamilyInvitation;
use App\Models\FamilyMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Inertia\Inertia;

class FamilyController extends Controller
{
    /**
     * Display the family group dashboard.
     */
    public function index()
    {
        // Check if user has a family group (either as owner or member)
        $ownedGroup = FamilyGroup::where('owner_id', auth()->id())->first();

        $memberGroup = null;
        if (!$ownedGroup) {
            $memberGroup = FamilyGroup::whereHas('members', function ($query) {
                $query->where('user_id', auth()->id());
            })->first();
        }

        $familyGroup = $ownedGroup ?? $memberGroup;

        // Get family members if user has a group
        $members = [];
        $invitations = [];

        if ($familyGroup) {
            // Get all members including the owner
            $owner = User::select('id', 'name', 'email')
                ->where('id', $familyGroup->owner_id)
                ->first();

            $owner->relationship = 'self';
            $owner->is_owner = true;

            $groupMembers = FamilyMember::where('family_group_id', $familyGroup->id)
                ->with(['user:id,name,email'])
                ->get()
                ->map(function ($member) {
                    return [
                        'id' => $member->id,
                        'name' => $member->user->name,
                        'email' => $member->user->email,
                        'relationship' => $member->relationship,
                        'is_owner' => false
                    ];
                })
                ->toArray();

            // Only include owner in members array if the current user is the owner
            $members = auth()->id() === $familyGroup->owner_id
                ? array_merge([$owner], $groupMembers)
                : $groupMembers;

            // Get pending invitations (only for owner)
            if (auth()->id() === $familyGroup->owner_id) {
                $invitations = FamilyInvitation::where('family_group_id', $familyGroup->id)
                    ->where('status', 'pending')
                    ->get();
            }
        }

        return Inertia::render('Family/Index', [
            'familyGroup' => $familyGroup,
            'members' => $members,
            'invitations' => $invitations,
        ]);
    }

    /**
     * Show the form for creating a new family group.
     */
    public function create()
    {
        return Inertia::render('Family/Create');
    }

    /**
     * Store a newly created family group.
     */
    public function store(Request $request)
    {
        // Check if user already has or belongs to a family group
        $existingOwnedGroup = FamilyGroup::where('owner_id', auth()->id())->exists();
        $existingMembership = FamilyMember::where('user_id', auth()->id())->exists();

        if ($existingOwnedGroup || $existingMembership) {
            return back()->with('error', 'You already have or belong to a family group.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Create family group
        $familyGroup = FamilyGroup::create([
            'owner_id' => auth()->id(),
            'name' => $validated['name'],
        ]);

        return redirect()->route('family.index')
            ->with('success', 'Family group created successfully.');
    }

    /**
     * Display the specified family group.
     */
    public function show($id)
    {
        $familyGroup = FamilyGroup::where(function ($query) {
            $query->where('owner_id', auth()->id())
                ->orWhereHas('members', function ($q) {
                    $q->where('user_id', auth()->id());
                });
        })->findOrFail($id);

        // Get members
        $owner = User::select('id', 'name', 'email')
            ->where('id', $familyGroup->owner_id)
            ->first();

        $owner->relationship = 'self';
        $owner->is_owner = true;

        $groupMembers = FamilyMember::where('family_group_id', $familyGroup->id)
            ->with(['user:id,name,email'])
            ->get()
            ->map(function ($member) {
                return [
                    'id' => $member->id,
                    'name' => $member->user->name,
                    'email' => $member->user->email,
                    'relationship' => $member->relationship,
                    'is_owner' => false
                ];
            })
            ->toArray();

        $members = array_merge([$owner], $groupMembers);

        // Get pending invitations (only for owner)
        $invitations = [];
        if (auth()->id() === $familyGroup->owner_id) {
            $invitations = FamilyInvitation::where('family_group_id', $familyGroup->id)
                ->where('status', 'pending')
                ->get();
        }

        return Inertia::render('Family/Show', [
            'familyGroup' => $familyGroup,
            'members' => $members,
            'invitations' => $invitations,
        ]);
    }

    /**
     * Show the invite member form.
     */
    public function showInviteForm()
    {
        // Get user's family group
        $familyGroup = FamilyGroup::where('owner_id', auth()->id())->firstOrFail();

        return Inertia::render('Family/Invite', [
            'familyGroup' => $familyGroup
        ]);
    }

    /**
     * Send an invitation to join the family group.
     */
    public function invite(Request $request)
    {
        // Get user's family group
        $familyGroup = FamilyGroup::where('owner_id', auth()->id())->firstOrFail();

        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'relationship' => 'required|string|in:spouse,child,parent,sibling,other',
        ]);

        // Check if email is already a member
        $memberExists = User::where('email', $validated['email'])
            ->whereHas('familyMemberships', function ($query) use ($familyGroup) {
                $query->where('family_group_id', $familyGroup->id);
            })
            ->exists();

        if ($memberExists) {
            return back()->with('error', 'This email is already a member of your family group.');
        }

        // Check if invitation already exists
        $invitationExists = FamilyInvitation::where('family_group_id', $familyGroup->id)
            ->where('email', $validated['email'])
            ->where('status', 'pending')
            ->exists();

        if ($invitationExists) {
            return back()->with('error', 'An invitation has already been sent to this email.');
        }

        // Create invitation
        $token = Str::random(32);
        $invitation = FamilyInvitation::create([
            'family_group_id' => $familyGroup->id,
            'email' => $validated['email'],
            'token' => $token,
            'expires_at' => now()->addDays(7),
            'status' => 'pending',
        ]);

        // Store relationship for when user accepts invitation
        $invitation->relationshipType = $validated['relationship'];
        $invitation->save();

        // Send invitation email
        // Mail::to($validated['email'])->send(new FamilyInvitation($invitation, $familyGroup));

        return redirect()->route('family.index')
            ->with('success', 'Invitation sent successfully.');
    }

    /**
     * Show the invitation acceptance page.
     */
    public function join($token)
    {
        // Find and validate invitation
        $invitation = FamilyInvitation::where('token', $token)
            ->where('status', 'pending')
            ->where('expires_at', '>', now())
            ->firstOrFail();

        $familyGroup = FamilyGroup::findOrFail($invitation->family_group_id);

        // Check if user is logged in and email matches invitation
        $userMatches = auth()->check() && auth()->user()->email === $invitation->email;

        return Inertia::render('Family/Join', [
            'invitation' => $invitation,
            'familyGroup' => $familyGroup,
            'userMatches' => $userMatches,
        ]);
    }

    /**
     * Accept a family group invitation.
     */
    public function accept($token, Request $request)
    {
        // Find and validate invitation
        $invitation = FamilyInvitation::where('token', $token)
            ->where('status', 'pending')
            ->where('expires_at', '>', now())
            ->firstOrFail();

        $familyGroup = FamilyGroup::findOrFail($invitation->family_group_id);

        // Ensure user is logged in and email matches invitation
        if (!auth()->check() || auth()->user()->email !== $invitation->email) {
            return redirect()->route('login')
                ->with('error', 'You must be logged in with the invited email address to accept this invitation.');
        }

        // Check if user already belongs to a family group
        $userHasGroup = FamilyGroup::where('owner_id', auth()->id())->exists();
        $userIsMember = FamilyMember::where('user_id', auth()->id())->exists();

        if ($userHasGroup || $userIsMember) {
            $invitation->update(['status' => 'declined']);

            return redirect()->route('family.index')
                ->with('error', 'You already have or belong to a family group.');
        }

        // Create family membership
        FamilyMember::create([
            'family_group_id' => $familyGroup->id,
            'user_id' => auth()->id(),
            'relationship' => $invitation->relationshipType ?? 'other',
            'is_active' => true,
        ]);

        // Update invitation status
        $invitation->update(['status' => 'accepted']);

        return redirect()->route('family.index')
            ->with('success', 'You have joined the family group successfully.');
    }

    /**
     * Cancel a family group invitation.
     */
    public function cancelInvitation($id)
    {
        // Get user's family group
        $familyGroup = FamilyGroup::where('owner_id', auth()->id())->firstOrFail();

        // Find invitation
        $invitation = FamilyInvitation::where('id', $id)
            ->where('family_group_id', $familyGroup->id)
            ->firstOrFail();

        // Cancel invitation
        $invitation->update(['status' => 'cancelled']);

        return back()->with('success', 'Invitation cancelled successfully.');
    }

    /**
     * Update a family member.
     */
    public function updateMember(Request $request, $id)
    {
        // Get user's family group
        $familyGroup = FamilyGroup::where('owner_id', auth()->id())->firstOrFail();

        // Find member
        $member = FamilyMember::where('id', $id)
            ->where('family_group_id', $familyGroup->id)
            ->firstOrFail();

        $validated = $request->validate([
            'relationship' => 'required|string|in:spouse,child,parent,sibling,other',
        ]);

        // Update member
        $member->update([
            'relationship' => $validated['relationship'],
        ]);

        return back()->with('success', 'Member updated successfully.');
    }

    /**
     * Remove a family member.
     */
    public function removeMember($id)
    {
        // Get user's family group
        $familyGroup = FamilyGroup::where('owner_id', auth()->id())->firstOrFail();

        // Find member
        $member = FamilyMember::where('id', $id)
            ->where('family_group_id', $familyGroup->id)
            ->firstOrFail();

        // Delete member
        $member->delete();

        return back()->with('success', 'Member removed successfully.');
    }
}
