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

        // جلب الاشتراكات لحساب إجمالي الدفع
        $customers = $customers->with('subscriptions')->paginate(10);

        // حساب إجمالي الدفع لكل عميل
        foreach ($customers as $customer) {
            $totalPay = 0;

            foreach ($customer->subscriptions as $subscription) {
                $startTime = Carbon::parse($subscription->start_date);
                $endTime = Carbon::parse($subscription->end_date);
                $hoursParked = max(1, $startTime->diffInHours($endTime));
                $totalPay += $hoursParked * $subscription->slot->price;
            }

            $customer->total_pay = $totalPay;
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
        return redirect()->route('customer.index');
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
        return redirect()->route('customer.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        Customer::find($id)->delete();
        return redirect()->route('customer.index');
    }
}
