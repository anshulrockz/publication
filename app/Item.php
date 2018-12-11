<?php

namespace App;

use DB;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $fillable = ['from_unit', 'to_unit', 'job_num', 'vehicle_num', 'reciever', 'security_officer', 'remark', 'uid', 'created_by', 'updated_by' ];

	public static function boot()
     {
        parent::boot();
        static::creating(function($model)
        {
            $model->created_by = Auth::id(); 
			$model->updated_by = Auth::id();
			// $model->status = 1; 
            $model->user_ip = \Request::ip();
        });
        static::updating(function($model)
        {
            $model->updated_by = Auth::id(); 
            $model->user_ip = \Request::ip();
        });       
    }

	public function UserDetails()
    {
        return $this->hasOne('App\User', 'id','created_by')->select("id", "name");
    }
	
	public function from_location()
    {
        return $this->hasOne('App\Workshop', 'id','from_unit')->select("id", "name");
    }
	
	public function to_location()
    {
        return $this->hasOne('App\Workshop', 'id','to_unit')->pluck('name');
    }

    public function ChallanDetails()
    {
        return $this->hasMany('App\ChallanDetail', 'challan_id');
    }

    public static function super_admin_all()
	{
		return DB::table('Challans')
			->select('Challans.*', 'users.name as user', DB::raw('SUM(Challan_details.cost*Challan_details.quantity) as cost'), DB::raw('SUM(Challan_details.quantity) as quantity'), DB::raw('SUM(Challan_details.sgst) as sgst'), DB::raw('SUM(Challan_details.cgst) as cgst'), DB::raw('SUM(Challan_details.igst) as igst') )
			->where([
			['Challans.deleted_at',null],
			['Challan_details.deleted_at',null]
			])
            ->leftJoin('users', 'users.id', '=', 'Challans.created_by')
            ->leftJoin('Challan_details', 'Challan_details.Challan_id', '=', 'Challans.id')
            ->orderBy('Challans.id', 'DESC')
            ->groupBy('Challans.id')
            ->get();
		
	}
	
	public static function workshop_all()
	{
		$company = Auth::user()->company_id;
		$workshop = Auth::user()->workshop_id;
		$id = Auth::user()->id;
		$user_type = Auth::user()->user_type;
		
			return DB::table('Challans')
			->select('Challans.*', 'users.name as user', DB::raw('SUM(Challan_details.cost*Challan_details.quantity) as cost'), DB::raw('SUM(Challan_details.quantity) as quantity'), DB::raw('SUM(Challan_details.sgst) as sgst'), DB::raw('SUM(Challan_details.cgst) as cgst'), DB::raw('SUM(Challan_details.igst) as igst') )
			->where([
			['users.company_id',$company],
			['users.workshop_id',$workshop],
			['users.id','=', $id],
			['users.id','!=', 1],
			['Challans.deleted_at',null],
			['Challan_details.deleted_at',null]
			])
            ->leftJoin('users', 'users.id', '=', 'Challans.created_by')
            ->leftJoin('Challan_details', 'Challan_details.Challan_id', '=', 'Challans.id')
            ->orderBy('Challans.id', 'DESC')
            ->groupBy('Challans.id')
            ->get();
		
		
	}
	
	public static function user_all()
	{
		$company = Auth::user()->company_id;
		$id = Auth::user()->id;
		$user_type = Auth::user()->user_type;
		
			return DB::table('Challans')
			->select('Challans.*', 'users.name as user', DB::raw('SUM(Challan_details.cost*Challan_details.quantity) as cost'), DB::raw('SUM(Challan_details.quantity) as quantity'), DB::raw('SUM(Challan_details.sgst) as sgst'), DB::raw('SUM(Challan_details.cgst) as cgst'), DB::raw('SUM(Challan_details.igst) as igst') )
			->where([
			['users.company_id',$company],
			['Challans.created_by',$id],
			['Challans.deleted_at',null],
			['Challan_details.deleted_at',null]
			])
            ->leftJoin('users', 'users.id', '=', 'Challans.created_by')
            ->leftJoin('Challan_details', 'Challan_details.Challan_id', '=', 'Challans.id')
            ->orderBy('Challans.id', 'DESC')
            ->groupBy('Challans.id')
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
        
        $Challans = DB::table('Challans')
			->select(DB::raw('SUM(Challan_details.cost*Challan_details.quantity) as cost'), DB::raw('SUM(Challan_details.quantity) as quantity'), DB::raw('SUM(Challan_details.sgst) as sgst'), DB::raw('SUM(Challan_details.cgst) as cgst'), DB::raw('SUM(Challan_details.igst) as igst') )
			->where([
			['Challans.created_by',$id],
			['Challans.created_for',null],
			['Challans.paid_by',$id],
			['Challans.paid_in',1],
			['Challans.status',1],
			['Challans.deleted_at',null],
			['Challan_details.deleted_at',null]
			])
            ->leftJoin('Challan_details', 'Challan_details.Challan_id', '=', 'Challans.id')
            ->first();

    	return $deposits->amt  + $user_returns->amt - $user_deposits->amt - $Challans->cost - $Challans->sgst - $Challans->cgst - $Challans->igst; 
         
	}
	
    public static function Challan_bar_chart()
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
		
			return DB::table('Challans')
				->select( DB::raw('YEAR(Challans.invoice_date) AS y'), DB::raw('MONTH(Challans.invoice_date) AS m'), DB::raw('SUM(cost*quantity) as cost'), DB::raw('SUM(sgst) as sgst'), DB::raw('SUM(cgst) as cgst'), DB::raw('SUM(igst) as igst') )
				->where( [
							[DB::raw('YEAR(Challans.invoice_date)'), "=","2018"],
							['Challan_details.deleted_at', null],
							['Challans.deleted_at', null],
							['Challans.status', 1],
						])
		  		->leftJoin('Challan_details', 'Challan_details.Challan_id', '=', 'Challans.id')
				->groupBy('y', 'm')
				->orderBy('m', 'asc')
				->get();
		}

		if($user_type == 3){ 
			return DB::table('Challans')
					->select( DB::raw('YEAR(Challans.invoice_date) AS y'), DB::raw('MONTH(Challans.invoice_date) AS m'), DB::raw('SUM(cost*quantity) as cost'), DB::raw('SUM(sgst) as sgst'), DB::raw('SUM(cgst) as cgst'), DB::raw('SUM(igst) as igst') )
					->where([
						    [ DB::raw('YEAR(Challans.invoice_date)'),"2018"],
						    ['Challan_details.deleted_at', null],
						    ['Challans.deleted_at', null],
						    ['Challans.status', 1],
						    ['users.workshop_id', $workshop],
							])
	            	->leftJoin('Challan_details', 'Challan_details.Challan_id', '=', 'Challans.id')
		            ->leftJoin('users', 'users.id', '=', 'Challans.created_by')
					->groupBy('y', 'm')
					->orderBy('m', 'asc')
					->get();
		}

		else{
			return DB::table('Challans')
				->select( DB::raw('YEAR(Challans.invoice_date) AS y'), DB::raw('MONTH(Challans.invoice_date) AS m'), DB::raw('SUM(cost*quantity) as cost'), DB::raw('SUM(sgst) as sgst'), DB::raw('SUM(cgst) as cgst'), DB::raw('SUM(igst) as igst') )
				->where( [
							[DB::raw('YEAR(Challans.invoice_date)'), "=","2018"],
							['Challans.created_by',$id],
                			['Challans.created_for',null],
                			['Challans.paid_by',$id],
                			['Challans.paid_in',1],
                			['Challans.status',1],
                			['Challans.deleted_at',null],
                			['Challan_details.deleted_at',null]
							])
		        ->leftJoin('users', 'users.id', '=', 'Challans.invoice_date')
		  		->leftJoin('Challan_details', 'Challan_details.Challan_id', '=', 'Challans.id')
				->groupBy('y', 'm')
				->orderBy('m', 'asc')
				->get();
			
		}
	}

	public static function Challan_pie_chart()
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
			return DB::table('Challans')
				->select( "Challan_details.category1", "Challan_details.category2", "Challan_details.category3", DB::raw('SUM(Challan_details.cost*Challan_details.quantity) as cost'), DB::raw('SUM(Challan_details.quantity) as quantity'), DB::raw('SUM(Challan_details.sgst) as sgst'), DB::raw('SUM(Challan_details.cgst) as cgst'), DB::raw('SUM(Challan_details.igst) as igst') )
				->where([
						['Challans.deleted_at',null],
						['Challans.status',1],
						['Challan_details.deleted_at',null]
						])
	            ->leftJoin('Challan_details', 'Challan_details.Challan_id', '=', 'Challans.id')
				->groupBy('category3')
				->get();
		}
		
		if($user_type == 3){
			return DB::table('Challans')
				->select( "Challan_details.category1", "Challan_details.category2", "Challan_details.category3", DB::raw('SUM(Challan_details.cost*Challan_details.quantity) as cost'), DB::raw('SUM(Challan_details.quantity) as quantity'), DB::raw('SUM(Challan_details.sgst) as sgst'), DB::raw('SUM(Challan_details.cgst) as cgst'), DB::raw('SUM(Challan_details.igst) as igst') )
				->where([
						['Challans.deleted_at',null],
						['Challans.status',1],
						['Challan_details.deleted_at',null],
					    	['users.workshop_id', $workshop]
						])
	            ->leftJoin('Challan_details', 'Challan_details.Challan_id', '=', 'Challans.id')
	            ->leftJoin('users', 'users.id', '=', 'Challan_details.created_by')
				->groupBy('category3')
				->get();
		}

		else{
			return DB::table('Challans')
				->select( "Challan_details.category1", "Challan_details.category2", "Challan_details.category3", DB::raw('SUM(Challan_details.cost*Challan_details.quantity) as cost'), DB::raw('SUM(Challan_details.quantity) as quantity'), DB::raw('SUM(Challan_details.sgst) as sgst'), DB::raw('SUM(Challan_details.cgst) as cgst'), DB::raw('SUM(Challan_details.igst) as igst') )
				->where([
						['Challans.deleted_at',null],
						['Challans.status',1],
						['Challan_details.deleted_at',null],
					    ['users.id', $id]
						])
	            ->leftJoin('Challan_details', 'Challan_details.Challan_id', '=', 'Challans.id')
	            ->leftJoin('users', 'users.id', '=', 'Challan_details.created_by')
				->groupBy('category3')
				->get();
		}
	}
}
