<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AuthPersonel extends Authenticatable
{
    use Notifiable, HasApiTokens;

    protected $table = 'personels';
    protected $fillable = [
        'acc_status_id',
        'rc_dpw_id',
        'title_id',
        'first_name',
        'last_name',
        'gender',
        'date_of_birth',
        'marital_status',
        'spouse_name',
        'spouse_date_of_birth',
        'anniversary',
        'child_name',
        'ministry_background',
        'career_background',
        'image',
        'street_address',
        'city',
        'province',
        'postal_code',
        'country_id',
        'email',
        'second_email',
        'phone',
        'fax',
        'first_lisenced_on',
    ];

}