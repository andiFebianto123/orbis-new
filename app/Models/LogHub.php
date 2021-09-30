<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class LogHub extends Model
{
    use CrudTrait;

    protected $table = 'log_hubs';
    protected $fillable = [
        'personel_id','name', 'email', 'user_agent', 'ip', 'action'
    ];
    protected $guarded = ['id'];

}



