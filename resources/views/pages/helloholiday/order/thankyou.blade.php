
<?php 
$d = Session::get('2perfomant_data'); 

if($d == null){header("Location: http://helloholidays.ro");die;}

//if($d['type'] == 0){
	$p = \DB::table('packages')->where('id','=',$d['r']->id_package)->first(); 
	$category = \DB::table('package_categories')->where('id_package','=',$p->id)->get();
	$curs = \DB::table('curseuro')->where('id','=','1')->first()->curseuro;
	$commission = false;
	$commissionPrice='';
	
	if(count($category) == 2){
		$categoryId = $category[1]; 
	}else{
		$categoryId = $category[0];
	}
	
	$cur = \DB::table('cached_prices')->where('id_package',$d['r']->id_package)->first();
	
	function convert($price,$cur,$curs){
		
		if($cur->currency == 1){
			$p = $price / $curs;
			return $p;
		}else{
			return $price;
		}
	}
	
	$cp = convert($d['r']->price,$cur,$curs);
	//dd($categoryId->id_category);
	$curs = \DB::table('curseuro')->where('id','=',1)->first()->curseuro;

	if($categoryId->id_category == 11){
		$commission = true;
		
			$commissionPrice = 5;
		
		
	}elseif($categoryId->id_category == 12){
		$commission = true;
		
		$commissionPrice = ((5/$curs)*100)/$cp;
		
		
	}elseif($categoryId->id_category == 13){
		$commission = true;
		
		
		$commissionPrice = 10*100/$cp;
		
	}elseif($categoryId->id_category == 14){
		$commission = true;
		
		$commissionPrice = 15*100/$cp;
		
		
	}elseif($categoryId->id_category == 15){
		$commission = true;
		
		$commissionPrice = 20*100/$cp;
	
	}else{
		$commission = true;
		
		$commissionPrice = $cp * 0.05;
	}
	
	
	$price = $d['r']->price;

	if(!empty($cur)){
		if($cur->currency == 1){
			$price = $d['r']->price / $curs; 
		}
	}
	
	
?>	

@if($commission == true)
	<iframe height='1' width='1' scrolling='no' marginheight='0' marginwidth='0' frameborder='0' src="//event.2performant.com/events/salecheck?amount={{number_format((float)$cp,2,'.','')}}&campaign_unique=20aab2046&com_percent={{number_format((float)$commissionPrice,2,'.','')}}&confirm=4c55d3b25e0cabfd&description={{str_replace(' ','_',$p->name)}}&transaction_id={{$d['r']->id}}"></iframe>
@endif


<div class="sixteen columns">
	<div class="ba-thankyou container clearfix" style="width: 100%;margin-bottom:200px;">
		<div class="ba-order-rooms"  style="font-size: 16px;margin-top: 20px;">
            <center>Multumim pentru rezervare</center>
						<center>Vei fi contactat de echipa noastra in cel mai scurt timp posibil.</center>
		  <center><a href="http://helloholidays.ro" class="ba-blue-button-m" style="width: 200px;">Continua</a></center>
       	</div>
	</div>
</div>
<?php Session::forget('2perfomant_data') ?>
