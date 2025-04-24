<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Slot;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index(Request $request)
    {
        // جلب عدد الاشتراكات والعملاء (إذا لزم الأمر)
        $SubscriptionsCount = Subscription::count();
        $CustomersCount     = Customer::count();

        // جلب كل الاشتراكات مع علاقة الـ Slot
        $subscriptions = Subscription::with('slot')->get();

        // حساب إجمالي الدفع عبر جمع total pay لكل اشتراك
        $total_pay = $subscriptions->sum(function ($subscription) {
            // تحويل التواريخ لكائنات Carbon
            $startTime = Carbon::parse($subscription->start_date);
            $endTime   = Carbon::parse($subscription->end_date);

            // حساب عدد الساعات بين وقت البداية والنهاية (على الأقل ساعة واحدة)
            $hoursParked = max(1, $startTime->diffInHours($endTime));

            // ضرب عدد الساعات بسعر المكان لكل اشتراك
            return $hoursParked * $subscription->slot->price;
        });

        return view('home', compact('SubscriptionsCount', 'CustomersCount', 'total_pay'));
    }
}
