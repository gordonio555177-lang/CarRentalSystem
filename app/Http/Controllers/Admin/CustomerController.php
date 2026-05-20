<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::withCount('rentals')->latest()->paginate(10);
        return view('admin.customers.index', compact('customers'));
    }

    public function create()
    {
        return view('admin.customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|unique:customers',
            'phone' => 'required|string|max:20',
            'license_no' => 'required|string|unique:customers',
            'address' => 'required|string',
        ]);

        $validated['registered_date'] = now();

        Customer::create($validated);

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer added successfully!');
    }

    public function show(Customer $customer)
    {
        $customer->load(['rentals' => function($q) {
            $q->with('car')->latest();
        }, 'feedback']);
        
        return view('admin.customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name'  => 'required|string|max:50',
            'email'      => 'required|email|unique:customers,email,' . $customer->customer_id . ',customer_id',
            'phone'      => 'required|string|max:20',
            'license_no' => 'required|string|unique:customers,license_no,' . $customer->customer_id . ',customer_id',
            'address'    => 'required|string',
        ]);

        $customer->update($validated);

        // Keep the linked users table in sync so rentals/dashboard show the updated name
        if ($customer->user_id) {
            \App\Models\User::where('id', $customer->user_id)->update([
                'name'  => trim($validated['first_name'] . ' ' . $validated['last_name']),
                'email' => $validated['email'],
                'phone' => $validated['phone'],
            ]);
        }

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer updated successfully!');
    }

    public function destroy(Customer $customer)
    {
        if ($customer->rentals()->exists()) {
            return back()->with('error', 'Cannot delete customer with existing rentals!');
        }
        
        $customer->delete();
        
        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer deleted successfully!');
    }
}