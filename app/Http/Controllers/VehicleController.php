<?php

namespace App\Http\Controllers;

use App\Models\UserInsurance;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class VehicleController extends Controller
{
    /**
     * Display a listing of the user's vehicles.
     */
    public function index()
    {
        $vehicles = Vehicle::where('user_id', auth()->id())
            ->get()
            ->map(function ($vehicle) {
                // Check if vehicle has active insurance
                $hasInsurance = UserInsurance::where('vehicle_id', $vehicle->id)
                    ->where('status', 'active')
                    ->where('expires_at', '>', now())
                    ->exists();

                $vehicle->has_insurance = $hasInsurance;
                return $vehicle;
            });

        return Inertia::render('Vehicles/Index', [
            'vehicles' => $vehicles
        ]);
    }

    /**
     * Show the form for creating a new vehicle.
     */
    public function create()
    {
        return Inertia::render('Vehicles/Create');
    }

    /**
     * Store a newly created vehicle in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|string|max:4',
            'license_plate' => 'required|string|max:20',
            'vin' => 'nullable|string|max:17',
            'color' => 'nullable|string|max:50',
            'vehicle_card_image' => 'nullable|image|max:5120', // 5MB max
        ]);

        // Process vehicle card image if uploaded
        $imagePath = null;
        if ($request->hasFile('vehicle_card_image')) {
            $imagePath = $request->file('vehicle_card_image')->store('vehicle_cards', 'public');
        }

        // Create vehicle
        $vehicle = Vehicle::create([
            'user_id' => auth()->id(),
            'make' => $validated['make'],
            'model' => $validated['model'],
            'year' => $validated['year'],
            'license_plate' => $validated['license_plate'],
            'vin' => $validated['vin'],
            'color' => $validated['color'],
            'vehicle_card_image_path' => $imagePath,
        ]);

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehicle added successfully.');
    }

    /**
     * Display the specified vehicle.
     */
    public function show($id)
    {
        $vehicle = Vehicle::where('user_id', auth()->id())
            ->findOrFail($id);

        // Get active insurance policies for this vehicle
        $insurances = UserInsurance::with('insuranceProduct')
            ->where('vehicle_id', $vehicle->id)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->get();

        return Inertia::render('Vehicles/Show', [
            'vehicle' => $vehicle,
            'insurances' => $insurances
        ]);
    }

    /**
     * Show the form for editing the specified vehicle.
     */
    public function edit($id)
    {
        $vehicle = Vehicle::where('user_id', auth()->id())
            ->findOrFail($id);

        return Inertia::render('Vehicles/Edit', [
            'vehicle' => $vehicle
        ]);
    }

    /**
     * Update the specified vehicle in storage.
     */
    public function update(Request $request, $id)
    {
        $vehicle = Vehicle::where('user_id', auth()->id())
            ->findOrFail($id);

        $validated = $request->validate([
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|string|max:4',
            'license_plate' => 'required|string|max:20',
            'vin' => 'nullable|string|max:17',
            'color' => 'nullable|string|max:50',
            'vehicle_card_image' => 'nullable|image|max:5120', // 5MB max
        ]);

        // Process vehicle card image if uploaded
        if ($request->hasFile('vehicle_card_image')) {
            // Delete old image if exists
            if ($vehicle->vehicle_card_image_path) {
                Storage::disk('public')->delete($vehicle->vehicle_card_image_path);
            }

            $imagePath = $request->file('vehicle_card_image')->store('vehicle_cards', 'public');
            $vehicle->vehicle_card_image_path = $imagePath;
        }

        // Update vehicle
        $vehicle->update([
            'make' => $validated['make'],
            'model' => $validated['model'],
            'year' => $validated['year'],
            'license_plate' => $validated['license_plate'],
            'vin' => $validated['vin'],
            'color' => $validated['color'],
        ]);

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehicle updated successfully.');
    }

    /**
     * Remove the specified vehicle from storage.
     */
    public function destroy($id)
    {
        $vehicle = Vehicle::where('user_id', auth()->id())
            ->findOrFail($id);

        // Check if vehicle has active insurance
        $hasActiveInsurance = UserInsurance::where('vehicle_id', $vehicle->id)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->exists();

        if ($hasActiveInsurance) {
            return back()->with('error', 'Cannot delete a vehicle with active insurance policies.');
        }

        // Delete vehicle card image if exists
        if ($vehicle->vehicle_card_image_path) {
            Storage::disk('public')->delete($vehicle->vehicle_card_image_path);
        }

        // Delete vehicle
        $vehicle->delete();

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehicle deleted successfully.');
    }
}
