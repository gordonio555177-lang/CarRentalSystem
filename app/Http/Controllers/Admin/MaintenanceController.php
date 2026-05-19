<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Maintenance;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaintenanceController extends Controller
{
    public function index()
    {
        $maintenances = Maintenance::with('car')->latest()->paginate(10);
        return view('admin.maintenances.index', compact('maintenances'));
    }
    
    public function create()
    {
        $cars = Car::all();
        return view('admin.maintenances.create', compact('cars'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'car_id'           => 'required|exists:cars,car_id',
            'maintenance_date' => 'required|date',
            'description'      => 'required|string',
            'cost'             => 'required|numeric|min:0',
            'next_due_date'    => 'nullable|date|after:maintenance_date',
            'status'           => 'required|in:scheduled,in_progress,completed',
        ]);

        Maintenance::create($validated);

        // Sync car status directly via DB to guarantee the update
        $newCarStatus = ($validated['status'] === 'completed') ? 'available' : 'maintenance';
        DB::table('cars')->where('car_id', $validated['car_id'])->update(['status' => $newCarStatus]);

        return redirect()->route('admin.maintenances.index')
            ->with('success', 'Maintenance record added. Car status set to ' . $newCarStatus . '.');
    }
    
    public function edit(Maintenance $maintenance)
    {
        $cars = Car::all();
        return view('admin.maintenances.edit', compact('maintenance', 'cars'));
    }
    
    public function update(Request $request, Maintenance $maintenance)
    {
        $validated = $request->validate([
            'car_id'           => 'required|exists:cars,car_id',
            'maintenance_date' => 'required|date',
            'description'      => 'required|string',
            'cost'             => 'required|numeric|min:0',
            'next_due_date'    => 'nullable|date',
            'status'           => 'required|in:scheduled,in_progress,completed',
        ]);

        $maintenance->update($validated);

        // Sync car status directly via DB to guarantee the update
        $newCarStatus = ($validated['status'] === 'completed') ? 'available' : 'maintenance';
        DB::table('cars')->where('car_id', $validated['car_id'])->update(['status' => $newCarStatus]);

        return redirect()->route('admin.maintenances.index')
            ->with('success', 'Maintenance updated. Car status set to ' . $newCarStatus . '.');
    }
    
    public function destroy(Maintenance $maintenance)
    {
        $carId = $maintenance->car_id;
        $wasActive = in_array($maintenance->status, ['scheduled', 'in_progress']);

        $maintenance->delete();

        // If this was an active maintenance, free the car
        if ($wasActive) {
            // Only set available if no other active maintenance exists for this car
            $hasOtherActive = Maintenance::where('car_id', $carId)
                ->whereIn('status', ['scheduled', 'in_progress'])
                ->exists();

            if (!$hasOtherActive) {
                DB::table('cars')->where('car_id', $carId)->update(['status' => 'available']);
            }
        }

        return redirect()->route('admin.maintenances.index')
            ->with('success', 'Maintenance record deleted.');
    }
    
    public function complete(Maintenance $maintenance)
    {
        $maintenance->update(['status' => 'completed']);
        DB::table('cars')->where('car_id', $maintenance->car_id)->update(['status' => 'available']);
        return back()->with('success', 'Maintenance completed. Car is now available.');
    }
}