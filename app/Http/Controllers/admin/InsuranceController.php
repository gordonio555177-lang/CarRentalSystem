<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Insurance;
use Illuminate\Http\Request;

class InsuranceController extends Controller
{
    public function index()
    {
        $insurances = Insurance::paginate(10);
        return view('admin.insurances.index', compact('insurances'));
    }
    
    public function create()
    {
        return view('admin.insurances.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'coverage_details' => 'required|string',
            'daily_rate' => 'required|numeric|min:0',
        ]);
        
        Insurance::create($validated);
        return redirect()->route('admin.insurances.index')->with('success', 'Insurance added successfully');
    }
    
    public function edit(Insurance $insurance)
    {
        return view('admin.insurances.edit', compact('insurance'));
    }
    
    public function update(Request $request, Insurance $insurance)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'coverage_details' => 'required|string',
            'daily_rate' => 'required|numeric|min:0',
        ]);
        
        $insurance->update($validated);
        return redirect()->route('admin.insurances.index')->with('success', 'Insurance updated successfully');
    }
    
    public function destroy(Insurance $insurance)
    {
        $insurance->delete();
        return redirect()->route('admin.insurances.index')->with('success', 'Insurance deleted successfully');
    }
}