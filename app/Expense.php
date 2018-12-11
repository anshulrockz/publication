<?php

namespace App;

use DB;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

	public static function lastid()
	{
		return DB::table('expenses')->orderBy('id', 'desc')->first();
	}

	public function UserDetails()
    {
        return $this->hasOne('App\User', 'id','created_by')->pluck('name');
    }

    public function ExpenseDetails()
    {
        return $this->hasMany('App\ExpenseDetail', 'expense_id');
    }

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
            ->orderBy('expenses.id', 'DESC')
            ->groupBy('expenses.id')
            ->get();
		
	}
	
	public static function workshop_all()
	{
		$company = Auth::user()->company_id;
		$workshop = Auth::user()->workshop_id;
		$id = Auth::user()->id;
		$user_type = Auth::user()->user_type;
		
			return DB::table('expenses')
			->select('expenses.*', 'users.name as user', DB::raw('SUM(expense_details.cost*expense_details.quantity) as cost'), DB::raw('SUM(expense_details.quantity) as quantity'), DB::raw('SUM(expense_details.sgst) as sgst'), DB::raw('SUM(expense_details.cgst) as cgst'), DB::raw('SUM(expense_details.igst) as igst') )
			->where([
			['users.company_id',$company],
			['users.workshop_id',$workshop],
			['users.id','=', $id],
			['users.id','!=', 1],
			['expenses.deleted_at',null],
			['expense_details.deleted_at',null]
			])
            ->leftJoin('users', 'users.id', '=', 'expenses.created_by')
            ->leftJoin('expense_details', 'expense_details.expense_id', '=', 'expenses.id')
            ->orderBy('expenses.id', 'DESC')
            ->groupBy('expenses.id')
            ->get();
		
		
	}
	
	public static function user_all()
	{
		$company = Auth::user()->company_id;
		$id = Auth::user()->id;
		$user_type = Auth::user()->user_type;
		
			return DB::table('expenses')
			->select('expenses.*', 'users.name as user', DB::raw('SUM(expense_details.cost*expense_details.quantity) as cost'), DB::raw('SUM(expense_details.quantity) as quantity'), DB::raw('SUM(expense_details.sgst) as sgst'), DB::raw('SUM(expense_details.cgst) as cgst'), DB::raw('SUM(expense_details.igst) as igst') )
			->where([
			['expenses.created_by',$id],
			['expenses.deleted_at',null],
			['expense_details.deleted_at',null]
			])
            ->leftJoin('users', 'users.id', '=', 'expenses.created_by')
            ->leftJoin('expense_details', 'expense_details.expense_id', '=', 'expenses.id')
            ->orderBy('expenses.id', 'DESC')
            ->groupBy('expenses.id')
            ->get();		
	}
	
    public static function balance($id)
	{
		$deposits = DB::table('deposits')
			->select(DB::raw('sum(amount) as amt'))
			->where([
			['deposits.to_user',$id],
			['deposits.deleted_at',null]
			])
            ->first();

      	$user_deposits = DB::table('user_deposits')
			->select(DB::raw('sum(amount) as amt'))
			->where([
			['user_deposits.created_by',$id],
			['user_deposits.deleted_at',null]
			])
            ->first();
            
        $user_returns = DB::table('user_returns')
			->select(DB::raw('sum(amount) as amt'))
			->where([
			['user_returns.created_by',$id],
			['user_returns.deleted_at',null]
			])
            ->first();
        
        $expenses = DB::table('expenses')
			->select(DB::raw('SUM(expense_details.cost*expense_details.quantity) as cost'), DB::raw('SUM(expense_details.quantity) as quantity'), DB::raw('SUM(expense_details.sgst) as sgst'), DB::raw('SUM(expense_details.cgst) as cgst'), DB::raw('SUM(expense_details.igst) as igst') )
			->where([
			['expenses.created_by',$id],
			['expenses.created_for',null],
			['expenses.paid_by',$id],
			['expenses.paid_in',1],
			['expenses.status',1],
			['expenses.deleted_at',null],
			['expense_details.deleted_at',null]
			])
            ->leftJoin('expense_details', 'expense_details.expense_id', '=', 'expenses.id')
            ->first();

    	return $deposits->amt  + $user_returns->amt - $user_deposits->amt - $expenses->cost - $expenses->sgst - $expenses->cgst - $expenses->igst; 
         
	}
	
    public static function expense_bar_chart()
	{
		$company = Auth::user()->company_id;
		$workshop = Auth::user()->workshop_id;
		$id = Auth::user()->id;
		$user_type = Auth::user()->user_type;

		if(isset($_GET['company']) && isset($_GET['location']))
		{
			$company = Auth::user()->company_id;
			$workshop = Auth::user()->workshop_id;
			$id = Auth::user()->id;
			$user_type = Auth::user()->user_type;
		}

		elseif(isset($_GET['location']))
		{
			$company = Auth::user()->company_id;
			$workshop = $_GET['location'];
			$user_type = 3;
		}

		if($user_type == 1  || $user_type == 5){
		
			return DB::table('expenses')
				->select( DB::raw('YEAR(expenses.invoice_date) AS y'), DB::raw('MONTH(expenses.invoice_date) AS m'), DB::raw('SUM(cost*quantity) as cost'), DB::raw('SUM(sgst) as sgst'), DB::raw('SUM(cgst) as cgst'), DB::raw('SUM(igst) as igst') )
				->where( [
							[DB::raw('YEAR(expenses.invoice_date)'), "=","2018"],
							['expense_details.deleted_at', null],
							['expenses.deleted_at', null],
							['expenses.status', 1],
						])
		  		->leftJoin('expense_details', 'expense_details.expense_id', '=', 'expenses.id')
				->groupBy('y', 'm')
				->orderBy('m', 'asc')
				->get();
		}

		if($user_type == 3){ 
			return DB::table('expenses')
					->select( DB::raw('YEAR(expenses.invoice_date) AS y'), DB::raw('MONTH(expenses.invoice_date) AS m'), DB::raw('SUM(cost*quantity) as cost'), DB::raw('SUM(sgst) as sgst'), DB::raw('SUM(cgst) as cgst'), DB::raw('SUM(igst) as igst') )
					->where([
						    [ DB::raw('YEAR(expenses.invoice_date)'),"2018"],
						    ['expense_details.deleted_at', null],
						    ['expenses.deleted_at', null],
						    ['expenses.status', 1],
						    ['users.workshop_id', $workshop],
							])
	            	->leftJoin('expense_details', 'expense_details.expense_id', '=', 'expenses.id')
		            ->leftJoin('users', 'users.id', '=', 'expenses.created_by')
					->groupBy('y', 'm')
					->orderBy('m', 'asc')
					->get();
		}

		else{
			return DB::table('expenses')
				->select( DB::raw('YEAR(expenses.invoice_date) AS y'), DB::raw('MONTH(expenses.invoice_date) AS m'), DB::raw('SUM(cost*quantity) as cost'), DB::raw('SUM(sgst) as sgst'), DB::raw('SUM(cgst) as cgst'), DB::raw('SUM(igst) as igst') )
				->where( [
							[DB::raw('YEAR(expenses.invoice_date)'), "=","2018"],
							['expenses.created_by',$id],
                			['expenses.created_for',null],
                			['expenses.paid_by',$id],
                			['expenses.paid_in',1],
                			['expenses.status',1],
                			['expenses.deleted_at',null],
                			['expense_details.deleted_at',null]
							])
		        ->leftJoin('users', 'users.id', '=', 'expenses.invoice_date')
		  		->leftJoin('expense_details', 'expense_details.expense_id', '=', 'expenses.id')
				->groupBy('y', 'm')
				->orderBy('m', 'asc')
				->get();
			
		}
	}

	public static function expense_pie_chart()
	{
		$company = Auth::user()->company_id;
		$workshop = Auth::user()->workshop_id;
		$id = Auth::user()->id;
		$user_type = Auth::user()->user_type;

		if(isset($_GET['location']))
		{
			$company = Auth::user()->company_id;
			$workshop = $_GET['location'];
			$user_type = 3;
		}

		if($user_type == 1){
			return DB::table('expenses')
				->select( "expense_details.category1", "expense_details.category2", "expense_details.category3", DB::raw('SUM(expense_details.cost*expense_details.quantity) as cost'), DB::raw('SUM(expense_details.quantity) as quantity'), DB::raw('SUM(expense_details.sgst) as sgst'), DB::raw('SUM(expense_details.cgst) as cgst'), DB::raw('SUM(expense_details.igst) as igst') )
				->where([
						['expenses.deleted_at',null],
						['expenses.status',1],
						['expense_details.deleted_at',null]
						])
	            ->leftJoin('expense_details', 'expense_details.expense_id', '=', 'expenses.id')
				->groupBy('category3')
				->get();
		}
		
		if($user_type == 3){
			return DB::table('expenses')
				->select( "expense_details.category1", "expense_details.category2", "expense_details.category3", DB::raw('SUM(expense_details.cost*expense_details.quantity) as cost'), DB::raw('SUM(expense_details.quantity) as quantity'), DB::raw('SUM(expense_details.sgst) as sgst'), DB::raw('SUM(expense_details.cgst) as cgst'), DB::raw('SUM(expense_details.igst) as igst') )
				->where([
						['expenses.deleted_at',null],
						['expenses.status',1],
						['expense_details.deleted_at',null],
					    	['users.workshop_id', $workshop]
						])
	            ->leftJoin('expense_details', 'expense_details.expense_id', '=', 'expenses.id')
	            ->leftJoin('users', 'users.id', '=', 'expense_details.created_by')
				->groupBy('category3')
				->get();
		}

		else{
			return DB::table('expenses')
				->select( "expense_details.category1", "expense_details.category2", "expense_details.category3", DB::raw('SUM(expense_details.cost*expense_details.quantity) as cost'), DB::raw('SUM(expense_details.quantity) as quantity'), DB::raw('SUM(expense_details.sgst) as sgst'), DB::raw('SUM(expense_details.cgst) as cgst'), DB::raw('SUM(expense_details.igst) as igst') )
				->where([
						['expenses.deleted_at',null],
						['expenses.status',1],
						['expense_details.deleted_at',null],
					    ['users.id', $id]
						])
	            ->leftJoin('expense_details', 'expense_details.expense_id', '=', 'expenses.id')
	            ->leftJoin('users', 'users.id', '=', 'expense_details.created_by')
				->groupBy('category3')
				->get();
		}
	}
}
