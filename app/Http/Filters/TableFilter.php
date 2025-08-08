<?php

namespace App\Http\Filters;

use App\Http\Filters\QueryFilter;

class TableFilter extends QueryFilter
{
    protected $sortable=[
        'status',
    ];

    public function status($value){

        return $this->builder->whereIn('status',explode(',',$value));
    }

}
