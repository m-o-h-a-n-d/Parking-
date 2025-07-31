<?php

namespace App\Http\Controllers;

use App\Models\Slot;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SlotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $request->input('search');

        $slotsQuery = Slot::query();

        if ($query) {
            $slotsQuery->where('location', 'like', "%{$query}%");
        }

        $slots = $slotsQuery->with('subscriptions')->paginate(10);

        foreach ($slots as $slot) {
            $hasActiveSubscription = false; // Initialize to false

            // A subscription is active if its end_date is in the future relative to the current time.
            foreach ($slot->subscriptions as $subscription) {
                $subscriptionEndDate = Carbon::parse($subscription->end_date)->timezone('Africa/Cairo'); // Interpret end_date as Africa/Cairo
                $currentTime = Carbon::now()->timezone('Africa/Cairo'); // Get current time in Africa/Cairo
                $isGreaterThan = $subscriptionEndDate->greaterThan($currentTime);

                Log::info('Slot ID: ' . $slot->id . ' - Subscription ID: ' . $subscription->id . ' - End Date (Africa/Cairo): ' . $subscriptionEndDate->toDateTimeString() . ' - Current Time (Africa/Cairo): ' . $currentTime->toDateTimeString() . ' - isGreaterThan: ' . ($isGreaterThan ? 'true' : 'false'));

                if ($isGreaterThan) {
                    $hasActiveSubscription = true; // Found an active subscription, slot is occupied
                    break; // No need to check further for this slot
                }
            }

            // Determine the new status based on whether an active subscription was found
            // If no active subscriptions, the slot is available (false).
            $newStatus = $hasActiveSubscription;

            // Update the slot's status in the database if it's different from the calculated status
            // True if occupied (green check), false if available (red X)
            if ($slot->status !== $newStatus) {
                $slot->status = $newStatus;
                $slot->save();
            }
            Log::info('Slot ID: ' . $slot->id . ' - Has Active Subscription: ' . ($hasActiveSubscription ? 'true' : 'false') . ' - Old Status: ' . ($slot->status ? 'true' : 'false') . ' - New Status: ' . ($newStatus ? 'true' : 'false'));
        }

        return view('slots.slot', compact('slots'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $subscriptions = Subscription::all();

        return view('slots.create', compact('subscriptions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();
        // Set default status to false (available) when creating a new slot
        $input['status'] = false;
        Slot::create($input);
        return redirect()->route('slots.index')->with('Success', 'Slot has been uploaded');
    }

    /**
     * Display the specified resource.
     */
    public function show(Slot $slot)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $slot = Slot::find($id);
        return view('slots.edit', compact('slot'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();
        $slot = Slot::find($id);
        $slot->update($input);
        return redirect()->route('slots.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Slot::find($id)->delete();
        return redirect()->route('slots.index')->with('error', 'Slot has been deleted');
    }

    public function updateStatus(Request $request, $id)
    {
        $slot = Slot::findOrFail($id);
        $slot->update(['status' => $request->status]);
        return response()->json(['message' => 'Slot status updated successfully']);
    }
}
