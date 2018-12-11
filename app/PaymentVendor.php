<?php

namespace App;

use DB;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentVendor extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public static function lastid()
	{
		return DB::table('payment_vendors')->orderBy('id', 'desc')->first();
	}

    public function VendorDetails()
    {
        return $this->hasOne('App\Vendors', 'id','vendor_id');
    }
    
    public static function all_details()
	{
		$company = Auth::user()->company_id;
		$workshop = Auth::user()->workshop_id;
		$id = Auth::user()->id;
		$user_type = Auth::user()->user_type;
		
		if($user_type == 1  || $user_type == 5){
			return DB::table('transactions')
			->select('transactions.*', 'vendors.name as user', 'vendors.address', 'vendors.uid', 'workshops.name as location')
			->where([
			['transactions.deleted_at',null],
					    //['users.workshop_id', $workshop],
					    //['transactions.created_for', $id],
					    //['users.id', $id]
						])
	            ->leftJoin('vendors', 'vendors.id', '=', 'transactions.vendor_id')
	            ->leftJoin('workshops', 'workshops.id', '=', 'vendors.location')
	            ->get();
		}

       	if($user_type == 3){
			return DB::table('transactions')
				->select('transactions.*', 'vendors.name as user', 'vendors.address', 'vendors.uid')
				->where([
				['transactions.deleted_at',null],
				['transactions.created_by', $id],
				['vendors.location', $workshop],
				])
	            ->leftJoin('vendors', 'vendors.id', '=', 'transactions.vendor_id')
	            ->get();
		}

		else{
			return DB::table('transactions')
				->select('transactions.*', 'vendors.name as user', 'vendors.address', 'vendors.uid')
				->where([
						['transactions.deleted_at',null],
						['transactions.created_by', $id],
						])
	            ->leftJoin('vendors', 'vendors.id', '=', 'transactions.vendor_id')
	            ->get();
		}
	}
}
