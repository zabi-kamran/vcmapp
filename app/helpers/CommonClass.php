<?php
namespace App\Helpers;
use DB;
use Cart;
use Illuminate\Support\Facades\Auth;

class CommonClass{
	
	static function geStates(){


        if(Auth::user()->isadmin==1)
        {
		return DB::table('states')->pluck('state_name','id')->toArray();
        }
        else
        {

            $state_id = DB::table('state_users')->where('user_id',Auth::id())->first()->state_id;
            return DB::table('states')->where('id',$state_id)->pluck('state_name','id')->toArray();
        }
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

        if(Auth::user()->isadmin==1)
        {

            $data= DB::table('categories')->select('categories.category_name as category_name','categories.id as id')
                ->get()
                ->toArray();

             return $data;
        }
        else
        {
            $data= DB::table('categories')
                ->select('categories.category_name as category_name','categories.id as id')
                ->join('user_categories','user_categories.cat_id','=','categories.id')
                ->get()
               ->toArray();

            return $data;

        }


	}

	static function getGsmNet(){
		return DB::table('gsms')->pluck('gsm_name','id')->toArray();
	}

}