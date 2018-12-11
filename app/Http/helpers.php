<?php

if (! function_exists('getFromID')) {
    function getFromID($id, $name){
    	$table = "$name";
		$temp = DB::table($table)
				->select('name')
				->where([
						['id',$id],
						['deleted_at',null]
						])
				->first();
		if(count($temp)>0)
		return $temp->name;
		else
		return " ";
	}
}

if (! function_exists('getAllFromID')) {
    function getAllFromID($id, $name){
    	$table = "$name";
		$temp = DB::table($table)
				->select('*')
				->where([
						['id',$id],
						['deleted_at',null]
						])
	            ->first();
		return $temp;
	}
}

if (! function_exists('getLastRow')) {
    function getLastRow($tablename){
    	$table = "$tablename";
		return DB::table($tablename)->orderBy('id', 'desc')->first();
	}
}

