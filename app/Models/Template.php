<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;
    protected $table = 'template';
    protected $fillable = [
        'template_name',
        'prompt',
        'field_json',
        'is_tone'
    ];
}
