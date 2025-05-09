<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\UserInsurance;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display the user's dashboard.
     */
    public function index()
    {
        // Get active insurance policies
        $insurancePolicies = UserInsurance::with(['insuranceProduct', 'vehicle'])
            ->where('user_id', auth()->id())
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->take(3)
            ->get();

        // Get upcoming expirations (policies expiring in the next 30 days)
        $upcomingExpirations = UserInsurance::with(['insuranceProduct'])
            ->where('user_id', auth()->id())
            ->where('status', 'active')
            ->whereBetween('expires_at', [now(), now()->addDays(30)])
            ->get();

        // Get recent notifications
        $notifications = Notification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return Inertia::render('Dashboard', [
            'insurancePolicies' => $insurancePolicies,
            'upcomingExpirations' => $upcomingExpirations,
            'notifications' => $notifications,
        ]);
    }

    /**
     * Display the user's notifications.
     */
    public function notifications()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return Inertia::render('Notifications/Index', [
            'notifications' => $notifications,
        ]);
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', auth()->id())
            ->findOrFail($id);

        $notification->update(['is_read' => true]);

        return back()->with('success', 'Notification marked as read.');
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return back()->with('success', 'All notifications marked as read.');
    }
}
