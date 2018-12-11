<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Workshop extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
    public static function AllWorkshops()
    {
        return DB::table('workshops')
			->select('workshops.*', 'companies.name as company')
			->where([
			['workshops.deleted_at',null],
			['companies.deleted_at',null],
			])
            ->leftJoin('companies', 'companies.id', '=', 'workshops.company')
            ->get();
    }
    
    public function Company()
    {
        return $this->belongsTo('App\Company', 'wwwid');
    }
    
}
