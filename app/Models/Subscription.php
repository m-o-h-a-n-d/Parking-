<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;
    protected $fillable = ['customer_id', 'car_id', 'slot_id', 'start_date', 'end_date'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function car()
    {
        return $this->belongsTo(Car::class);
    }
    public function slot()
    {
        return $this->belongsTo(Slot::class);
    }
}
