<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $query = $request->get('search'); // الحصول على قيمة البحث من الطلب

        $customers = Customer::query();

        // تطبيق البحث إذا تم إدخال نص
        if ($query) {
            $customers->where('name', 'like', "%{$query}%");
        }

        // جلب الاشتراكات لحساب إجمالي الدفع وتحديد حالة النشاط
        $customers = $customers->with('subscriptions')->paginate(10);

        // حساب إجمالي الدفع لكل عميل وتحديد ما إذا كان لديه اشتراك نشط
        foreach ($customers as $customer) {
            $totalPay = 0;
            $isActive = false; // Initialize $isActive here

            foreach ($customer->subscriptions as $subscription) {
                // Check if the subscription is active (end date is in the future)
                if (\Carbon\Carbon::parse($subscription->end_date)->isFuture()) {
                    $isActive = true; // Mark as active if at least one active subscription is found

                    // Calculate total pay for active subscriptions
                    $startTime = \Carbon\Carbon::parse($subscription->start_date);
                    $endTime = \Carbon\Carbon::parse($subscription->end_date);
                    // Ensure end time is not before start time for calculation (should be handled by form validation, but good practice)
                    if ($endTime->greaterThan($startTime)) {
                        $hoursParked = max(1, $startTime->diffInHours($endTime));
                        $totalPay += $hoursParked * $subscription->slot->price; // Assuming slot relation is loaded or available
                    }
                }
            }

            $customer->total_pay = $totalPay;
            $customer->is_active = $isActive; // Add is_active property
        }

        return view('customers.customer', compact('customers'));
    }




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();
        Customer::create($input);
        return redirect()->route('customer.index')->with('success', 'Customer added successfully');

    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $customer = Customer::find($id);
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();
        $customer = Customer::find($id);
        $customer->update($input);
        return redirect()->route('customer.index')->with('success', 'Customer updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        Customer::find($id)->delete();
        return redirect()->route('customer.index')->with('error', 'Customer deleted successfully');

    }

    public function updateStatus(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        $customer->update(['status' => $request->status]);
        return response()->json(['message' => 'Customer status updated successfully']);
    }
}
