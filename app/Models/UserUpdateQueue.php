<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserUpdateQueue extends Model
{
    protected $fillable = [
        'email',
        'changes',
    ];
    protected $casts = [
        'changes' => 'array'
    ];
}
