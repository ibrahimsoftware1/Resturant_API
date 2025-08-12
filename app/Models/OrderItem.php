<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory;
    //

    public function order()
    {
        return $this->belongsTo(Orders::class,'order_id');
    }

    public function menuItems()
    {
        return $this->belongsTo(MenuItem::class,'menu_item_id');
    }
}
