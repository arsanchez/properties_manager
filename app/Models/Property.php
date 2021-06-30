<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model 
{
    protected $table = 'properties';
    
    protected $hidden = ['uuid'];

    use SoftDeletes;
}
