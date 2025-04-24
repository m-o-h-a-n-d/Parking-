<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slot extends Model
{
    use HasFactory;

    protected $fillable = ['number',  'location', 'price'];

    public function Subscriptions(){
        return $this->hasMany(Subscription::class);
    }

}
