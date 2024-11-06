<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class api_data extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'password',
        'weight',
        'height',
        'age',
    ];
}
