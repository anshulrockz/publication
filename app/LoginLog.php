<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoginLog extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
}
