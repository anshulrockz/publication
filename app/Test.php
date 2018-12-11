<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Illuminate\Support\Facades\DB;
use DB;

class Test extends Model
{
    
    public static function super_admin_all()
	{
		return DB::table('expenses')
			->select('expenses.*', 'users.name as user', DB::raw('SUM(expense_details.cost*expense_details.quantity) as cost'), DB::raw('SUM(expense_details.quantity) as quantity'), DB::raw('SUM(expense_details.sgst) as sgst'), DB::raw('SUM(expense_details.cgst) as cgst'), DB::raw('SUM(expense_details.igst) as igst') )
			->where([
			['expenses.deleted_at',null],
			['expense_details.deleted_at',null]
			])
            ->leftJoin('users', 'users.id', '=', 'expenses.created_by')
            ->leftJoin('expense_details', 'expense_details.expense_id', '=', 'expenses.id')
            ->groupBy('expenses.id')
            // ->paginate();
            ->get();
		
	}
}
