<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubAsset extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
    public static function asset_categoriesJoin()
	{
		return DB::table('sub_assets')
			->select('sub_assets.*', 'asset_categories.name as asset_categories_name')
			->where([
			['sub_assets.deleted_at',null],
			['asset_categories.deleted_at',null]
			])
            ->leftJoin('asset_categories', 'asset_categories.id', '=', 'sub_assets.asset_category')
            ->get();
		
	}
}
