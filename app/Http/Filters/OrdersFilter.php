<?php

namespace App\Http\Filters;

class OrdersFilter extends QueryFilter
{
    protected $sortable=[
        'status',
    ];

    public function status($value){

        return $this->builder->whereIn('status',explode(',',$value));
    }

}
