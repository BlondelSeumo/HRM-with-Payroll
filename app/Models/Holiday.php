<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $fillable = [
        'occasion',
        'start_date',
        'end_date',
        'created_by',
    ];
}
