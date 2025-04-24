<?php

namespace App\Http\Controllers;

use App\Models\Slot;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Symfony\Component\Console\Completion\Suggestion;

class SlotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $query = $request->input('search'); // الحصول على قيمة البحث من الطلب

        $slots  = Slot::where('location', 'like', "%{$query}%")->paginate(10);



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
        Slot::create($input);
        return redirect()->route('slots.index');
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
        return redirect()->route('slots.index');
    }
}
