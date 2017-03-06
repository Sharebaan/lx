<?php  namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Travel\PackageInfo;
use Validator, Input, Redirect ;
use App\Models\Travel\Geography;
use \DB;
use Cache;

class HomeController extends Controller {

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index( Request $request )
	{
		$featuredPackages = PackageInfo::leftJoin('package_categories',function($join){
			$join->on('package_categories.id_package','=','packages.id');
			$join->on('package_categories.soap_client','=','packages.soap_client');
		})->where('package_categories.id_category','=',6)->take(6)->get();
		
		$this->data['featuredPackages'] = $featuredPackages;
		$this->data['pageTitle'] = 'Home';
		$this->data['pageNote'] = 'Welcome To Our Site';
		$this->data['breadcrumb'] = 'inactive';
		$this->data['pageMetakey'] =  CNF_METAKEY ;
		$this->data['pageMetadesc'] = CNF_METADESC ;
		$this->data['pages'] = 'pages.'.CNF_THEME.'.home';
		$page = 'layouts.'.CNF_THEME.'.index';

		return view($page,$this->data);


	}



	public function  getLang($lang='en')
	{
		\Session::put('lang', $lang);
		return  Redirect::back();
	}

	public function  getSkin($skin='sximo')
	{
		\Session::put('themes', $skin);
		return  Redirect::back();
	}

	public  function  postContact( Request $request)
	{

		$this->beforeFilter('csrf', array('on'=>'post'));
		$rules = array(
				'name'		=>'required',
				'subject'	=>'required',
				'message'	=>'required|min:20',
				'sender'	=>'required|email'
		);
		$validator = Validator::make(Input::all(), $rules);
		if ($validator->passes())
		{

			$data = array('name'=>$request->input('name'),'sender'=>$request->input('sender'),'subject'=>$request->input('subject'),'notes'=>$request->input('message'));
			$message = view('emails.contact', $data);

			$to 		= 	CNF_EMAIL;
			$subject 	= $request->input('subject');
			$headers  	= 'MIME-Version: 1.0' . "\r\n";
			$headers 	.= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers 	.= 'From: '.$request->input('name').' <'.$request->input('sender').'>' . "\r\n";
				//mail($to, $subject, $message, $headers);

			return Redirect::to($request->input('redirect'))->with('message', \SiteHelpers::alert('success','Thank You , Your message has been sent !'));

		} else {
			return Redirect::to($request->input('redirect'))->with('message', \SiteHelpers::alert('error','The following errors occurred'))
			->withErrors($validator)->withInput();
		}
	}

	public function clearCache(Request $request){
		Cache::flush();
		return Redirect::to('dashboard')->with('messagetext','Cache has been cleared!')->with('msgstatus','success');
	}

}
