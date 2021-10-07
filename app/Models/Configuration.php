<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{

    protected $table = 'configurations';
    protected $fillable = [
        'name','value'
    ];
    protected $guarded = ['id'];
  }
