@extends('layouts.app')

@section('content')
<link href="/public/sximo/css/magicsuggest-min.css" rel="stylesheet">
<script src="/public/sximo/js/magicsuggest-min.js"></script>

  <div class="page-content row">
    <!-- Page header -->
    <div class="page-header">
      <div class="page-title">
        <h3> {{ $pageTitle }} <small>{{ $pageNote }}</small></h3>
      </div>
      <ul class="breadcrumb">
        <li><a href="{{ URL::to('dashboard') }}">{{ Lang::get('core.home') }}</a></li>
		<li><a href="{{ URL::to('adminpackages?return='.$return) }}">{{ $pageTitle }}</a></li>
        <li class="active">{{ Lang::get('core.addedit') }} </li>
      </ul>

    </div>
<div style="clear:both"></div>
<div class="page-content-wrapper m-t">
{!! Form::open(array('url'=>'adminpackages/save?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
<div class="col-md-6">
<div class="sbox">
	<div class="sbox-title">
		<h4>
			<i class="fa fa-cog"></i> {{Lang::get('travel.package_details')}}
		</h4>
	</div>
	<div class="sbox-content">
		<input name="idx" type="hidden" value="{{$row['idx']}}" />
		<div class="form-group  " >
			<label for="hotel_id" class=" control-label col-md-4 text-left">
				{{Lang::get('travel.package_id')}}
			</label>
			<div class="col-md-2">
				{!! Form::text('id', $row['id'],array('class'=>'form-control', 'placeholder'=>'', 'readonly'=>'readonly'   )) !!}
			</div>
			<label for="soap_client" class=" control-label col-md-2 text-left">
				{{Lang::get('travel.package_soap')}}
			</label>
			<div class="col-md-2">
				{!! Form::text('soap_client', $row['soap_client'],array('class'=>'form-control', 'placeholder'=>'', 'readonly'=>'readonly'   )) !!}
			</div>
			<div class="col-md-2">
			</div>
		</div>
		<div class="form-group  " >
			<label for="name" class=" control-label col-md-4 text-left"> {{Lang::get('travel.package_name')}} </label>
			<div class="col-md-6">
				@if($row['isLocal'])
					{!! Form::text('name', $row['name'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
				@else
					{!! Form::text('name', $row['name'],array('class'=>'form-control', 'placeholder'=>'', 'readonly'=>'readonly'  )) !!}
				@endif
			</div>
			<div class="col-md-2">

			</div>
		</div>

		<div class="form-group  " >
			<label for="is_tour" class=" control-label col-md-4 text-left"> {{Lang::get('travel.package_type')}} </label>
			<div class="col-md-6">
				<label class='radio radio-inline ba-radio-no-padding-left'>
					<input type='radio' name='is_tour' value ='0' required @if($row['is_tour'] == 0) checked="checked" @endif @if(!$row['isLocal']) disabled @endif  > Sejur
				</label>
				<label class='radio radio-inline ba-radio-no-padding-left'>
					<input type='radio' name='is_tour' value ='1' required @if($row['is_tour'] == 1) checked="checked" @endif @if(!$row['isLocal']) disabled @endif > Circuit
				</label>
			</div>
			<div class="col-md-2">

			</div>
		</div>

		<div class="form-group  " >
			<label for="duration" class=" control-label col-md-4 text-left"> {{Lang::get('travel.package_duration')}} </label>
			<div class="col-md-2">
				@if($row['isLocal'])
					{!! Form::text('duration', $row['duration'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}

				@else
					{!! Form::text('duration', $row['duration'],array('class'=>'form-control', 'placeholder'=>'', 'readonly' => 'readonly'  )) !!}
				@endif
			</div>
			<div class="col-md-6">
        	@if($row['isLocal'])
            <select class="form-control" name="day_night">
              @if($row['day_night'] == 1)
                <option value="1">Zile</option>
                <option value="0">Nopti</option>
              @else
                <option value="0">Nopti</option>
                <option value="1">Zile</option>
              @endif
            </select>
          @endif
			</div>
		</div>
				<div class="form-group  " >
			<label for="id_hotel" class=" control-label col-md-4 text-left">
				{{Lang::get('travel.package_hotel')}}
			</label>
			<div class="col-md-6">
				@if($row['isLocal'])
				<select name='id_hotel' rows='5' id='id_hotel' code='{$id_hotel}' class='select2 '  required></select>
				@else
					<input name="id_hotel" type="hidden" value="{{$row['id_hotel']}}" />
					{!! Form::text('hotel_name', $row['hotel_name'],array('class'=>'form-control', 'placeholder'=>'', 'readonly' => 'readonly' )) !!}
				@endif


			</div>
			<div class="col-md-2"></div>
		</div>

		<div class="form-group  " >
			<label for="fare_types" class=" control-label col-md-4 text-left">
				{{Lang::get('travel.package_fare_types')}}
			</label>
			<div class="col-md-6">
				<select name='fare_types[]' rows='5' id='fare_types' code='0'
				class='select2 ' multiple="multiple" ></select>
			</div>
			<div class="col-md-2"></div>
		</div>

		<div class="form-group  " >
			<label for="categories" class=" control-label col-md-4 text-left">
				{{Lang::get('travel.package_categories')}}
			</label>
			<div class="col-md-6">
				<select name='categories[]' rows='5' id='categories' code='0'
				class='select2 ' multiple="multiple" ></select>
			</div>
			<div class="col-md-2"></div>
		</div>
	</div>
</div>
</div>

<?php

	if($row['idx'] != ''&&$row['id'] != ''){
		$location = App\Models\Travel\Geography::getCountryForHotel($row->id_hotel,$row->soap_client);
		$transport = App\Models\Travel\PackageInfo::getTransportCode($row->is_tour,$row->is_bus,$row->is_flight);
		if($row['is_tour'] == 0){
			$link = "/oferte/Sejururi/".$location.'/'.str_replace("/","-",str_replace(" ", "-", str_replace("_","-",$row->name)))."_".$transport."_".$row->id_hotel."_".$row->soap_client."_sid0";
		}else{
			$link = "/oferte/Circuite/".$location.'/'.str_replace("/","-",str_replace(" ", "-", str_replace("_","-",$row->name)))."_".$transport."_".$row->id_hotel."_".$row->soap_client."_sid0";
		}
	}
?>
<div class="col-md-6">
<div class="sbox">
	<div class="sbox-title">
		<h4>
			<i class="fa fa-suitcase"></i> {{Lang::get('travel.package_transport_details')}}
		</h4>
	</div>

	<div class="sbox-content">
		<div class="form-group  " >
			<label for="transport_type" class=" control-label col-md-4 text-left"> {{Lang::get('travel.package_transport_type')}} </label>
			<div class="col-md-6">
				<label class='radio radio-inline ba-radio-no-padding-left'>
				@if($row['idx'] != ''&&$row['id'] != '')
					<input type='radio' name='transport_type' value ='0' required @if($row['is_bus'] == 0 && $row['is_flight'] == 0) checked="checked" @endif  @if(!$row['isLocal']) disabled @endif > Individual
				@else
					<input type='radio' name='transport_type' value ='0' required  > Individual
				@endif
				</label>
				<label class='radio radio-inline ba-radio-no-padding-left'>
				@if($row['idx'] != ''&&$row['id'] != '')
					<input type='radio' name='transport_type' value ='1' required @if($row['is_bus'] == 1) checked="checked" @endif @if(!$row['isLocal']) disabled @endif > Autocar
				@else
					<input type='radio' name='transport_type' value ='1' required > Autocar
				@endif
				</label>
				<label class='radio radio-inline ba-radio-no-padding-left'>
				@if($row['idx'] != ''&&$row['id'] != '')
					<input type='radio' name='transport_type' value ='2' required @if($row['is_flight'] == 1) checked="checked" @endif @if(!$row['isLocal']) disabled @endif > Avion
				@else
					<input type='radio' name='transport_type' value ='2' required > Avion	
				@endif	
				</label>
			</div>
			<div class="col-md-2">

			</div>
			
		</div>
			@if($row['idx'] != ''&&$row['id'] != '')
		<div class="form-group">
			<label ><a href="{{$link}}" target="_blank">Vezi Oferta</a></label>
				<div class="col-md-2"></div>
			</div>
			@endif
		<div class="form-group  " >
			
			<label for="outbound_transport_duration" class=" control-label col-md-4 text-left">
				{{Lang::get('travel.package_outbound_duration')}}
			</label>
			<div class="col-md-2">
				@if($row['isLocal'] && $row['idx'] != ''&&$row['id'] != '')
			  		{!! Form::text('outbound_transport_duration', $row['outbound_transport_duration'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
				@else
					{!! Form::text('outbound_transport_duration', $row['outbound_transport_duration'],array('class'=>'form-control', 'placeholder'=>'', 'readonly' => 'readonly' )) !!}
				@endif
			</div>
			
			<div class="col-md-6"></div>
		</div>
		@if(!$row['isLocal'] && $row['idx'] != ''&&$row['id'] != '')
		<div class="form-group  " >
			<label for="city_id" class=" control-label col-md-4 text-left">
				{{Lang::get('travel.package_destination')}}
			</label>
			<div class="col-md-6">
				<input name="city_id" type="hidden" value="{{$row['destination']}}" />
				{!! Form::text('outbound_transport_duration', $row['destination_name'],array('class'=>'form-control', 'placeholder'=>'', 'readonly' => 'readonly' )) !!}
			</div>
			<div class="col-md-2"></div>
		</div>
		@endif
		
	</div>
</div>
</div>
<div class="col-md-12">
<div class="sbox">
	<div class="sbox-title">
		<h4>
			<i class="fa fa-info-circle"></i> {{Lang::get('travel.package_description')}}&nbsp;&nbsp;
			<a href="javascript:;" onclick="addDetail();" class="tips label label-primary" title="{{ Lang::get('core.btn_add') }}"><i class="fa fa-plus-square"></i></a>
		</h4>
	</div>
	<div class="sbox-content">
		<input name="detail" id="detail" type="hidden" value="0" />
		<div class="form-group  " >
			<label for="description" class=" control-label col-md-2 text-left">{{Lang::get('travel.package_description')}}</label>
			<div class="col-md-9">
			  <textarea name='description' rows='3' id='description' class='editor form-control ' >{{$row['description']}}</textarea>
			</div>
			<div class="col-md-1">
			</div>
		  </div>
		  <div class="form-group  ">
		  	<label for="included_services" class=" control-label col-md-2 text-left">
				{{Lang::get('travel.package_included_services')}}
			</label>
		    <div class="col-md-9">
				<textarea name='included_services' rows='3' id='included_services' class='editor form-control ' >{{$row['included_services']}}</textarea>
			</div>
			<div class="col-md-1">
			</div>
		  </div>
		  <div class="form-group  ">
		  	<label for="not_included_services" class=" control-label col-md-2 text-left">
				{{Lang::get('travel.package_not_included_services')}}
			</label>
		    <div class="col-md-9">
				<textarea name='not_included_services' rows='3' id='not_included_services' class='editor form-control ' >{{$row['not_included_services']}}</textarea>
			</div>
			<div class="col-md-1">
			</div>
		 </div>
		<input type="hidden" name="last_detail_i" value="{{count($row['detailed_descriptions'])}}" />
		@for ($i=0; $i < count($row['detailed_descriptions']); $i++)
		<div class="form-group" id="detailed_description_{{$i}}">
			<label for="label" class=" control-label col-md-2 text-left">
				Detail
			</label>
			<div class="col-md-9">
				<input class="form-control" placeholder="" name="detail[{{$i}}][label]" type="text" value="{{$row['detailed_descriptions'][$i]['label']}}">
				<textarea name='detail[{{$i}}][text]' rows='3' class='editor form-control ' >{{$row['detailed_descriptions'][$i]['text']}}</textarea>
				<input id="detailed_description_{{$id}}_delete" name="detail[{{$i}}][delete]" type="hidden" value="0" />
				<input name="detail[{{$i}}][id]" type="hidden" value="{{$row['detailed_descriptions'][$i]['id']}}" />
			</div>
			<div class="col-md-1">
				<a href="javascript:;" onclick="deleteDetail({{$i}},{{$id}});" class="tips jdelete btn btn-xs btn-white" title="{{ Lang::get('core.btn_remove') }}"><i class="fa fa-trash-o"></i></a>
			</div>
		</div>
    <?php //dd($row['detailed_descriptions'][$i]['id'],$id) ?>
		@endfor
		<div id="content_details"></div>
	</div>
</div>
</div>

<div class="col-md-12">
<div class="sbox">
	<div class="sbox-title">
		<h4>
			<i class="fa fa-info-circle"></i> {{Lang::get('travel.package_prices')}}
		</h4>
	</div>
	<div class="sbox-content">
		<div class="table-responsive">
			<input type="hidden" value="{{count($row['prices'])}}" id="noPrices" name="noPrices" />
	    	<table class="table table-striped ">
	        	<thead>
					<tr>
						<th>{{Lang::get('travel.package_price_room_category')}}</th>
						<th>{{Lang::get('travel.package_price_price_set')}}</th>
						<th>{{Lang::get('travel.package_price_meal_plan')}}</th>
						<th>{{Lang::get('travel.package_price_departure_date')}}</th>
						<th>{{Lang::get('travel.package_price_gross')}}</th>
						<th>{{Lang::get('travel.package_price_tax')}}</th>
            <th>Currency</th>
						@if($row['isLocal'])
						<th>{{Lang::get('travel.package_price_actions')}}</th>
						@endif
					</tr>
	        	</thead>

		        <tbody id="pricesOutput">
		        		@if($row['prices'] == null)
			        		<tr>
			        			<td colspan="7"><center>Nu exista preturi pentru acest pachet</center></td>
			        		</tr>
		        		@else

			        		@for($i = 0; $i< count($row['prices']); $i++)
			        		<tr id="priceEntry-{{$i}}">
				           		<td class="price_entry_room_category">{{$row['prices'][$i]->room_category_name}}</td>
				           		<td class="price_entry_price_set">{{$row['prices'][$i]->price_set_label}}</td>
				           		<td class="price_entry_meal_plan">{{$row['prices'][$i]->meal_plan_name}}</td>
				           		<td class="price_entry_departure_date">{{$row['prices'][$i]->departure_date}}</td>
				           		<td class="price_entry_gross">{{$row['prices'][$i]->gross}}</td>
				           		<td class="price_entry_tax">{{$row['prices'][$i]->tax}}</td>
                      <td class="price_entry_currency">
                        @if($row['prices'][$i]->currency == 1)
                          RON
                        @else
                          EUR
                        @endif
                      </td>

				           		@if($row['isLocal'])
				           		<td class="price_entry_actions">
				           		@if(Auth::user()->group_id == 1)
				           			<a href="javascript:;" onclick="editPrice({{$i}});" class="tips jedit btn btn-xs btn-white" title="{{ Lang::get('core.btn_edit') }}"><i class="fa fa-pencil-square-o"></i> Edit</a>
				           		@endif
				           			<a href="javascript:;" onclick="delPrice({{$i}})" class="tips jdelete btn btn-xs btn-white" title="{{ Lang::get('core.btn_remove') }}"><i class="fa fa-trash-o"></i></a>
				           		</td>
				           		@endif
				           		<input type="hidden" name='price_entry[{{$i}}][room_category]' value='{{$row["prices"][$i]->id_room_category}}' />
				           		<input type="hidden" name='price_entry[{{$i}}][price_set]' value='{{$row["prices"][$i]->id_price_set}}' />
				           		<input type="hidden" name='price_entry[{{$i}}][meal_plan]' value='{{$row["prices"][$i]->id_meal_plan}}' />
				           		<input type="hidden" name='price_entry[{{$i}}][departure_date]' value='{{$row["prices"][$i]->departure_date}}' />
				           		<input type="hidden" name='price_entry[{{$i}}][gross]' value='{{$row["prices"][$i]->gross}}' />
				           		<input type="hidden" name='price_entry[{{$i}}][tax]' value='{{$row["prices"][$i]->tax}}' />
                      <input type="hidden" name='price_entry[{{$i}}][currency]' value='{{$row["prices"][$i]->currency}}' />
			           		</tr>
			           		@endfor
		           		@endif
		        </tbody>
		        @if($row['isLocal'])
		        <tfoot>
		        	<tr>
		           		<td class="price_entry_room_category">
		           			<select name='add_price_room_category' rows='5' id='add_price_room_category' code='0' class='select2'></select>
		           		</td>
		           		<td class="price_entry_price_set">
		           			<select name='add_price_price_set' rows='5' id='add_price_price_set' code='0' class='select2'></select>
		           		</td>
		           		<td class="price_entry_meal_plan">
		           			<select name='add_price_meal_plan' rows='5' id='add_price_meal_plan' code='0' class='select2'></select>
		           		</td>
		           		<td class="price_entry_departure_date">
		           			<input class="form-control date" placeholder="" name="add_price_departure_date" id="add_price_departure_date" type="text" value="">
		           		</td>
		           		<td class="price_entry_gross">
		           			<input class="form-control" placeholder="" name="add_price_gross" id="add_price_gross" type="text" value="">
		           		</td>
		           		<td class="price_entry_tax">
		           			<input class="form-control" placeholder="" name="add_price_tax" id="add_price_tax" type="text" value="">
		           		</td>
                  <td class="price_entry_room_category">
		           			<select name='add_currency' rows='5' id='add_currency' code='0' class='select2'>
		           			  <option value="0">EUR</option>
                      <option value="1">RON</option>
		           			</select>
		           		</td>
		           		<td class="price_entry_actions">
		           			<a href="javascript:;" style="margin-top: 4px;" onclick="addPrice();" class="tips jedit btn btn-xs btn-white" title="{{ Lang::get('core.btn_add') }}"><i class="fa fa-plus"></i> Add</a>
		           		</td>
	           		</tr>
		        </tfoot>
		        @endif
	    	</table>
		</div>
	</div>
</div>
</div>

<div class="col-md-12">
<div class="sbox">
	<div class="sbox-content">
	  <div class="form-group">
	  	<center>
		<button type="submit" name="apply" class="btn btn-info btn-sm" ><i class="fa  fa-check-circle"></i> {{ Lang::get('core.sb_apply') }}</button>
		<button type="submit" name="submit" class="btn btn-primary btn-sm" ><i class="fa  fa-save "></i> {{ Lang::get('core.sb_save') }}</button>
		<button type="button" onclick="location.href='{{ URL::to('adminpackages?return='.$return) }}' " class="btn btn-success btn-sm "><i class="fa  fa-arrow-circle-left "></i>  {{ Lang::get('core.sb_cancel') }} </button>
		</center>

	  </div>
	</div>
</div>
</div>
{!! Form::close() !!}
</div>
</div>
   <script type="text/javascript">
   	var prices = <?php echo $row['pricesJSON'];?>;
	$(document).ready(function() {
		var hotelChanged = 0;
		$("#id_hotel").jCombo("{{ URL::to('adminpackages/comboselect?filter=hotels:id:name#limit=WHERE:is_local:=:1') }}", {
			selected_value : '{{ $row["id_hotel"] }}'
		});

		$('#id_hotel').change(function(){
			if(hotelChanged == 1){
				$("#pricesOutput").html('<tr><td colspan="7"><center>Nu exista preturi pentru acest pachet</center></td></tr>');
			}
			hotelChanged = 1;
		});

		$("#add_price_room_category").jCombo("{{ URL::to('adminpackages/comboselect?filter=room_categories:id:name#limit=WHERE:id_hotel:=:') }}", {
			selected_value : '0',
			parent : "#id_hotel",
      parent_value : $("#id_hotel").val()
		});

		$("#add_price_price_set").jCombo("{{ URL::to('adminpackages/comboselect?filter=price_sets:id:label#limit=WHERE:is_local:=:1') }}", {
			selected_value : '0'
		});

		$("#add_price_meal_plan").jCombo("{{ URL::to('adminpackages/comboselect?filter=meal_plans:id:name') }}", {
			selected_value : '0'
		});

		$("#fare_types").jCombo("{{ URL::to('adminpackages/comboselect?filter=fare_types:id:name') }}", {
			selected_value : '{{$row["selectedFareTypes"]}}'
		});

		$("#categories").jCombo("{{ URL::to('adminpackages/comboselect?filter=categories:id:name') }}", {
			selected_value : '{{$row["selectedCategories"]}}'
		});


		$('.removeCurrentFiles').on('click',function(){
			var removeUrl = $(this).attr('href');
			$.get(removeUrl,function(response){});
			$(this).parent('div').empty();
			return false;
		});


	});

	function deleteDetail(i,id) {
	    if (confirm("Are you sure?")) {
	    	$('#detailed_description_'+id+'_delete').val(1);
	    	$('#detailed_description_'+i).hide();
	    }
	    return false;
	}


	function addDetail() {
		var lastIDInput = $('input[name="last_detail_i"]');
		var newID = lastIDInput.val();
		lastIDInput.val(parseInt(newID) + 1);

		var html = '<div class="form-group" id="detailed_description_'+newID+'"><label for="label" class=" control-label col-md-2 text-left">Detail</label>';
		html += '<div class="col-md-9">';
		html += '<input class="form-control" placeholder="" name="detail['+newID+'][label]" type="text" value="">';
		html += '<textarea id="detailed_description_text_'+newID+'" name="detail['+newID+'][text]" rows="3" class="editor form-control"" ></textarea>';
		html += '<input id="detailed_description_'+newID+'_delete" name="detail['+newID+'][delete]" type="hidden" value="0" />';
		html += '</div>';
		html += '<div class="col-md-1"><a href="javascript:;" onclick="deleteDetail('+newID+');" class="tips jdelete btn btn-xs btn-white" title="{{ Lang::get('core.btn_remove') }}"><i class="fa fa-trash-o"></i></a></div></div>';

		$('#content_details').append(html);
		$('#detailed_description_text_'+newID).summernote();

	}

	//======================================
	var editPrice = function(id){
		var roomCategoryTD = $("#priceEntry-"+id+" .price_entry_room_category");
		var priceSetTD = $("#priceEntry-"+id+" .price_entry_price_set");
		var mealPlanTD = $("#priceEntry-"+id+" .price_entry_meal_plan");
		var departureDateTD = $("#priceEntry-"+id+" .price_entry_departure_date");
		var grossTD = $("#priceEntry-"+id+" .price_entry_gross");
		var taxTD = $("#priceEntry-"+id+" .price_entry_tax");
		var actionsTD = $("#priceEntry-"+id+" .price_entry_actions");
    var currencyTD = $("#priceEntry-"+id+" .price_entry_currency");
		var roomCategoryHInput = $("#priceEntry-"+id+" input[name='price_entry["+id+"][room_category]']");
		var priceSetHInput = $("#priceEntry-"+id+" input[name='price_entry["+id+"][price_set]']");
		var mealPlanHInput = $("#priceEntry-"+id+" input[name='price_entry["+id+"][meal_plan]']");
		var departureDateHInput = $("#priceEntry-"+id+" input[name='price_entry["+id+"][departure_date]']");
		var grossHInput = $("#priceEntry-"+id+" input[name='price_entry["+id+"][gross]']");
		var taxHInput = $("#priceEntry-"+id+" input[name='price_entry["+id+"][tax]']");
    var currency = $("#priceEntry-"+id+" input[name='price_entry["+id+"][currency]']");
		var inputRoomCategory = "<select data-old='"+roomCategoryTD.html()+"' name='input_room_category-"+id+"' rows='5' id='input_room_category-"+id+"' code='"+roomCategoryHInput.val()+"' class='select2'></select>";
		roomCategoryTD.html(inputRoomCategory);
		$("#input_room_category-"+id).jCombo("{{ URL::to('adminpackages/comboselect?filter=room_categories:id:name#limit=WHERE:id_hotel:=:') }}", {
			selected_value : roomCategoryHInput.val(),
			parent : "#id_hotel",
			parent_value : $("#id_hotel").val()
		});
		var inputPriceSet = "<select data-old='"+priceSetTD.html()+"' name='input_price_set-"+id+"' rows='5' id='input_price_set-"+id+"' code='"+priceSetHInput.val()+"' class='select2'></select>";
		priceSetTD.html(inputPriceSet);
		$("#input_price_set-"+id).jCombo("{{ URL::to('adminpackages/comboselect?filter=price_sets:id:label#limit=WHERE:is_local:=:1') }}", {
			selected_value : priceSetHInput.val()
		});
		var inputMealPlan = "<select data-old='"+mealPlanTD.html()+"' name='input_meal_plan-"+id+"' rows='5' id='input_meal_plan-"+id+"' code='"+mealPlanHInput.val()+"' class='select2'></select>";
		mealPlanTD.html(inputMealPlan);
		$("#input_meal_plan-"+id).jCombo("{{ URL::to('adminpackages/comboselect?filter=meal_plans:id:name') }}", {
			selected_value : mealPlanHInput.val()
		});
		var inputDepartureDate = "<input data-old='"+departureDateTD.html()+"' class='form-control date' placeholder='' name='input_departure_date-"+id+"' id='input_departure_date-"+id+"' type='text' value='"+departureDateHInput.val()+"'>";
		departureDateTD.html(inputDepartureDate);
		var inputGross = "<input data-old='"+grossTD.html()+"' class='form-control' placeholder='' name='input_gross-"+id+"' id='input_gross-"+id+"' type='text' value='"+grossHInput.val()+"'>";
		grossTD.html(inputGross);
		var inputTax = "<input data-old='"+taxTD.html()+"' class='form-control' placeholder='' name='input_tax-"+id+"' id='input_tax-"+id+"' type='text' value='"+taxHInput.val()+"'>";
		taxTD.html(inputTax);
    var currency = "<input data-old='"+currency.html()+"' class='form-control' placeholder='' name='input_currency-"+id+"' id='input_currency-"+id+"' type='text' value='"+currency.val()+"'>";
    currencyTD.html(currency);
    actionsTD.html("<a href='javascript:;' onclick='savePrice("+id+");' class='tips jedit btn btn-xs btn-white' title='Save'><i class='fa fa-floppy-o'></i> Save</a>"
				      +"<a href='javascript:;' onclick='cancelPrice("+id+")' class='tips jdelete btn btn-xs btn-white' title='Cancel'><i class='fa fa-ban'></i></a>");
		$('.date').datepicker({format:'yyyy-mm-dd',autoClose:true});

		$("select").select2("destroy").select2();
	}

	//======================================

	var delPrice = function(id){
		if(confirm("Are you sure?")){
			$("#priceEntry-"+id).remove();
			prices[id] = null;
			$('#noPrices').val(parseInt($('#noPrices').val()) - 1);
			if(parseInt($('#noPrices').val()) == 0){
				$("#pricesOutput").html('<tr><td colspan="7"><center>Nu exista preturi pentru acest pachet</center></td></tr>');
			}
		}
		return false;
	}

	var cancelPrice = function(id){
		var roomCategoryTD = $("#priceEntry-"+id+" .price_entry_room_category");
		var priceSetTD = $("#priceEntry-"+id+" .price_entry_price_set");
		var mealPlanTD = $("#priceEntry-"+id+" .price_entry_meal_plan");
		var departureDateTD = $("#priceEntry-"+id+" .price_entry_departure_date");
		var grossTD = $("#priceEntry-"+id+" .price_entry_gross");
		var taxTD = $("#priceEntry-"+id+" .price_entry_tax");
		var actionsTD = $("#priceEntry-"+id+" .price_entry_actions");
		var roomCategoryOld = $("#input_room_category-"+id).data("old");
		var priceSetOld = $("#input_price_set-"+id).data("old");
		var mealPlanOld = $("#input_meal_plan-"+id).data("old");
		var departureDateOld = $("#input_departure_date-"+id).data("old");
		var grossOld = $("#input_gross-"+id).data("old");
		var taxOld = $("#input_tax-"+id).data("old");
		roomCategoryTD.html(roomCategoryOld);
		priceSetTD.html(priceSetOld);
		mealPlanTD.html(mealPlanOld);
		departureDateTD.html(departureDateOld);
		grossTD.html(grossOld);
		taxTD.html(taxOld);
		actionsTD.html("<a href='javascript:;' onclick='editPrice("+id+");' class='tips jedit btn btn-xs btn-white' title='core.btn_edit'><i class='fa fa-pencil-square-o'></i> Edit</a>"
				      +"<a href='javascript:;' onclick='delPrice("+id+");' class='tips jdelete btn btn-xs btn-white' title='core.btn_remove'><i class='fa fa-trash-o'></i></a>");
	}

	var savePrice = function(id){
		var roomCategoryTD = $("#priceEntry-"+id+" .price_entry_room_category");
		var priceSetTD = $("#priceEntry-"+id+" .price_entry_price_set");
		var mealPlanTD = $("#priceEntry-"+id+" .price_entry_meal_plan");
		var departureDateTD = $("#priceEntry-"+id+" .price_entry_departure_date");
		var grossTD = $("#priceEntry-"+id+" .price_entry_gross");
		var taxTD = $("#priceEntry-"+id+" .price_entry_tax");
		var actionsTD = $("#priceEntry-"+id+" .price_entry_actions");
		var roomCategoryInput = $("#input_room_category-"+id);
		var priceSetInput = $("#input_price_set-"+id);
		var mealPlanInput = $("#input_meal_plan-"+id);
		var departureDateInput = $("#input_departure_date-"+id);
		var grossInput = $("#input_gross-"+id);
		var taxInput = $("#input_tax-"+id);
		var roomCategoryHInput = $("#priceEntry-"+id+" input[name='price_entry["+id+"][room_category]']");
		var priceSetHInput = $("#priceEntry-"+id+" input[name='price_entry["+id+"][price_set]']");
		var mealPlanHInput = $("#priceEntry-"+id+" input[name='price_entry["+id+"][meal_plan]']");
		var departureDateHInput = $("#priceEntry-"+id+" input[name='price_entry["+id+"][departure_date]']");
		var grossHInput = $("#priceEntry-"+id+" input[name='price_entry["+id+"][gross]']");
		var taxHInput = $("#priceEntry-"+id+" input[name='price_entry["+id+"][tax]']");
		roomCategoryHInput.val(roomCategoryInput.val());
		priceSetHInput.val(priceSetInput.val());
		mealPlanHInput.val(mealPlanInput.val());
		departureDateHInput.val(departureDateInput.val());
		grossHInput.val(grossInput.val());
		taxHInput.val(taxInput.val());
		roomCategoryTD.html($("#input_room_category-"+id+" option[value='"+$("#input_room_category-"+id).val()+"']").text());
		priceSetTD.html($("#input_price_set-"+id+" option[value='"+$("#input_price_set-"+id).val()+"']").text());
		mealPlanTD.html($("#input_meal_plan-"+id+" option[value='"+$("#input_meal_plan-"+id).val()+"']").text());
		departureDateTD.html(departureDateInput.val());
		grossTD.html(grossInput.val());
		taxTD.html(taxInput.val());
		actionsTD.html("<a href='javascript:;' onclick='editPrice("+id+");' class='tips jedit btn btn-xs btn-white' title='core.btn_edit'><i class='fa fa-pencil-square-o'></i> Edit</a>"
				      +"<a href='javascript:;' onclick='delPrice("+id+");' class='tips jdelete btn btn-xs btn-white' title='core.btn_remove'><i class='fa fa-trash-o'></i></a>");
	}

	var addPrice = function($id){
		var roomCategory = new Object();
		var priceSet = new Object();
		var mealPlan = new Object();
		var departureDate = "";
		var gross = "";
		var tax = "";
    var currencyVal = '';
		roomCategory.value = $("#add_price_room_category").val();
		roomCategory.text = $("#add_price_room_category option[value='"+roomCategory.value+"']").text();
		priceSet.value = $("#add_price_price_set").val();
		priceSet.text = $("#add_price_price_set option[value='"+priceSet.value+"']").text();
		mealPlan.value = $("#add_price_meal_plan").val();
		mealPlan.text = $("#add_price_meal_plan option[value='"+mealPlan.value+"']").text();
		departureDate = $("#add_price_departure_date").val();
		gross = $("#add_price_gross").val();
		tax = $("#add_price_tax").val();
    if($("#add_currency").val() == 0){
      currency = "EUR";
      currencyVal = 0;
    }else{
      currency = "RON";
      currencyVal = 1;
    }
		//Validate fields - to be developed
		//if(validateAddPrice(roomCategory,priceSet,mealPlan,departureDate,gross,tax))
		var newId = prices.length;
		if(parseInt($('#noPrices').val()) != 0){
     
	   	$("#pricesOutput").append("<tr id='priceEntry-"+newId+"'>"
	   							 +	"<td class='price_entry_room_category'>"+roomCategory.text+"</td>"
	   							 +	"<td class='price_entry_price_set'>"+priceSet.text+"</td>"
	   							 +	"<td class='price_entry_meal_plan'>"+mealPlan.text+"</td>"
	   							 +	"<td class='price_entry_departure_date'>"+departureDate+"</td>"
	   							 +	"<td class='price_entry_gross'>"+gross+"</td>"
	   							 +	"<td class='price_entry_tax'>"+tax+"</td>"
                   +	"<td class='price_entry_room_category'>"+currency+"</td>"
	   							 +  "<td class='price_entry_actions'>"
				           		 +    "<a href='javascript:;' onclick='editPrice("+newId+");' class='tips jedit btn btn-xs btn-white' title='core.btn_edit'><i class='fa fa-pencil-square-o'></i> Edit</a>"
				           	     +    "<a href='javascript:;' onclick='delPrice("+newId+")' class='tips jdelete btn btn-xs btn-white' title='core.btn_remove'><i class='fa fa-trash-o'></i></a>"
				           		 +  "</td>"
	   							 +  "<input type='hidden' name='price_entry["+newId+"][room_category]' value='"+roomCategory.value+"' />"
	   							 +  "<input type='hidden' name='price_entry["+newId+"][price_set]' value='"+priceSet.value+"' />"
	   							 +  "<input type='hidden' name='price_entry["+newId+"][meal_plan]' value='"+mealPlan.value+"' />"
	   							 +  "<input type='hidden' name='price_entry["+newId+"][departure_date]' value='"+departureDate+"' />"
	   							 +  "<input type='hidden' name='price_entry["+newId+"][gross]' value='"+gross+"' />"
	   							 +  "<input type='hidden' name='price_entry["+newId+"][tax]' value='"+tax+"' />"
                   +  "<input type='hidden' name='price_entry["+newId+"][currency]' value='"+currencyVal+"' />"
	   							 +"</tr>");
	   } else {
	   	$("#pricesOutput").html("<tr id='priceEntry-"+newId+"'>"
	   							 +	"<td class='price_entry_room_category'>"+roomCategory.text+"</td>"
	   							 +	"<td class='price_entry_price_set'>"+priceSet.text+"</td>"
	   							 +	"<td class='price_entry_meal_plan'>"+mealPlan.text+"</td>"
	   							 +	"<td class='price_entry_departure_date'>"+departureDate+"</td>"
	   							 +	"<td class='price_entry_gross'>"+gross+"</td>"
	   							 +	"<td class='price_entry_tax'>"+tax+"</td>"
                   +	"<td class='price_entry_room_category'>"+currency+"</td>"
	   							 +  "<td class='price_entry_actions'>"
				           		 +    "<a href='javascript:;' onclick='editPrice("+newId+");' class='tips jedit btn btn-xs btn-white' title='core.btn_edit'><i class='fa fa-pencil-square-o'></i> Edit</a>"
				           	     +    "<a href='javascript:;' onclick='delPrice("+newId+")' class='tips jdelete btn btn-xs btn-white' title='core.btn_remove'><i class='fa fa-trash-o'></i></a>"
				           		 +  "</td>"
	   							 +  "<input type='hidden' name='price_entry["+newId+"][room_category]' value='"+roomCategory.value+"' />"
	   							 +  "<input type='hidden' name='price_entry["+newId+"][price_set]' value='"+priceSet.value+"' />"
	   							 +  "<input type='hidden' name='price_entry["+newId+"][meal_plan]' value='"+mealPlan.value+"' />"
	   							 +  "<input type='hidden' name='price_entry["+newId+"][departure_date]' value='"+departureDate+"' />"
	   							 +  "<input type='hidden' name='price_entry["+newId+"][gross]' value='"+gross+"' />"
	   							 +  "<input type='hidden' name='price_entry["+newId+"][tax]' value='"+tax+"' />"
                   +  "<input type='hidden' name='price_entry["+newId+"][currency]' value='"+currencyVal+"' />"
	   							 +"</tr>");
	   }
	   	$("#add_price_room_category").val("");
	   	$("#add_price_room_category").change();
	   	$("#add_price_price_set").val("");
	   	$("#add_price_price_set").change();
	   	$("#add_price_meal_plan").val("");
	   	$("#add_price_meal_plan").change();
	   	$("#add_price_departure_date").val("");
	   	$("#add_price_gross").val("");
	   	$("#add_price_tax").val("");

	    var entry = new Object();
	    entry.id_room_category = roomCategory.value;
	    entry.id_price_set = priceSet.value;
	    entry.id_meal_plan = mealPlan.value;
	    entry.departure_date = departureDate;
	    entry.gross = gross;
	    entry.tax = tax;
      entry.currency = currencyVal;
	    prices[newId] = entry;
		$('#noPrices').val(parseInt($('#noPrices').val()) + 1);
	}


	</script>
@stop
