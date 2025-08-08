<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Filters\QueryFilter;
class Tables extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
    ];


    public function orders()
    {
        return $this->hasMany(Orders::class);
    }

    public function scopeFilter(Builder $builder,QueryFilter $filters){
        return $filters->apply($builder);
    }


}
