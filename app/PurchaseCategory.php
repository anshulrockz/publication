<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseCategory extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
    public static function DepartmentJoin()
	{
		return DB::table('purchase_categories')
			->select('purchase_categories.*', 'departments.name as department_name')
			->where([
			['purchase_categories.deleted_at',null]
			])
            ->leftJoin('departments', 'departments.id', '=', 'purchase_categories.department_id')
            ->get();
		
	}
}
