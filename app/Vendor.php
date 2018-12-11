<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public static function lastid($tableName)
	{
		return DB::table($tableName)->orderBy('id', 'desc')->first();
	}
}
