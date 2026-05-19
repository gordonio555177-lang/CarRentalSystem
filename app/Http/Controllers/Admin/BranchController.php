<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Staff;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::with('manager')->latest()->paginate(10);
        return view('admin.branches.index', compact('branches'));
    }
    
    public function create()
    {
        $managers = Staff::where('role', 'manager')->get();
        return view('admin.branches.create', compact('managers'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'address' => 'required|string',
            'city' => 'required|string|max:50',
            'phone' => 'required|string|max:20',
            'manager_staff_id' => 'nullable|exists:staff,staff_id',
        ]);
        
        Branch::create($validated);
        return redirect()->route('admin.branches.index')->with('success', 'Branch created');
    }
    
    public function edit(Branch $branch)
    {
        $managers = Staff::where('role', 'manager')->get();
        return view('admin.branches.edit', compact('branch', 'managers'));
    }
    
    public function update(Request $request, Branch $branch)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'address' => 'required|string',
            'city' => 'required|string|max:50',
            'phone' => 'required|string|max:20',
            'manager_staff_id' => 'nullable|exists:staff,staff_id',
        ]);
        
        $branch->update($validated);
        return redirect()->route('admin.branches.index')->with('success', 'Branch updated');
    }
    
    public function destroy(Branch $branch)
    {
        if ($branch->cars()->exists() || $branch->staff()->exists()) {
            return back()->with('error', 'Cannot delete branch with associated cars or staff');
        }
        $branch->delete();
        return redirect()->route('admin.branches.index')->with('success', 'Branch deleted');
    }
}