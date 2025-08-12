<?php

namespace App\Models;

use App\Http\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Orders extends Model
{
 use HasFactory;

    protected $fillable = [
        'user_id',
        'table_id',
        'status',
        'total_amount',
        'paid_amount',
        'change_amount',
    ];

    public function users()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function tables()
    {
        return $this->belongsTo(Tables::class,'table_id');
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class,'order_id');
    }
    public function scopeFilter(Builder $builder,QueryFilter $filters){
        return $filters->apply($builder);
    }


}
