<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Role extends Model
{
    use HasApiTokens;

    public function users(){
        return $this->hasMany(User::class, 'role_id');
    }
}
