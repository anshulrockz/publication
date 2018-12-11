<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class SubSubExpense extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
    public static function expense_categoriesJoin()
	{
		return DB::table('sub_sub_expenses')
			->select('sub_sub_expenses.*', 'expense_categories.name as expense_categories_name', 'sub_expenses.name as sub_expenses_name')
			->where([
			['sub_sub_expenses.deleted_at',null]
			])
            ->leftJoin('sub_expenses', 'sub_expenses.id', '=', 'sub_sub_expenses.sub_expenses')
            ->leftJoin('expense_categories', 'expense_categories.id', '=', 'sub_expenses.expense_category')
            ->get();
		
	}
}
