<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Branch;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function index()
    {
        $cars = Car::with('branch')->latest()->paginate(10);
        return view('admin.cars.index', compact('cars'));
    }

    public function create()
    {
        $branches = Branch::all();
        return view('admin.cars.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'brand' => 'required|string|max:50',
            'model' => 'required|string|max:50',
            'year' => 'required|integer|min:1990|max:' . (date('Y') + 1),
            'license_plate' => 'required|string|unique:cars',
            'daily_rate' => 'required|numeric|min:0',
            'mileage' => 'required|integer|min:0',
            'branch_id' => 'required|exists:branches,branch_id',
            'status' => 'required|in:available,rented,maintenance,unavailable',
        ]);

        Car::create($validated);

        return redirect()->route('admin.cars.index')
            ->with('success', 'Car added successfully!');
    }

    public function show(Car $car)
    {
        $car->load(['rentals' => function($q) {
            $q->latest()->with('customer');
        }, 'maintenances']);
        return view('admin.cars.show', compact('car'));
    }

    public function edit(Car $car)
    {
        $branches = Branch::all();
        return view('admin.cars.edit', compact('car', 'branches'));
    }

    public function update(Request $request, Car $car)
    {
        $validated = $request->validate([
            'brand' => 'required|string|max:50',
            'model' => 'required|string|max:50',
            'year' => 'required|integer|min:1990|max:' . (date('Y') + 1),
            'license_plate' => 'required|string|unique:cars,license_plate,' . $car->car_id . ',car_id',
            'daily_rate' => 'required|numeric|min:0',
            'mileage' => 'required|integer|min:0',
            'branch_id' => 'required|exists:branches,branch_id',
            'status' => 'required|in:available,rented,maintenance,unavailable',
        ]);

        $car->update($validated);

        return redirect()->route('admin.cars.index')
            ->with('success', 'Car updated successfully!');
    }

    public function destroy(Car $car)
    {
        if ($car->rentals()->exists()) {
            return back()->with('error', 'Cannot delete car with existing rentals!');
        }
        
        $car->delete();
        
        return redirect()->route('admin.cars.index')
            ->with('success', 'Car deleted successfully!');
    }
}