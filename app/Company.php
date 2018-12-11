<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Company extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = ['name', 'contact_person', 'mobile', 'email', 'address', 'transport', 'gst', 'type', 'state_id', 'city_id'];

	public static function boot()
     {
        parent::boot();
        static::creating(function($model)
        {
            $model->created_by = Auth::id(); 
			$model->updated_by = Auth::id();
			$model->status = 1;
            $model->user_sys = \Request::ip();
        });
        static::updating(function($model)
        {
            $model->updated_by = Auth::id(); 
            $model->user_ip = \Request::ip();
        });       
    }
}
