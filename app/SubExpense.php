<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubExpense extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
    public static function expense_categoriesJoin()
	{
		return DB::table('sub_expenses')
			->select('sub_expenses.*', 'expense_categories.name as expense_categories_name')//, 'departments.name as department_name')
			->where([
			['sub_expenses.deleted_at',null]
			])
            ->leftJoin('expense_categories', 'expense_categories.id', '=', 'sub_expenses.expense_category')
            // ->leftJoin('departments', 'departments.id', '=', 'expense_categories.department_id')
            ->get();
		
	}
}
