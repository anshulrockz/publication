<?php

namespace App;

use DB;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetNew extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
    public function Vehicle()
    {
        return $this->hasOne('App\Vehicle', 'voucher_no','voucher_no');
    }

    public static function ajax_voucher_no($category)
	{
		$count = AssetNew::where('main_category',$category)->count();
		$voucher_no = 'PLS_NEW_'.substr(strtoupper($category),0,3).'_'.sprintf("%04d", ++$count);
		return $voucher_no;
	}
	
	public static function user_all()
	{
		$company = Auth::user()->company_id;
		$id = Auth::user()->id;
		$user_type = Auth::user()->user_type;
		if($user_type==1 || $user_type==5){
		return DB::table('asset_news')
			->select('asset_news.*', 'users.name as user')
			->where([
			['users.company_id',$company],
			//['asset_news.created_by',$id],
			['asset_news.deleted_at',null]
			])
            ->leftJoin('users', 'users.id', '=', 'asset_news.created_by')
            ->get();
            }
	else{
			return DB::table('asset_news')
			->select('asset_news.*', 'users.name as user')
			->where([
			['users.company_id',$company],
			['asset_news.created_by',$id],
			['asset_news.deleted_at',null]
			])
            ->leftJoin('users', 'users.id', '=', 'asset_news.created_by')
            ->get();
            }
		
		
	}
	
	public static function lastid()
	{
		return DB::table('assets')->orderBy('id', 'desc')->first();
	}

	public static function assetnew_pie_chart()
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

		if($user_type == 1  || $user_type == 5){
			return DB::table('asset_news')
				->select( "main_category", "sub_category", DB::raw('SUM(amount+(amount*tax/100)) as total') )
				->where([
					    ['asset_news.deleted_at', null],['asset_news.status', "In use"],
					    //['users.workshop_id', $workshop],
					    //['users.id', $id]
						])
	            ->leftJoin('users', 'users.id', '=', 'asset_news.created_by')
				->groupBy('main_category', 'sub_category')
				->get();
		}

		if($user_type == 3){
			return DB::table('asset_news')
				->select( "main_category", "sub_category", DB::raw('SUM(amount+(amount*tax/100)) as total') )
				->where([
					    ['asset_news.deleted_at', null],['asset_news.status', "In use"],
					    ['users.workshop_id', $workshop],
					    //['users.id', $id]
						])
	            ->leftJoin('users', 'users.id', '=', 'asset_news.created_by')
				->groupBy('main_category', 'sub_category')
				->get();
		}

		if($user_type == 4){
			return DB::table('asset_news')
				->select( "main_category", "sub_category", DB::raw('SUM(amount) as total') )
				->where([
					    ['asset_news.deleted_at', null],
					    ['asset_news.status', "In use"],
					    ['users.workshop_id', $workshop],
					    ['users.id', $id]
						])
	            ->leftJoin('users', 'users.id', '=', 'asset_news.created_by')
				->groupBy('main_category', 'sub_category')
				->get();
		}
	}

}
