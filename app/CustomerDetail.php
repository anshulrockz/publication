<?php

namespace App;

use DB;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerDetail extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
    protected $connection = 'mysql2';
    
    public function category()
    {
        return $this->belongsTo('App\ClaimCategory', 'name', 'category');
    }

    public static function all_data()
	{
		$company = Auth::user()->company_id;
		$workshop = Auth::user()->workshop_id;
		$user_id = Auth::user()->id;
	
        if(Auth::user()->user_type == 1){
            $data = DB::connection('mysql2')->table('customer_details')
    			->select('customer_details.*', 'vehicle_details.model_num', 'vehicle_details.vehicle_num', 'claim_details.insurer_name', 'claim_job_details.invoice_amt as invoice_amt', 'claim_job_details.payment_amt as payment_amt', 'claim_job_details.job_num as job_num') //, DB::raw('SUM(claim_job_entries.entry_payment_amt) as payment_amt') ) 
    			->where([
    				['customer_details.deleted_at',null],
        			])
                ->leftJoin('claim_job_details', 'claim_job_details.customer_detail_id', '=', 'customer_details.id')
                ->leftJoin('vehicle_details', 'vehicle_details.customer_detail_id', '=', 'customer_details.id')
                ->leftJoin('claim_details', 'claim_details.customer_detail_id', '=', 'customer_details.id')
                // ->leftJoin('claim_job_entries', 'claim_job_entries.customer_detail_id', '=', 'customer_details.id')
                // ->groupBy('customer_details.id')
                ->orderBy('customer_details.id', 'DESC')
                ->get();
        }
        
        else{
            $data = DB::connection('mysql2')->table('customer_details')
    			->select('customer_details.*', 'vehicle_details.model_num', 'vehicle_details.vehicle_num', 'claim_details.insurer_name', 'claim_job_details.invoice_amt as invoice_amt', 'claim_job_details.payment_amt as payment_amt', 'claim_job_details.job_num as job_num') //, DB::raw('SUM(claim_job_entries.entry_payment_amt) as payment_amt') ) 
    			->where([
    				['customer_details.deleted_at',null],
    				['customer_details.created_by',$user_id],
    			])
                ->leftJoin('claim_job_details', 'claim_job_details.customer_detail_id', '=', 'customer_details.id')
                ->leftJoin('vehicle_details', 'vehicle_details.customer_detail_id', '=', 'customer_details.id')
                ->leftJoin('claim_details', 'claim_details.customer_detail_id', '=', 'customer_details.id')
                // ->leftJoin('claim_job_entries', 'claim_job_entries.customer_detail_id', '=', 'customer_details.id')
                
                ->orderBy('customer_details.id', 'DESC')
                ->get();
                
                
        }
        
        return $data;
		
	}
}
