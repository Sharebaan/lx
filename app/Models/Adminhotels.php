<?php namespace App\Models;

use App\Models\Admingeographies;
use App\Models\Travel\RoomCategory;
use App\Models\Travel\CachedPrice;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use DB;

class adminhotels extends Sximo  {

	protected $table = 'hotels';
	protected $primaryKey = 'idx';

	public function __construct() {
		parent::__construct();

	}

	public static function querySelect(  ){

		return "  SELECT hotels.* FROM hotels  ";

		//return " SELECT hotels.*,hotels_update.* FROM hotels LEFT JOIN hotels_update ON hotels.idx = hotels_update.idx_hotel ";
	}

	public static function queryWhere(  ){

		return "  WHERE hotels.idx IS NOT NULL ";
	}

	public static function queryGroup(){
		return "  ";
	}

	//preiau toate temele hotelului
	public static function getThemesHotel( $hotelID,$soap_client,$update )
	{
		$themes = \DB::table('hotel_themes')->where('id_hotel', $hotelID)->where('soap_client', $soap_client)->where('update', $update)->get();
		if(empty($themes)) return '';

		$result = array();
		foreach ($themes as $key => $value) {
			$result[] = $value->text;
		}
		return implode(',', $result);
	}

	//preiau toate facilitatiile hotelului
	public static function getAmenitiesHotel( $hotelID,$soap_client,$update )
	{
		$amenities = \DB::table('hotel_amenities')->where('id_hotel', $hotelID)->where('soap_client', $soap_client)->where('update', $update)->get();
		if(empty($amenities)) return '';

		$result = array();
		foreach ($amenities as $key => $value) {
			$result[] = $value->text;
		}
		return implode(',', $result);
	}

	//preiau toate facilitatiile camerelor hotelului
	public static function getAmenitiesRoomHotel( $hotelID,$soap_client,$update )
	{
		$amenities = \DB::table('room_amenities')->where('id_hotel', $hotelID)->where('soap_client', $soap_client)->where('update', $update)->get();
		if(empty($amenities)) return '';

		$result = array();
		foreach ($amenities as $key => $value) {
			$result[] = $value->text;
		}
		return implode(',', $result);
	}

	//preiau toate descrierile detaliate ale hotelului
	public static function getDetailedDescriptionHotel( $hotelID,$soap_client,$update )
	{
		$result = array();

		$descriptions = \DB::table('detailed_descriptions')->where('id_hotel', $hotelID)->where('soap_client', $soap_client)->where('update', $update)->get();

		if(empty($descriptions)) return $result;

		foreach ($descriptions as $key => $value) {
			$result[$key] = array();
			$result[$key]['id'] = $value->id;
			$result[$key]['label'] = $value->label;
			$result[$key]['text'] = $value->text;
		}
		return $result;
	}

	//preiau toate imaginile hotelului
	public static function getImagesHotel( $hotelID,$soap_client )
	{
		$result = array();

		$images = \DB::table('file_infos')->where('id_hotel', $hotelID)->where('soap_client', $soap_client)->get();
		if(empty($images)) return $result;

		foreach ($images as $key => $value) {
			$type = '';
			switch ($value->mime_type) {
		    case "image/gif":
		        $type = ".gif";
		        break;
		    case "image/jpeg":
		        $type = ".jpeg";
		        break;
		    case "image/png":
		        $type = ".png";
		        break;
		    case "image/bmp":
		        $type = ".bmp";
		        break;
		}
			$value->image = $value->id.$type;
			$result[] = $value;
		}
		return $result;
	}

	public static function getIdHotelLocal(){
		$hotel = DB::table('hotels')
						->select('hotels.id')
			->where('hotels.soap_client', '=', 'LOCAL')
			->orderBy('hotels.id', 'DESC')
						->first();

		if(!empty($hotel)){
				return $hotel->id+1;
		}elseif(!empty(DB::table('hotels')->orderBy('hotels.id', 'DESC')->first())){
			return DB::table('hotels')->orderBy('hotels.id', 'DESC')->first()->id+1;
		}else{
			return 1;
		}

	}

	public static function updateHotel($post, $id) {
		//dd($post);
	    $data = array();
		
		//prelucrez valorile din formular
		$post['location'] = Admingeographies::getLocationPost($post);

		$biggestId = \DB::table('hotels')->orderBy('id','desc')->first();

			if(isset($post['create'])){
				// updated with biggest id to avoid conflict with imports
				$data['id'] = $biggestId->id + 1;
			}else{
				$data['id'] = $post['id'];
			}
	
	    $data['soap_client'] = $post['soap_client'];
	    $data['code'] = $post['code'];
	    $data['name'] = $post['name'];
	    $data['description'] = $post['description'];
	    $data['address'] = $post['address'];
	    $data['zip'] = $post['zip'];
	    $data['phone'] = $post['phone'];
	    $data['fax'] = $post['fax'];
	    $data['url'] = $post['url'];
	    $data['latitude'] = $post['latitude'];
	    $data['longitude'] = $post['longitude'];
	    $data['location'] = $post['location'];
	    $data['class'] = $post['class'];
	    $data['extra_class'] = $post['extra_class'];
	    $data['use_individually'] = $post['use_individually'];
	    $data['use_on_packages'] = $post['use_on_packages'];
		$data['available'] = $post['available'];
	    if(!isset($post['roomCategory'])){
	    	$post['roomCategory'] = array();
	    }
	

		if(empty($post['themes'])) $post['themes'] = array();
		if(empty($post['amenities'])) $post['amenities'] = array();
		if(empty($post['details'])) $post['details'] = array();

		//valori pentru update
		$table = with(new static)->table;
	   	$key = with(new static)->primaryKey;

		if($id == NULL )
        {//insert
            // Insert Here
			if(isset($data['createdOn'])) $data['createdOn'] = date("Y-m-d H:i:s");
			if(isset($data['updatedOn'])) $data['updatedOn'] = date("Y-m-d H:i:s");

			$data['soap_client'] = 'LOCAL';
			$data['is_local'] = 1;
			$id = \DB::table( $table)->insertGetId($data);

			//insert themes
			with(new static)->updateHotelTheme($post['id'],$post['themes'],$post['soap_client'],0);
			with(new static)->updateHotelAmenities($post['id'],$post['amenities'],$post['soap_client'],0);
			with(new static)->updateHotelDetails($post['id'],$post['details'],$post['soap_client'],0);
			self::updateHotelRoomCategories($post['id'],$post['soap_client'],$post['roomCategory']);


        } else {//update

        	//daca nu este hotel introdus local sau importat
			if( $post['soap_client'] != 'LOCAL' &&  $post['soap_client'] != 'IMPORT'){

				$table_update = 'hotels_update';
				$key_update = 'idx_hotel';

				$data['idx_hotel'] = $id;
				unset($data['id']);
				unset($data['code']);
				unset($data['soap_client']);

				//daca se suprascrie informatia hotelului
				if(empty($post['update'])){
					//dd('insert');
					\DB::table( $table_update)->insert($data);

					$data = array();
					$data['update'] = 1;
					\DB::table($table)->where($key,$id)->update($data);

				//daca daja este facut update
				}else{

		            // Update here
					// update created field if any
					if(isset($data['createdOn'])) unset($data['createdOn']);
					if(isset($data['updatedOn'])) $data['updatedOn'] = date("Y-m-d H:i:s");
					\DB::table($table)->where($key,$id)->update(array("available"=>$data['available']));
				 	\DB::table($table_update)->where($key_update,$id)->update($data);

				}

				//insert themes
				with(new static)->updateHotelTheme($post['id'],$post['themes'],$post['soap_client'],1,1);
				with(new static)->updateHotelAmenities($post['id'],$post['amenities'],$post['soap_client'],1,1);
				with(new static)->updateHotelDetails($post['id'],$post['details'],$post['soap_client'],1,1);
				self::updateHotelRoomCategories($post['id'],$post['soap_client'],$post['roomCategory']);

        	//fac update la hotel
			}else{

	            // Update here
				// update created field if any
				if(isset($data['createdOn'])) unset($data['createdOn']);
				if(isset($data['updatedOn'])) $data['updatedOn'] = date("Y-m-d H:i:s");
				//dd($id,$data);
				 \DB::table($table)->where($key,$id)->update($data);

				//insert themes
				with(new static)->updateHotelTheme($post['id'],$post['themes'],$post['soap_client'],0,1);
				with(new static)->updateHotelAmenities($post['id'],$post['amenities'],$post['soap_client'],0,1);
				with(new static)->updateHotelDetails($post['id'],$post['details'],$post['soap_client'],0,1);
				self::updateHotelRoomCategories($post['id'],$post['soap_client'],$post['roomCategory']);
			}

        }

        return $id;
	}


	public static function updateHotelRoomCategories($id,$soapClient,$roomCategories){
		//print_r($roomCategories);die;
		//dd($id);
		foreach($roomCategories as $roomCategory){
			if($roomCategory['id'] == 0 && $roomCategory['deleted'] == 0){
				$idx = \DB::table('room_categories')->insertGetId(array(
							'id' => 0,
							'name' => $roomCategory['name'],
							'id_hotel' => $id,
							'soap_client' => $soapClient,
							'update' => 0
				));
				\DB::table('room_categories')->where('idx','=',$idx)->update(array('id' => $idx));
			} else if($roomCategory['id'] != 0 && $roomCategory['deleted'] == 1){
				CachedPrice::where('id_room_category','=',$roomCategory['id'])->where('soap_client','=',$soapClient)->delete();
				RoomCategory::where('id','=',$roomCategory['id'])->where('soap_client','=',$soapClient)->delete();
			} else if($roomCategory['id'] != 0 && $roomCategory['deleted'] == 0){
				$tmp = RoomCategory::where('id','=',$roomCategory['id'])->where('soap_client','=',$soapClient)->first();
				$tmp->name = $roomCategory['name'];
				$tmp->save();
			}
		}
		//dd($roomCategories);
	}




	public static function find($id){

		$hotel = DB::table('hotels')
            ->select('hotels.*')
			->where('hotels.idx', '=', $id)
            ->first();
		$hotel = (array)$hotel;

		$hotel_update = DB::table('hotels_update')
            ->select('hotels_update.*')
			->where('hotels_update.idx_hotel', '=', $id)
            ->first();
		$hotel_update = (array)$hotel_update;
		unset($hotel_update['idx']);
		unset($hotel_update['idx_hotel']);


		$hotel = array_replace($hotel, $hotel_update);
		$hotel = (object)$hotel;

		//print_r($hotel);die;

		return $hotel;
	}


	public static function updateHotelTheme($id_hotel,$themes,$soap_client,$update,$delete=0) {

		$table = 'hotel_themes';
		$key = 'id_hotel';

		if(!empty($delete))
			\DB::table($table)->where($key,$id_hotel)->where('update',$update)->delete();

		foreach ($themes as $key => $value) {
			$data = array();
			$data['id_hotel'] = $id_hotel;
			$data['text'] = $value;
			$data['soap_client'] = $soap_client;
			$data['update'] = $update;
			\DB::table($table)->insert($data);
		}
		return true;
	}

	public static function updateHotelAmenities($id_hotel,$amenities,$soap_client,$update,$delete=0) {

		$table = 'hotel_amenities';
		$key = 'id_hotel';

		if(!empty($delete))
			\DB::table($table)->where($key,$id_hotel)->where('update',$update)->delete();

		foreach ($amenities as $key => $value) {
			$data = array();
			$data['id_hotel'] = $id_hotel;
			$data['text'] = $value;
			$data['soap_client'] = $soap_client;
			$data['update'] = $update;
			\DB::table($table)->insert($data);
		}
		return true;
	}



	public static function updateHotelDetails($id_hotel,$details,$soap_client,$update,$delete=0) {

		$table = 'detailed_descriptions';
		$key = 'id_hotel';

		if(!empty($delete))
			\DB::table($table)->where($key,$id_hotel)->where('update',$update)->delete();

		foreach ($details as $key => $value) {
			$data = array();
			$data['id_hotel'] = $id_hotel;
			$data['text'] = $value->text;
			$data['label'] = $value->label;
			$data['index'] = $value->index;
			$data['soap_client'] = $soap_client;
			$data['update'] = $update;
			\DB::table($table)->insert($data);
		}
		return true;
	}


	public static function getRoomCategories($id,$soap_client){
		return RoomCategory::where("id_hotel","=",$id)->where("soap_client","=",$soap_client)->get();
	}


	public static function updateHotelImage($id,$data,$type=0) {

		$table = 'file_infos';
		$key = 'id';
		//dd($data);
		// print_r($data);die;
		if($type==2){//delete
			\DB::table($table)->where($key,$id)->delete();
		}else if($type==1){//update
			\DB::table($table)->where($key,$id)->update($data);
		}else{
			//dd($data);
			$q = \DB::table($table)->insertGetId($data);
			//dd($q);
		}
		return true;
	}

	public static function getNewIdImage() {
		$img = DB::table('file_infos')
            ->select('id')
			->orderBy('id', 'DESC')
            ->first();
		return $img->id+1;
	}


}
