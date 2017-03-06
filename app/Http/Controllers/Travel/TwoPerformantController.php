<?php

namespace App\Http\Controllers\Travel;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Models\Travel\TwoPerformant;

class TwoPerformantController extends Controller
{
    
    public function categorie(Request $req)
    {
    	$what = $this->what($req->type);
        $packagesData = TwoPerformant::getListingResultsByFilteringOptions($what[0],$req->type,$what[1],$what[2]);  
        
        \Excel::create($req->type, function($excel) use ($packagesData) {
            $excel->sheet('sheet1', function($sheet) use ($packagesData) {
                $sheet->fromArray($packagesData);
            });
        })->download('csv');      
        
    }

    private function what($id){
        switch ($id) {
            case 'revelion':
                    return [8,null,null];
                break;
            
            case 'excursii':
                    return [7,null,null];
                break;

            case 'citybreak':
                    return [9,null,null];
                break;

            case 'craciun':
                    return [10,null,null];
                break;  
                
            case 'romaniazi':
            		return [7,161,null];
            	break;
           
            case 'romania':
            		return [0,161,1];
            	break;
            	
            case 'bulgariazi':
            		return [7,133,null];
            	break;
            	
            case 'paste2017':
            		return [11,null,null];
            	break;
        }
    }

    
}
