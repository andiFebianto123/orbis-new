<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChurchType extends Model
{
    protected $table = 'church_types';
    protected $fillable = ['entities_type'];
}
