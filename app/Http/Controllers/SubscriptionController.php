<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Customer;
use App\Models\Slot;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Mockery\Matcher\Type;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $request->input('search');
        $subscriptions = Subscription::all();

        // Filter subscriptions based on search query if provided
        if ($query) {
            $subscriptions = $subscriptions->filter(function ($subscription) use ($query) {
                // Assuming search is by customer name. Adjust if needed for other fields.
                return strpos(strtolower($subscription->customer->name), strtolower($query)) !== false;
            });
        }

        // Add an is_expired property to each subscription
        $now = Carbon::now();
        foreach ($subscriptions as $subscription) {
            $subscription->is_expired = Carbon::parse($subscription->end_date)->lessThanOrEqualTo($now);

            // If the subscription is expired, update the associated slot's status to false (available)
            if ($subscription->is_expired) {
                $slot = Slot::find($subscription->slot_id);
                if ($slot) {
                    // Check if there are any active subscriptions for this slot
                    $activeSubscriptionsCount = Subscription::where('slot_id', $subscription->slot_id)
                        ->where('end_date', '>', $now)
                        ->where('id', '!=', $subscription->id) // Exclude the current expiring subscription
                        ->count();

                    // If no other active subscriptions, set slot status to false (available)
                    if ($activeSubscriptionsCount === 0 && $slot->status !== false) {
                        $slot->status = false;
                        $slot->save();
                    }
                }
            }
        }

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
        $slot = Slot::find($request->slot_id);
        if ($slot) {
            $slot->status = true; // Set to true (occupied) when a new subscription is created
            $slot->save();
        }
        $subscription = Subscription::create($input);

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
        $subscription = Subscription::with('car')->find($id);
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
        $subscription = Subscription::find($id);
        $subscription->update($input);

        // After updating the subscription, we need to ensure the slot status is re-evaluated.
        // The SlotController@index method will handle this when the slots page is viewed.
        // No direct slot status update needed here, as it's handled globally by SlotController@index.

        return redirect()->route('subscriptions.index')->with('success', 'Subscription has been updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Subscription::find($id)->delete();
        return redirect()->route('subscriptions.index')->with('error', 'Subscription has been deleted');
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
