<?php

namespace App;

use DB;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserDeposit extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

	public static function lastid()
	{
		return DB::table('user_deposits')->orderBy('id', 'desc')->first();
	}

    public function UserDetails()
    {
        return $this->hasOne('App\User', 'id','to_user');
    }
    
    public static function all_user_deposits()
	{
		$company = Auth::user()->company_id;
		$workshop = Auth::user()->workshop_id;
		$id = Auth::user()->id;
		$user_type = Auth::user()->user_type;
		
		if($user_type == 1  || $user_type == 5){
			return DB::table('user_deposits')
			->select('user_deposits.*', 'users.name as user')
			->where([
			['user_deposits.deleted_at',null],
					    //['users.workshop_id', $workshop],
					    //['user_deposits.to_user', $id],
					    //['users.id', $id]
						])
	            ->leftJoin('users', 'users.id', '=', 'user_deposits.to_user')
				->get();
		}

        	/*if($user_type == 3){
			return DB::table('user_deposits')
			->select('user_deposits.*', 'users.name as user')
			->where([
					['user_deposits.deleted_at',null],
					['users.workshop_id', $workshop],
					//['user_deposits.to_user', $id],
					//['users.id', $id]
						])
	            ->leftJoin('users', 'users.id', '=', 'user_deposits.to_user')
				->get();
		}*/

		else{
			return DB::table('user_deposits')
				->select('user_deposits.*', 'users.name as user')
				->where([
				['user_deposits.deleted_at',null],
					//['user_deposits.to_user', $id],
					['users.workshop_id', $workshop],
				])
	            ->leftJoin('users', 'users.id', '=', 'user_deposits.to_user')
	            ->get();
		}
	}
	
	public static function workshop_user_deposits()
	{
		$id = Auth::user()->id;
		$company = Auth::user()->company_id;
		$workshop = Auth::user()->workshop_id;
		
		return DB::table('user_deposits')
			->select('user_deposits.*', 'users.name as user')
			->where([
			['users.company_id',$company],
			['users.workshop_id',$workshop],
			['user_deposits.created_by',$id],
			['user_deposits.deleted_at',null]
			])
            ->leftJoin('users', 'users.id', '=', 'user_deposits.to_user')
            ->get();
	}

	public static function deposit_bar_chart()
	{
		$company = Auth::user()->company_id;
		$workshop = Auth::user()->workshop_id;
		$id = Auth::user()->id;
		$user_type = Auth::user()->user_type;

		if($user_type == 1  || $user_type == 5){
			return DB::table('user_deposits')
				->select( DB::raw('YEAR(date) AS y'), DB::raw('MONTH(date) AS m'), DB::raw('SUM(amount) as total') )
				->where( [
							[DB::raw('YEAR(date)'), "=",date('Y')],
							['deleted_at', null]
						])
				->groupBy('y', 'm')
					->orderBy('m', 'asc')
				->get();
		}

		if($user_type == 3){
			return DB::table('user_deposits')
				->select( DB::raw('YEAR(date) AS y'), DB::raw('MONTH(date) AS m'), DB::raw('SUM(amount) as total') )
				->where( [
							[DB::raw('YEAR(date)'), "=",date('Y')],
					    ['users.workshop_id', $workshop],
							['user_deposits.deleted_at', null]
						])
				->groupBy('y', 'm')
					->orderBy('m', 'asc')
	            ->leftJoin('users', 'users.id', '=', 'user_deposits.to_user')->get();
		}

		if($user_type == 4){
			return DB::table('user_deposits')
				->select( DB::raw('YEAR(user_deposits.date) AS y'), DB::raw('MONTH(user_deposits.date) AS m'), DB::raw('SUM(user_deposits.amount) as total') )
				->where( [
						[DB::raw('YEAR(user_deposits.date)'), "=",date('Y')],
					    ['users.workshop_id', $workshop],
					    ['user_deposits.to_user', $id],
						['user_deposits.deleted_at', null]
						])
				->groupBy('y', 'm')
					->orderBy('m', 'asc')
	            ->leftJoin('users', 'users.id', '=', 'user_deposits.to_user')->get();

			// return DB::table('user_deposits')
			// 	->select( DB::raw('YEAR(user_deposits.created_at) AS y'), DB::raw('MONTH(user_deposits.created_at) AS m'), DB::raw('SUM(user_deposits.amount) as total') )
			// 	->where([
			// 		    ['user_deposits.deleted_at', null],
			// 		    ['users.workshop_id', $workshop],
			// 		    ['user_deposits.to_user', $id],
			// 		    ['users.id', $id]
			// 			])
	  //           ->leftJoin('users', 'users.id', '=', 'user_deposits.to_user')
			// 	->groupBy('y', 'm')
			// 		->orderBy('m', 'asc')
			// 	->get();
		}
	}

	public static function deposit_table()
	{
		return DB::table('user_deposits')
				->select( "user_deposits.*")
				->where([
				[ DB::raw('YEAR(date)'), "=","2018"],
				["amount","=>","10"]
				]
				)
				->groupBy('y', 'm')
				->get();
	}
}
