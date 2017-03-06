@extends('layouts.app')

@section('content')
	<link rel="stylesheet" href="/css/dropzone.css" media="screen" title="no title" charset="utf-8">
<script type="text/javascript" src="/js/dropzone.js"></script>
<div class="page-content row">
	<!-- Page header -->
	<div class="page-header">
		<div class="page-title">
			<h3> {{ $pageTitle }} <small>{{ $pageNote }}</small></h3>
		</div>
		<ul class="breadcrumb">
			<li>
				<a href="{{ URL::to('dashboard') }}">{{ Lang::get('core.home') }}</a>
			</li>
			<li>
				<a href="{{ URL::to('adminhotels?return='.$return) }}">{{ $pageTitle }}</a>
			</li>
			<li class="active">
				{{ Lang::get('core.addedit') }}
			</li>
		</ul>
	</div>

	<div class="page-content-wrapper m-t">

		<div class="sbox animated fadeInRight">
			<div class="sbox-title">
				<h4><i class="fa fa-table"></i> <?php echo $pageTitle; ?>
				<small>{{ $pageNote }}</small></h4>
			</div>
			<div class="sbox-content">

					

				<div class="col-md-6">
					@if(!empty($order->bookpackage_search))
						<?php $b = json_decode($order->bookpackage_search);$r = json_decode($b->rooms); ?>
					<div class="panel panel-warning">
						<div class="panel-heading">
							Order Details
						</div>
						<div class="panel-body">
							<div class="sbox-content" style="background:#fff;">

								<table class="table table-striped table-bordered" >
									<tbody>

											<tr>
												<td width='30%' class='label-view text-right'>Check In</td>
												<td>{{$b->check_in}}	 </td>

											</tr>

											<tr>
												<td width='30%' class='label-view text-right'>Check Out</td>
												<td>{{$b->check_out}} </td>

											</tr>


											<tr>
												<td width='30%' class='label-view text-right'>Durata</td>
												<td>{{$b->duration}} </td>
											</tr>

											<tr>
												<td width='30%' class='label-view text-right'>Camere</td>
												<td>{{count($r)}} x {{$b->room_category}} pentru {{$r[0]->Adults == 1? "1 Adult":$r[0]->Adults." Adulti"}} {{$r[0]->ChildAges == 0? "":($r[0]->ChildAges == 1?"si 1 Copil":"si ".$r[0]->ChildAges." Copii")}}</td>
											</tr>

											<tr>
												<td width='30%' class='label-view text-right'>Tipul {{count($r)== 1 ? "camerei" : "camerelor"}}</td>
												<td>{{$b->room_category}} </td>
											</tr>

											<tr>
												<td width='30%' class='label-view text-right'>Tipul mesei</td>
												<td>{{$b->meal_plan}} </td>
											</tr>

											<tr>
												<td width='30%' class='label-view text-right'>Tip transport</td>
												<td>@if($package->is_flight == 1)
								            Avion
								            @elseif($package->is_bus == 1)
								            Autocar
								            @else
								            Individual
								            @endif </td>
											</tr>

											<tr>
												<td width='30%' class='label-view text-right'>Total</td>
												<td>{{$b->price}}&euro; </td>
											</tr>

									</tbody>
								</table>
							</div>

						</div>
					</div>
					@endif
					<div class="panel panel-info">
						<div class="panel-heading">
							Client Details
						</div>
						<div class="panel-body">

							<div class="sbox-content" style="background:#fff;">

								<table class="table table-striped table-bordered" >
									<tbody>

											<tr>
												<td width='30%' class='label-view text-right'>FirstName</td>
												<td>{{$order->fname}}	 </td>

											</tr>

											<tr>
												<td width='30%' class='label-view text-right'>LastName</td>
												<td>{{$order->lname}} </td>

											</tr>

											<tr>
												<td width='30%' class='label-view text-right'>Address</td>
												<td>{{ $order->address }} </td>

											</tr>

											<tr>
												<td width='30%' class='label-view text-right'>City</td>
												<td>{{ $order->city }} </td>
											</tr>

											<tr>
												<td width='30%' class='label-view text-right'>Zone</td>
												<td>{{ $order->zone }} </td>
											</tr>

											<tr>
												<td width='30%' class='label-view text-right'>Country</td>
												<td>{{ $order->country }} </td>
											</tr>

											<tr>
												<td width='30%' class='label-view text-right'>Email</td>
												<td>{{ $order->email }} </td>
											</tr>

											<tr>
												<td width='30%' class='label-view text-right'>Phone</td>
												<td>{{ $order->phone }} </td>
											</tr>

												<tr>
													<td width='30%' class='label-view text-right'>Total</td>
													<td>{{ $order->suma }} </td>
												</tr>
												<tr>
													<td width='30%' class='label-view text-right'>Currency</td>
													<td>{{ $order->payment_type }} </td>
												</tr>

												<tr>
													<td width='30%' class='label-view text-right'>Payment Type</td>
													<td>{{ $order->paytype }} </td>
												</tr>
											
											@if($order->rezervare == 1)
												@if($order->paytype == "Romcard")
														<?php
														/*if(strlen($pay->ORDER) < 18){
															$amountzero = 17 - strlen($pay->ORDER);
															for($i=0;$i<$amountzero;$i++){
																$pay->ORDER='0'.$pay->ORDER;
															}
														}*/
														$fields = array(
														 'ORDER'=>substr($pay->ORDER,2),
														 'AMOUNT'=>$pay->AMOUNT,
														 'CURRENCY'=>$pay->CURRENCY,
														 'RRN'=>$pay->RRN,
														 'INT_REF'=>$pay->INT_REF,
														 'TRTYPE'=>'21',
														 'TERMINAL'=>$pay->TERMINAL,
														 'TIMESTAMP'=>gmdate('YmdHis'),
														 'NONCE'=>md5(time()),
														 'BACKREF'=>'http://helloholidays.ro/paymentdone'
														);

														$fields2 = array(
														 'ORDER'=>substr($pay->ORDER,2),
														 'AMOUNT'=>$pay->AMOUNT,
														 'CURRENCY'=>$pay->CURRENCY,
														 'RRN'=>$pay->RRN,
														 'INT_REF'=>$pay->INT_REF,
														 'TRTYPE'=>'24',
														 'TERMINAL'=>$pay->TERMINAL,
														 'TIMESTAMP'=>gmdate('YmdHis'),
														 'NONCE'=>md5(time()),
														 'BACKREF'=>'http://helloholidays.ro/paymentdone'
														);


														$cheie='A479A9DD6B21C0A015F5B88ED46A45D1';
														$p_sign='';
														$p_sign2="";
														foreach($fields as $key=>$value) {
														 if ($value!='')$p_sign .= strlen($value).$value;
														 else $p_sign.='-';
														}
														foreach($fields2 as $key=>$value) {
														 if ($value!='')$p_sign2 .= strlen($value).$value;
														 else $p_sign2.='-';
														}

														$hex_key = pack("H*", $cheie);
														$p_sign = strtoupper(hash_hmac('sha1', $p_sign, $hex_key));
														$p_sign2 = strtoupper(hash_hmac('sha1', $p_sign2, $hex_key));

														$data = [
															'f1'=>[$fields,$p_sign],
															'f2'=>[$fields2,$p_sign2]
														];

														$f = json_encode($data);
														?>
											@if($order->error==0)			
												@if($order->achitat==0 && $order->refuzat==0 && $order->error==0)
													<tr>
														<td width='30%' class='label-view text-right'>Achitat</td>
														<td>
															@if($order->achitat==0)
																<a href="" class="tips btn btn-xs btn-primary {{$order->id}} confpay" title="Confirma Plata">Confirma Plata</a>
															@else
																"Da"
															@endif
														</td>
													</tr>
												@endif
												<tr>
													<td width='30%' class='label-view text-right'>Refuzat</td>
													<td>
														@if($order->refuzat==0)
															<a href="" class="tips btn btn-xs btn-primary {{$order->id}} confpay refusal" title="Confirma Plata">Refuza Plata</a>
														@else
															"Da"
														@endif
													</td>
												</tr>
											@else
												<tr>
													<td width='30%' class='label-view text-right'>Refuzat</td>
													<td>
														{{$pay->message}}
														
													</td>
												</tr>
											@endif	
												<script>
												$(document).ready(function(){
												  $('.confpay').click(function(e){
												    e.preventDefault();
												    $('#payment').children().remove();
												    var data = <?php echo $f; ?>;
												    that = $(this);
												    f = '';
												    if(that.attr('class').split(" ").length == 6){
												      f=data.f1;
												    }else{
												      f=data.f2;
												    }
														$.get('/savepaymenttosession/'+that.attr('class').split(" ")[4]).done(function(){
													    $.each(f[0],function(i,v){
													      $('#payment').append('<input type="hidden" name="'+i+'" value="'+v+'">');
													    });
													    $('#payment').append('<input type="hidden" name="P_SIGN" value="'+f[1]+'">');
													    $('#payment').submit();
														});
												  });
												});
												</script>
												@endif
											@endif

									</tbody>
								</table>
							</div>

						</div>
					</div>

					<?php
						$location = App\Models\Travel\Geography::getCountryForHotel($package->id_hotel,$package->soap_client);
						$transport = App\Models\Travel\PackageInfo::getTransportCode($package->is_tour,$package->is_bus,$package->is_flight);
						$link = "/oferte/Circuite/".$location.'/'.str_replace("/","-",str_replace(" ", "-", str_replace("_","-",$package->name)))."_".$transport."_".$package->id_hotel."_".$package->soap_client."_sid0";
					?>


					<div class="panel panel-info">
						<div class="panel-heading">
							@if($order->hotel == 0)
								Package Details
							@else
								Hotel Details
							@endif
						</div>
						<div class="panel-body">

							<div class="sbox-content" style="background:#fff;">
								<table class="table table-striped table-bordered" >
									<tbody>

											<tr>
												<td width='30%' class='label-view text-right'>Name</td>
												<td><a href="{{$link}}" target="_blank">{{$package->name}}</a></td>
											</tr>

											<tr>
												<td width='30%' class='label-view text-right'>Included Services</td>
												<td>{{$package->included_services}} </td>

											</tr>


											<tr>
												<td width='20%' class='label-view text-right'>Image</td>
												<?php
												$images = App\Models\Travel\Eloquent\FileInfoEloquent::where('id_hotel',$package->id_hotel)->get();

												if($images->isEmpty() == false){
														$mimeArray = explode('/', $images[0]->mime_type);
														$type = $mimeArray[count($mimeArray)-1];
														if($images[0]->soap_client == "HO"){
																if(file_exists(public_path()."/images/offers/{$images[0]->name}")){
																	$imgUrl = "/images/offers/{$images[0]->name}";
																}else{
																	$imgUrl = "/images/offers/{$images[0]->id}.{$type}";
																}
														} else {
																if(file_exists(public_path()."/images/offers/{$images[0]->soap_client}/{$images[0]->name}")){
																	$imgUrl = "/images/offers/{$images[0]->soap_client}/{$images[0]->name}";
																}else{
																	$imgUrl = "/images/offers/{$images[0]->soap_client}/{$images[0]->id}.{$type}";
																}
														}
												} else {
														$imgUrl = "/images/210x140.jpg";
												}
												?>

												<td><a href="{{$link}}" target="_blank"><img src="{{{$imgUrl}}}" width='100%' /></a></td>

											</tr>
											<tr>
												<td></td>
												<td><a href="{{$link}}" target="_blank">Vezi link</a></td>
											</tr>

																				</tbody>
								</table>
							</div>

						</div>
					</div>


					@if(!empty(json_decode($order->xtrc)))
						<div class="panel panel-info">
						<div class="panel-heading">
							Extra Components
						</div>
						<div class="panel-body">

							<div class="sbox-content" style="background:#fff;">
								<table class="table table-striped table-bordered" >
									<tbody>
										@foreach(json_decode($order->xtrc) as $v)
										<tr>
											<td>{{json_decode($v)->Label}}</td>	
											<td>{{json_decode($v)->Description}}</td>	
											<td>{{json_decode($v)->Price}}</td>	
										</tr>	
										@endforeach
									</tbody>
								</table>
							</div>

						</div>
					</div>



												
					@endif



					



					@if($order->company_name)
					<div class="panel panel-warning">
						<div class="panel-heading">
							Order Company Details
						</div>
						<div class="panel-body">
							<div class="sbox-content" style="background:#fff;">

								<table class="table table-striped table-bordered" >
									<tbody>

											<tr>
												<td width='30%' class='label-view text-right'>CompanyName</td>
												<td>{{$order->company_name}}	 </td>

											</tr>

											<tr>
												<td width='30%' class='label-view text-right'>Company Address</td>
												<td>{{$order->company_address}} </td>

											</tr>


											<tr>
												<td width='30%' class='label-view text-right'>Company City</td>
												<td>{{ $order->company_city }} </td>
											</tr>

											<tr>
												<td width='30%' class='label-view text-right'>Company Zone</td>
												<td>{{ $order->company_zone }} </td>
											</tr>

											<tr>
												<td width='30%' class='label-view text-right'>Company Country</td>
												<td>{{ $order->company_country }} </td>
											</tr>

											<tr>
												<td width='30%' class='label-view text-right'>Company NRC</td>
												<td>{{ $order->company_nrc }} </td>
											</tr>

											<tr>
												<td width='30%' class='label-view text-right'>Company CUI</td>
												<td>{{ $order->company_cui }} </td>
											</tr>

											<tr>
												<td width='30%' class='label-view text-right'>Company Bank Account</td>
												<td>{{ $order->company_bank_account }} </td>
											</tr>

											<tr>
												<td width='30%' class='label-view text-right'>Company Bank</td>
												<td>{{ $order->company_bank }} </td>
											</tr>
									</tbody>
								</table>
							</div>

						</div>
					</div>
					@endif




				</div>


				<div class="col-md-6">

				</div>

				@foreach($clients as $k=>$v)
				<div class="col-md-6">
					<div class="panel panel-danger">
						<div class="panel-heading">
							Person {{$k+1}}


						</div>
						<div class="panel-body">



							<div class="sbox-content" style="background:#fff;">

								<table class="table table-striped table-bordered" >
									<tbody>

											<tr>
												<td width='30%' class='label-view text-right'>FirstName</td>
												<td>{{$v->fname}}	 </td>

											</tr>

											<tr>
												<td width='30%' class='label-view text-right'>LastName</td>
												<td>{{$v->lname}} </td>

											</tr>

											<tr>
												<td width='30%' class='label-view text-right'>Gender</td>
												<td>{{ ($v->gender==1?"Male":"Female") }} </td>

											</tr>

											<tr>
												<td width='30%' class='label-view text-right'>BirthDate</td>
												<td>{{ $v->birthdate }} </td>
											</tr>


									</tbody>
								</table>
							</div>

						</div>
					</div>
				</div>
			@endforeach


				<div style="clear:both"></div>





		</div>
	</div>
</div>
<form id="payment" style="display:none;" target="_blank" action="https://www.activare3dsecure.ro/teste3d/cgi-bin/" method="post"></form>

<style type="text/css">
    .bootstrap-switch.bootstrap-switch-large .bootstrap-switch-handle-off, .bootstrap-switch.bootstrap-switch-large .bootstrap-switch-handle-on, .bootstrap-switch.bootstrap-switch-large .bootstrap-switch-label {
        padding: 2px 0px !important;
        font-size: 11px !important;
        width: 30px !important;
    }
</style>

@stop