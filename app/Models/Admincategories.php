<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class admincategories extends Sximo  {
	
	protected $table = 'categories';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT categories.* FROM categories  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE categories.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	
	public static function insertCategory(Request $request){
		if($request->input('id') == 0){
			$id = \DB::table('categories')->insertGetId(array(
				'name' => $request->input('name'),
				'description' => $request->input('description'),
				'picture_url' => $request->input('picture_url')
			));
		} else {
			$id = $request->input('id');
			\DB::table('categories')->where('id','=',$request->input('id'))->update(array(
				'name' => $request->input('name'),
				'description' => $request->input('description'),
				'picture_url' => $request->input('picture_url')
			));
		}
		return $id;
	}

}
