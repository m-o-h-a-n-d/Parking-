<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable=[
        'name',
        'email',
        'phone',
        'whatsapp',
        'driving_license',
        'address',
        'total_pay',
    ];
    public function Cars(){
        return $this->hasMany(Car::class);
    }
    public function Subscriptions(){
        return $this->hasMany(Subscription::class);
    }


}
