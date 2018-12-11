<?php

namespace App;

use DB;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentOther extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public static function lastid()
	{
		return DB::table('payment_others')->orderBy('id', 'desc')->first();
	}

    public function UserDetails()
    {
        return $this->hasOne('App\User', 'id','created_by');
    }
    
    public static function all_payment_others()
	{
		$company = Auth::user()->company_id;
		$workshop = Auth::user()->workshop_id;
		$id = Auth::user()->id;
		$user_type = Auth::user()->user_type;
		
		if($user_type == 1  || $user_type == 5){
			return DB::table('payment_others')
			->select('payment_others.*', 'users.name as user') //, DB::raw('COUNT(job_details.id) as job_details_id'))
			->where([
					['payment_others.deleted_at',null],
					])
            ->leftJoin('users', 'users.id', '=', 'payment_others.created_by')
            // ->leftJoin('job_details', 'job_details.parent_id', '=', 'payment_others.id')
			->get();
		}

       	if($user_type == 3){
			return DB::table('payment_others')
				->select('payment_others.*', 'users.name as user')
				->where([
				['payment_others.deleted_at',null],
				['payment_others.created_by', $id],
				['users.workshop_id', $workshop],
				])
	            ->leftJoin('users', 'users.id', '=', 'payment_others.created_by')
	            ->get();
		}

		else{
			return DB::table('payment_others')
				->select('payment_others.*', 'users.name as user')
				->where([
						['payment_others.deleted_at',null],
						['payment_others.created_by', $id],
						])
	            ->leftJoin('users', 'users.id', '=', 'payment_others.created_by')
	            ->get();
		}
	}
	
}
