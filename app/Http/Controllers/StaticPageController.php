<?php  namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Travel\PackageInfo;
use Validator, Input, Redirect ;
use App\Models\Travel\Geography;
use \DB;
use Cache;
use App\Models\Core\Pages;

class StaticPageController extends Controller {

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index($slug){
		$pageDB = Pages::where('alias','=',$slug)->where('status','=','enable')->first();
		if($pageDB){
			$this->data['pageTitle'] = $pageDB->title;
			$this->data['pageNote'] = $pageDB->note;
			$this->data['pageMetakey'] = ($pageDB->metakey !='' ? $pageDB->metakey : CNF_METAKEY) ;
			$this->data['pageMetadesc'] = ($pageDB->metadesc !='' ? $pageDB->metadesc : CNF_METADESC) ;
			$this->data['breadcrumb'] = 'active';
			$this->data['html'] = $pageDB->html;
			if($pageDB->access !='')
			{
				$access = json_decode($pageDB->access,true)	;
			} else {
				$access = array();
			}
			// If guest not allowed
			if($pageDB->allow_guest !=1)
			{
				$group_id = \Session::get('gid');
				$isValid =  (isset($access[$group_id]) && $access[$group_id] == 1 ? 1 : 0 );
				if($isValid ==0)
				{
					return Redirect::to('')
						->with('message', \SiteHelpers::alert('error',Lang::get('core.note_restric')));
				}
			}
			$page = 'layouts.'.CNF_THEME.'.index';
			$this->data['pages'] = 'page';
			return view($page,$this->data);
			
		} else {
			return Redirect::to('/');
		}
	}

}
