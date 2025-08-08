<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    //

    public function orders()
    {
        return $this->belongsTo(Orders::class);
    }

    public function menuItems()
    {
        return $this->belongsTo(MenuItem::class);
    }
}
