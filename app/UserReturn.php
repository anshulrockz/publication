<?php

namespace App;

use DB;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserReturn extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];


    public function UserDetails()
    {
        return $this->hasOne('App\User', 'id','to_user');
    }
    
   	public static function return_bal($string)
	{
		$id = Auth::user()->id;
		$company = Auth::user()->company_id;
		$workshop = Auth::user()->workshop_id;
		
		$user_deposits = DB::table('user_deposits')
				->select(DB::raw('SUM( user_deposits.amount) as amount'))
				->where([
						['user_deposits.deleted_at',null],
						['user_deposits.to_user', $string],
						])
	            // ->leftJoin('expenses', 'expenses.created_for', '=', 'user_deposits.to_user')
	            // ->leftJoin('expense_details', 'expense_details.expense_id', '=', 'expenses.id')
	            ->first();

       	$user_expenses = DB::table('expenses')
				->select(DB::raw('SUM((expense_details.cost*expense_details.quantity) + expense_details.sgst + expense_details.cgst + expense_details.igst ) as amount'))
				->where([
						['expenses.status',1],
						['expenses.deleted_at',null],
						['expense_details.deleted_at',null],
						['expenses.created_for', $string],
						])
	            ->leftJoin('expense_details', 'expense_details.expense_id', '=', 'expenses.id')
	            ->first();

        $user_return = DB::table('user_returns')
				->select(DB::raw('SUM( user_returns.amount) as amount'))
				->where([
						['user_returns.deleted_at',null],
						['user_returns.by_user', $string],
						])
				->first();
	            
	    return $user_deposits->amount - $user_expenses->amount - $user_return->amount;
	}
}
