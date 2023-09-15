<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public function dishes()
    {
        return $this->belongsToMany(Dish::class)->withPivot('customer_address');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
