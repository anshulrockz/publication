<?php

namespace App;

use DB;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleDetail extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
    protected $connection = 'mysql2';
}
