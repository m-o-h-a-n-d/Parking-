<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Customer;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index(Request $request)
    {
        $query = $request->post('search'); // مش get
        $cars = Car::when($query, function ($q) use ($query) {
            return $q->where('number', 'like', "%{$query}%");
        })->paginate(10);

        return view('cars.car', compact('cars'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::all(); // جلب كل العملاء
        return view('cars.create', compact('customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $input = $request->all();

        Car::create($input);
        return redirect()->route('cars.index')->with('success', 'car added successfully');

    }

    /**
     * Display the specified resource.
     */
    public function show(Car $car)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $car = Car::find($id);
        $customers = Customer::all();
        return view('cars.edit', compact('car', 'customers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();
        $car = Car::find($id);
        $car->update($input);
        return redirect()->route('cars.index')->with('Success', 'Car has been updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Car::find($id)->delete();
        return redirect()->route('cars.index')->with('error', 'Car has been deleted');

    }


    public function getCarsByCustomer($customer_id, $subscription_id = null)
    {
        // أولاً: هات كل العربيات اللي داخلة في اشتراكات
        $usedCarIds = Subscription::where('id', '!=', $subscription_id)->pluck('car_id')->toArray();

        // بعد كده: هات العربيات بتاعة العميل اللي مش مستخدمة في اشتراك
        $cars = Car::where('customer_id', $customer_id)
            ->whereNotIn('id', $usedCarIds)
            ->get();

        return response()->json($cars);
    }

}
