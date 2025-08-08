<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{

    public function users()
    {
        return $this->belongsTo(User::class);
    }
    public function tables()
    {
        return $this->belongsTo(Tables::class);
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }


}
