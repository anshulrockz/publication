<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubPurchase extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
    public static function purchase_categoriesJoin()
	{
		return DB::table('sub_purchases')
			->select('sub_purchases.*', 'purchase_categories.name as purchase_categories_name')
			->where([
			['sub_purchases.deleted_at',null]
			])
            ->join('purchase_categories', 'purchase_categories.id', '=', 'sub_purchases.expense_category')
            ->get();
		
	}
}
