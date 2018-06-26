<?php
namespace App\Helpers;
use DB;
use Cart;
class CommonClass{
	
	static function geStates(){
		return DB::table('states')->pluck('state_name','id')->toArray();
	}

	static function getLga(){
		return DB::table('lgas')->pluck('lga_name','id')->toArray();
	}

	static function LGAList($state_id){
		$lgalist= DB::table('lgas')->where('state_id',$state_id)->pluck('lga_name','id');
		return json_encode($lgalist);
	}

	static function getWards(){
		return DB::table('wards')->pluck('ward_name','id')->toArray();
	}

	static function WardList($lga_id){
		$list= DB::table('wards')->where('lga_id',$lga_id)->pluck('ward_name','id');
		return json_encode($list);
	}
	
	static function getCategory(){
		return DB::table('categories')->pluck('category_name','id')->toArray();
	}

	static function getGsmNet(){
		return DB::table('gsms')->pluck('gsm_name','id')->toArray();
	}

}