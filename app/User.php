<?php

namespace App;

use DB;
use Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_type', 'name', 'email', 'mobile', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public static function AllUsers()
    {
    	$company_id = Auth::user()->company_id;
    	$workshop_id = Auth::user()->workshop_id;
		$id = Auth::user()->id;
		$user_type = Auth::user()->user_type;
		
    	if(Auth::user()->user_type==1 || Auth::user()->user_type==5)
    	{
	        return DB::table('users')
				->select('users.*', 'workshops.name as workshop', 'companies.name as company', 'designations.name as designation')
				->where([
				//['users.deleted_at',null],
                ['users.id','!=',$id],
				])
	            ->leftJoin('companies', 'companies.id', '=', 'users.company_id')
                ->leftJoin('workshops', 'workshops.id', '=', 'users.workshop_id')
	            ->leftJoin('designations', 'designations.id', '=', 'users.designation_id')
	            ->get();
		}
		
		if(Auth::user()->user_type==2)
    	{
	        return DB::table('users')
				->select('users.*', 'workshops.name as workshop', 'companies.name as company')
				->where([
				//['users.deleted_at',null],
				['users.company_id',$company_id],
                ['users.id','!=',$id],
				])
	            ->leftJoin('companies', 'companies.id', '=', 'users.company_id')
	            ->leftJoin('workshops', 'workshops.id', '=', 'users.workshop_id')
	            ->get();
		}
		
		if(Auth::user()->user_type==3)
    	{
	        return DB::table('users')
				->select('users.*', 'workshops.name as workshop', 'companies.name as company')
				->where([
				//['users.deleted_at',null],
				['users.company_id',$company_id],
				['users.workshop_id',$workshop_id],
                ['users.id','!=',$id],
				])
	            ->leftJoin('companies', 'companies.id', '=', 'users.company_id')
	            ->leftJoin('workshops', 'workshops.id', '=', 'users.workshop_id')
	            ->get();
		}
    }
    
    public static function activateUser($id)
    {
        return DB::table('users')
                ->where([
                        ['id',$id],
                        ])
                ->update(['deleted_at' => null]);
    }

    public function EmployeeType()
    {
        return $this->hasOne('App\EmployeeType', 'id','user_type');
    }
    
    public function Workshop()
    {
        return $this->hasOne('App\Workshop', 'id','workshop_id');
    }
    
    public function Designation()
    {
        return $this->hasOne('App\Designation', 'id','designation_id');
    }
    
}
