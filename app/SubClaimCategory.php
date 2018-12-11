<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubClaimCategory extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
    protected $connection = 'mysql2';
}
