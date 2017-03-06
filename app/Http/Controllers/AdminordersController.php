<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Adminorders;
use Validator;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;


class AdminordersController extends Controller
{

  protected $layout = "layouts.main";
  protected $data = array();
  public $module = 'adminorders';
  static $per_page	= '10';

  public function __construct()
  {

    $this->beforeFilter('csrf', array('on'=>'post'));
    $this->model = new Adminorders();

    $this->info = $this->model->makeInfo( $this->module);
    $this->access = $this->model->validAccess($this->info['id']);

    $this->data = array(
      'pageTitle'	=> 	$this->info['title'],
      'pageNote'	=>  $this->info['note'],
      'pageModule'=> 'adminorders',
      'return'	=> self::returnUrl()

    );

  }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex( Request $request )
    {
        //dd('show orders');
        if($this->access['is_view'] ==0)
    			return redirect('dashboard')
    				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');

    		$sort = (!is_null($request->input('sort')) ? $request->input('sort') : 'id');
    		$order = (!is_null($request->input('order')) ? $request->input('order') : 'asc');
    		// End Filter sort and order for query
    		// Filter Search for query
    		$filter = (!is_null($request->input('search')) ? $this->buildSearch() : '');


    		$page = $request->input('page', 1);
    		$params = array(
    			'page'		=> $page ,
    			'limit'		=> (!is_null($request->input('rows')) ? filter_var($request->input('rows'),FILTER_VALIDATE_INT) : static::$per_page ) ,
    			'sort'		=> $sort ,
    			'order'		=> $order,
    			'params'	=> $filter,
    			'global'	=> (isset($this->access['is_global']) ? $this->access['is_global'] : 0 )
    		);
    		// Get Query

        $results = $this->model->getRows( $params );
        foreach($results['rows'] as $k=>$v){
          if($v->rezervare == 1){
              switch ($v->paytype) {
                case 'romcard':
                    $pay = \DB::table('romcard_orders')->where('rezervare_id','=',$v->id)->first();
                    $v->ORDER = $pay->ORDER;
                    $v->AMOUNT = $pay->AMOUNT;
                    $v->CURRENCY = $pay->CURRENCY;
                    $v->RRN = $pay->RRN;
                    $v->INT_REF = $pay->INT_REF;
                    $v->TERMINAL = $pay->TERMINAL;
                  break;

                default:
                    continue;
                  break;
              }
          }
        }

    		// Build pagination setting
    		$page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;
    		$pagination = new Paginator($results['rows'], $results['total'], $params['limit']);
    		$pagination->setPath('adminorders');

    		$this->data['rowData']		= $results['rows'];
    		// Build Pagination
    		$this->data['pagination']	= $pagination;
    		// Build pager number and append current param GET
    		$this->data['pager'] 		= $this->injectPaginate();
    		// Row grid Number
    		$this->data['i']			= ($page * $params['limit'])- $params['limit'];
    		// Grid Configuration
    		$this->data['tableGrid'] 	= $this->columnSort($this->info['config']['grid']);
    		$this->data['tableForm'] 	= $this->columnSort($this->info['config']['forms']);
    		$this->data['colspan'] 		= \SiteHelpers::viewColSpan($this->info['config']['grid']);

    		// Group users permission
    		$this->data['access']		= $this->access;
    		// Detail from master if any

    		// Master detail link if any
    		$this->data['subgrid']	= (isset($this->info['config']['subgrid']) ? $this->info['config']['subgrid'] : array());
        $this->data['curs'] = \DB::table('curseuro')->where('id','=',1)->first()->curseuro;
        return view('adminorders.index',$this->data);
    }


    private function columnSort($col){
      $ar=[];
      foreach($col as $k=>$v){
          switch ($v['field']) {
            case 'id':
              $ar[$k] = $v;
            break;

            case 'suma':
              $ar[$k] = $v;
            break;

            case 'payment_type':
              $ar[$k] = $v;
            break;

            case 'lname':
              $ar[$k] = $v;
            break;

            case 'fname':
              $ar[$k] = $v;
            break;

            case 'paytype':
              $ar[$k] = $v;
            break;

            case 'achitat':
              $ar[$k] = $v;
            break;

            case 'refuzat':
              $ar[$k] = $v;
            break;
            
            case 'error':
            	$ar[$k] = $v;
            break;	

            case 'created_at':
              $ar[$k] = $v;
            break;
          }
      }
      return array_values($ar);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUpdate(Request $request, $id = null)
    {
      if($id =='')
  		{
  			if($this->access['is_add'] ==0 )
  			return Redirect::to('dashboard')->with('messagetext',\Lang::get('core.note_restric'))->with('msgstatus','error');
  		}

  		if($id !='')
  		{
  			if($this->access['is_edit'] ==0 )
  			return Redirect::to('dashboard')->with('messagetext',\Lang::get('core.note_restric'))->with('msgstatus','error');
  		}

  		$this->data["clients"] = \DB::table('plata_rezervare_clienti')
      ->where('plata_rezervare_clienti.rezervare_id','=',$id)->get();
      $this->data["pay"] = \DB::table('romcard_orders')
      ->where('romcard_orders.rezervare_id','=',$id)->first();

      $rez = $this->model->find($id);

      $this->data["order"] = $rez;
      $this->data['curs'] = \DB::table('curseuro')->where('id','=',1)->first()->curseuro;

      if($rez->hotel == 0){
        $this->data['package'] = \DB::table('packages')->where('idx','=',$rez->id_bookedpackage)->first();
      }else{
        $this->data['package'] = \DB::table('packages')->where('id_hotel','=',$rez->id_bookedpackage)->first();
      }

      return view('adminorders.form',$this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getShow(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function postSave($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function postDelete(Request $request)
    {
      if($this->access['is_remove'] ==0)
        return Redirect::to('dashboard')
          ->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');
      if(count($request->input('ids')) >=1)
      {
        //$this->model->destroy($request->input('ids'));
        foreach($request->input('ids') as $v){
          $romcard = \DB::table('romcard_orders')->where('rezervare_id','=',$v);
          $plrezclienti = \DB::table('plata_rezervare_clienti')->where('rezervare_id','=',$v);
          if(!empty($romcard->get())){
            $romcard->delete();
          }
          if(!empty($plrezclienti->get())){
            $plrezclienti->delete();
          }
          $this->model->find($v)->delete();
        }

        \SiteHelpers::auditTrail( $request , "ID : ".implode(",",$request->input('ids'))."  , Has Been Removed Successfull");
        return redirect('adminorders')
              ->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus','success');

      } else {
        return redirect('adminorders')
              ->with('messagetext','No Item Deleted')->with('msgstatus','error');
      }
    }

    public function postCurseuro(Request $req){
      $v = Validator::make($req->all(),[
        'curseuro'=>'required|numeric'
      ]);
      if($v->fails()){return redirect()->back()->withErrors($v)->withInput();}
      \DB::table('curseuro')->where('id','=',1)->update(['curseuro'=>$req->curseuro]);
      return redirect()->back()->with('curs',$req->curseuro);
    }

}