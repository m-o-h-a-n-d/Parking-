<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Customer;
use App\Models\Slot;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $request->input('search');
        $subscriptions = Subscription::all();


        $subscription = Subscription::find(Customer::class);
        $subscriptions = $subscriptions->filter(function ($subscription) use ($query) {
            return strpos($subscription->customer->name, $query) !== false;
        });

        return view('subscriptios.subscription', compact('subscriptions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // جلب كل العربيات اللي مستخدمة بالفعل في اشتراكات
        $usedCarIds = Subscription::pluck('car_id')->toArray();

        // جلب العربيات اللي مش مستخدمة في اشتراك (يعني متاحة)
        $cars = Car::whereNotIn('id', $usedCarIds)->get();

        // جلب كل العملاء (حتى اللي عندهم اشتراك، لأن ممكن يكون عندهم عربيات تانية متاحة)
        $customerAvailable = Customer::all();

        // جلب الأماكن المتاحة (slots اللي مش مستخدمة)
        $usedSlotIds = Subscription::pluck('slot_id')->toArray();
        $availableSlots = Slot::whereNotIn('id', $usedSlotIds)->get();

        return view('subscriptios.create', compact('customerAvailable', 'cars', 'availableSlots'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $input = $request->all();
        $slot = Slot::find($request->slot);
        if ($slot) {
            $slot->status = true; // أو 1 حسب نوع الحقل
            $slot->save();
        }
        $subscription = Subscription::create($input);
        $subscription->slot = $request->slot;

        return redirect()->route('subscriptions.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Subscription $subscription)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $subscription = Subscription::find($id);
        $customerAvailable = Customer::all();
        $availableSlots = Slot::all();
        $cars = Car::all();

        return view('subscriptios.edit', compact('cars', 'customerAvailable', 'subscription', 'availableSlots'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();
        $slot = Subscription::find($id);
        $slot->update($input);
        return redirect()->route('subscriptions.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Subscription::find($id)->delete();
        return redirect()->route('subscriptions.index');
    }




    public function calculateTotalPay($subscriptionId)
    {
        $subscription = Subscription::with('slot')->find($subscriptionId);

        if (!$subscription || !$subscription->slot) {
            return response()->json(['error' => 'Subscription or Slot not found'], 404);
        }

        $startTime = Carbon::parse($subscription->start_date);
        $endTime = Carbon::parse($subscription->end_date);

        $hoursParked = max(1, $startTime->diffInHours($endTime)); // حساب عدد الساعات على الأقل ساعة واحدة
        $totalPay = $hoursParked * $subscription->slot->price; // ضرب عدد الساعات في السعر لكل ساعة

        return response()->json([
            'customer' => $subscription->customer->name,
            'slot' => $subscription->slot->name,
            'hours_parked' => $hoursParked,
            'total_pay' => $totalPay
        ]);
    }
}
