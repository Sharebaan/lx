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
				<ul class="parsley-error-list">
					@foreach($errors->all() as $error)
					<li>
						{{ $error }}
					</li>
					@endforeach
				</ul>

				{!! Form::open(array('url'=>'adminhotels/save?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
					@if(isset($create))
						<input name="create" type="hidden" value="1" />
					@endif
			    <input name="idx" type="hidden" value="{{$row['idx']}}" />
			    <input name="update" type="hidden" value="{{$row['update']}}" />

				<div class="col-md-6">
					<div class="panel panel-info">
						<div class="panel-heading">
							{{Lang::get('travel.hotel_details')}}
						</div>
						<div class="panel-body">

							<div class="form-group  " >
								<label for="hotel_id" class=" control-label col-md-4 text-left">
									{{Lang::get('travel.hotel_id')}}
								</label>
								<div class="col-md-2">
									{!! Form::text('id', $row['id'],array('class'=>'form-control', 'placeholder'=>'', 'readonly'=>'readonly'   )) !!}
								</div>
								<label for="soap_client" class=" control-label col-md-2 text-left">
									{{Lang::get('travel.hotel_soap')}}
								</label>
								<div class="col-md-2">
									{!! Form::text('soap_client', $row['soap_client'],array('class'=>'form-control', 'placeholder'=>'', 'readonly'=>'readonly'   )) !!}
								</div>
								<div class="col-md-2">
								</div>
							</div>

							<div class="form-group  " >
								<label for="Code" class=" control-label col-md-4 text-left"> {{Lang::get('travel.hotel_code')}} </label>
								<div class="col-md-6">
									<?php if( !empty($row['soap_client']) && $row['soap_client'] != 'LOCAL' && $row['soap_client'] != 'IMPORT' ){ ?>
									{!! Form::text('code', $row['code'],array('class'=>'form-control', 'placeholder'=>'', 'readonly'=>'readonly')) !!}
									<?php }else{?>
									{!! Form::text('code', $row['code'],array('class'=>'form-control', 'placeholder'=>'')) !!}
									<?php }?>
								</div>
								<div class="col-md-2">

								</div>
							</div>


							<div class="form-group  " >
								<label for="Name" class=" control-label col-md-4 text-left"> {{Lang::get('travel.hotel_name')}} </label>
								<div class="col-md-6">
									{!! Form::text('name', $row['name'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
								</div>
								<div class="col-md-2">

								</div>
							</div>

							<div class="form-group  " >
								<label for="Available" class=" control-label col-md-4 text-left"> Available</label>
								<div class="col-md-6">
									<label style="padding-left: 0px !important;" class='radio radio-inline ba-radio-no-padding-left'>
										<input type='checkbox' value="1"  name='available' @if($row['available'] == "1") checked="checked" @endif >
									</label>
								</div>
								<div class="col-md-2">

								</div>
							</div>
							<div class="form-group  " >
								<label for="Description" class=" control-label col-md-4 text-left">
									{{Lang::get('travel.hotel_description')}}
								</label>
								<div class="col-md-6">
									{!! Form::textarea('description', $row['description'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
								</div>
								<div class="col-md-2">

								</div>
							</div>
							<div class="form-group  " >
								<label for="Address" class=" control-label col-md-4 text-left">
									{{Lang::get('travel.hotel_address')}}
								</label>
								<div class="col-md-6">
									{!! Form::text('address', $row['address'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
								</div>
								<div class="col-md-2">

								</div>
							</div>
							<div class="form-group  " >
								<label for="Zip" class=" control-label col-md-4 text-left">
									{{Lang::get('travel.hotel_zip')}}
								</label>
								<div class="col-md-6">
									{!! Form::text('zip', $row['zip'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
								</div>
								<div class="col-md-2">

								</div>
							</div>
							<div class="form-group  " >
								<label for="Phone" class=" control-label col-md-4 text-left">
									{{Lang::get('travel.hotel_phone')}}
								</label>
								<div class="col-md-6">
									{!! Form::text('phone', $row['phone'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
								</div>
								<div class="col-md-2">

								</div>
							</div>
							<div class="form-group  " >
								<label for="Fax" class=" control-label col-md-4 text-left">
									{{Lang::get('travel.hotel_fax')}}
								</label>
								<div class="col-md-6">
									{!! Form::text('fax', $row['fax'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
								</div>
								<div class="col-md-2">

								</div>
							</div>
							<div class="form-group  " >
								<label for="Url" class=" control-label col-md-4 text-left">
									{{Lang::get('travel.hotel_url')}}
								</label>
								<div class="col-md-6">
									{!! Form::text('url', $row['url'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
								</div>
								<div class="col-md-2">

								</div>
							</div>
							<div class="form-group  " >
								<label for="Latitude" class=" control-label col-md-4 text-left">
									{{Lang::get('travel.hotel_latitude')}}
								</label>
								<div class="col-md-6">
									{!! Form::text('latitude', $row['latitude'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
								</div>
								<div class="col-md-2">

								</div>
							</div>
							<div class="form-group  " >
								<label for="Longitude" class=" control-label col-md-4 text-left">
									{{Lang::get('travel.hotel_longitude')}}
								</label>
								<div class="col-md-6">
									{!! Form::text('longitude', $row['longitude'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
								</div>
								<div class="col-md-2">

								</div>
							</div>
						</div>
					</div>

					<div class="panel panel-warning">
						<div class="panel-heading">
							{{Lang::get('travel.hotel_location')}}
						</div>
						<div class="panel-body">

							<div class="form-group  " >
								<label for="continent_id" class=" control-label col-md-4 text-left">
									{{Lang::get('travel.hotel_location_continent')}}
								</label>
								<div class="col-md-6">
									<select name='continent_id' rows='5' id='continent_id' code='{$continent_id}'
									class='select2 '  required  ></select>
								</div>
								<div class="col-md-2"></div>
							</div>
							<div class="form-group  " >
								<label for="country_id" class=" control-label col-md-4 text-left">
									{{Lang::get('travel.hotel_location_country')}}
								</label>
								<div class="col-md-6">
									<select name='country_id' rows='5' id='country_id' code='{$country_id}'
									class='select2'></select>
								</div>
								<div class="col-md-2"></div>
							</div>
							<div class="form-group  " >
								<label for="area_id" class=" control-label col-md-4 text-left">
									{{Lang::get('travel.hotel_location_area')}}
								</label>
								<div class="col-md-6">
									<select name='area_id' rows='5' id='area_id' code='{$area_id}'
									class='select2 '  ></select>
								</div>
								<div class="col-md-2"></div>
							</div>
							<div class="form-group  " >
								<label for="city_id" class=" control-label col-md-4 text-left">
									{{Lang::get('travel.hotel_location_city')}}
								</label>
								<div class="col-md-6">
									<select name='city_id' rows='5' id='city_id' code='{$city_id}'
									class='select2 ' ></select>
								</div>
								<div class="col-md-2"></div>
							</div>

						</div>
					</div>




				</div>


				<div class="col-md-6">
					<div class="panel panel-warning">
						<div class="panel-heading">
							{{Lang::get('travel.hotel_extradetails')}}
						</div>
						<div class="panel-body">
							<div class="form-group  " >
								<label for="Class" class=" control-label col-md-4 text-left">
									{{Lang::get('travel.hotel_class')}}
								</label>
								<div class="col-md-6">
									<label class='radio radio-inline'>
										<input type='radio' name='class' value ='1' required @if($row['class'] == '1') checked="checked" @endif >
										1 </label>
									<label class='radio radio-inline'>
										<input type='radio' name='class' value ='2' required @if($row['class'] == '2') checked="checked" @endif >
										2 </label>
									<label class='radio radio-inline'>
										<input type='radio' name='class' value ='3' required @if($row['class'] == '3') checked="checked" @endif >
										3 </label>
									<label class='radio radio-inline'>
										<input type='radio' name='class' value ='4' required @if($row['class'] == '4') checked="checked" @endif >
										4 </label>
									<label class='radio radio-inline'>
										<input type='radio' name='class' value ='5' required @if($row['class'] == '5') checked="checked" @endif >
										5 </label>
								</div>
								<div class="col-md-2">

								</div>
							</div>
							<div class="form-group  " >
								<label for="themes" class=" control-label col-md-4 text-left">
									{{Lang::get('travel.hotel_themes')}}
								</label>
								<div class="col-md-6">
									<select name='themes[]' rows='5' id='themes' code='{$themes}'
									class='select2 ' multiple="multiple" ></select>
								</div>
								<div class="col-md-2"></div>
							</div>
							<div class="form-group  " >
								<label for="continent_id" class=" control-label col-md-4 text-left">
									{{Lang::get('travel.hotel_amenities')}}
								</label>
								<div class="col-md-6">
									<select name='amenities[]' rows='5' id='amenities' code='{$amenities}'
									class='select2 ' multiple="multiple" ></select>
								</div>
								<div class="col-md-2"></div>
							</div>

							<div class="form-group  " >
								<label for="Extra Class" class=" control-label col-md-4 text-left">
									{{Lang::get('travel.hotel_extraclass')}}
								</label>
								<div class="col-md-6">
									{!! Form::text('extra_class', $row['extra_class'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
								</div>
								<div class="col-md-2">

								</div>
							</div>

							<div class="form-group  " >
								<label for="Use Individually" class=" control-label col-md-4 text-left">
									{{Lang::get('travel.hotel_individually')}}
								</label>
								<div class="col-md-6">
									<label class='radio radio-inline'>
										<input type='radio' name='use_individually' value ='0' required @if($row['use_individually'] == '0') checked="checked" @endif >
										Nu </label>
									<label class='radio radio-inline'>
										<input type='radio' name='use_individually' value ='1' required @if($row['use_individually'] == '1') checked="checked" @endif >
										Da </label>
								</div>
								<div class="col-md-2">

								</div>
							</div>
							<div class="form-group  " >
								<label for="Use On Packages" class=" control-label col-md-4 text-left">
									{{Lang::get('travel.hotel_packages')}}
								</label>
								<div class="col-md-6">
									<label class='radio radio-inline'>
										<input type='radio' name='use_on_packages' value ='0' required @if($row['use_on_packages'] == '0') checked="checked" @endif >
										Nu </label>
									<label class='radio radio-inline'>
										<input type='radio' name='use_on_packages' value ='1' required @if($row['use_on_packages'] == '1') checked="checked" @endif >
										Da </label>
								</div>
								<div class="col-md-2">

								</div>
							</div>

						</div>
					</div>
				</div>

				<div class="col-md-6">
					<div class="panel panel-danger">
						<div class="panel-heading">
							{{Lang::get('travel.hotel_room_categories')}}&nbsp;&nbsp;
							@if($row['is_local'])
							<a href="javascript:;" onclick="addRoomCategory();" class="tips label label-primary" title="{{ Lang::get('core.btn_add') }}"><i class="fa fa-plus-square"></i></a>
							@endif
						</div>
						<div class="panel-body">
							<input type="hidden" id="lastIdRoomCategories" name="no_room_categories" value="{{count($row['room_categories'])}}" />
							@if($row['is_local'])
								@for($i = 0; $i < count($row['room_categories']); $i++)
								<div class="form-group" id="room_category_{{$i}}">
									<label for="label" class="control-label col-md-4 text-left">
										Nume camera
									</label>
									<div class="col-md-6">
										<input class="form-control" placeholder=""  name="roomCategory[{{$i}}][name]" type="text" value="{{$row['room_categories'][$i]['name']}}" >
										<input type="hidden" name="roomCategory[{{$i}}][id]" type="text" value="{{$row['room_categories'][$i]['id']}}" />
										<input type="hidden" name="roomCategory[{{$i}}][deleted]" type="text" value="0" />
									</div>
									<div class="col-md-2">
										<a href="javascript:;" onclick="deleteRoomCategory({{$i}});" class="tips jdelete btn btn-xs btn-white" title="{{ Lang::get('core.btn_remove') }}"><i class="fa fa-trash-o"></i></a>
									</div>
								</div>
								@endfor
							@else
								@foreach ($row['room_categories'] as $roomCategory)
								<div class="form-group  " id="room_category_{{$roomCategory['id']}}">
									<label for="label" class=" control-label col-md-4 text-left">
										Nume camera
									</label>
									<div class="col-md-6">
										<input class="form-control" placeholder=""  type="text" value="{{$roomCategory['name']}}" readonly>
									</div>

								</div>
								@endforeach
							@endif
							<div id="newRoomCategories"></div>
						</div>
					</div>
				</div>

				<div class="col-md-6">
					<div class="panel panel-danger">
						<div class="panel-heading">
							{{Lang::get('travel.hotel_description')}}&nbsp;&nbsp;
							<a href="javascript:;" onclick="addDetail();" class="tips label label-primary" title="{{ Lang::get('core.btn_add') }}"><i class="fa fa-plus-square"></i></a>
						</div>
						<div class="panel-body">
							<input name="detail" id="detail" type="hidden" value="0" />
							@foreach ($row['detailed_descriptions'] as $key => $detail)

							<div class="form-group  " id="detail_{{$detail['id']}}">
								<label for="label" class=" control-label col-md-4 text-left">
									Detail
								</label>
								<div class="col-md-6">
									<input class="form-control" placeholder="" name="label_{{$detail['id']}}" type="text" value="{{$detail['label']}}">
									<textarea name='detail_{{ $detail['id'] }}' rows='3' id='detail_{{ $detail['id'] }}' class='editor form-control ' >{{$detail['text']}}</textarea>
									<input name="delete_{{ $detail['id'] }}" id="delete_{{ $detail['id'] }}" type="hidden" value="0" />
								</div>
								<div class="col-md-2">
									<a href="javascript:;" onclick="deleteDetail({{$detail['id']}});" class="tips jdelete btn btn-xs btn-white" title="{{ Lang::get('core.btn_remove') }}"></a>
								</div>
							</div>
							@endforeach

							<div id="content_details"></div>
						</div>
					</div>
				</div>

				<div style="clear:both"></div>

				<div class="form-group">
					<label class="col-sm-4 text-right">&nbsp;</label>
					<div class="col-sm-8">
						<button type="submit" name="apply" class="btn btn-info btn-sm" >
							<i class="fa  fa-check-circle"></i> {{ Lang::get('core.sb_apply') }}
						</button>
						<button type="submit" name="submit" class="btn btn-primary btn-sm" >
							<i class="fa  fa-save "></i> {{ Lang::get('core.sb_save') }}
						</button>
						<button type="button" onclick="location.href='{{ URL::to('core/users?return='.$return) }}' " class="btn btn-success btn-sm ">
							<i class="fa  fa-arrow-circle-left "></i> {{ Lang::get('core.sb_cancel') }}
						</button>
					</div>

				</div>

				{!! Form::close() !!}

				<div class="panel panel-danger">
					<div class="panel-heading">
					{{Lang::get('travel.hotel_images')}}&nbsp;&nbsp;

					</div>
					<!-- drag and drop -->
						<div class="panel-body " id="content_image" style="">

							@foreach ($row['images'] as $image)

							<div class="form-group" id="div_image_{{$image->id}}">
							<label for="label" class=" control-label col-md-4 text-left">
							{{$image->name}}
							</label>

							<div class="col-md-2">
							@if($image->soap_client == 'HO')
							{!! SiteHelpers::showUploadedFile($image->name,'/images/offers/') !!}
							@else
							{!! SiteHelpers::showUploadedFile($image->name,'/images/offers/'.$image->soap_client.'/') !!}
							@endif
							</div>
							<div class="col-md-2">
							<a href="/adminhotels/deleteimage/{{$image->id}}"  class="tips jdelete btn btn-xs btn-white" title="{{ Lang::get('core.btn_remove') }}"><i class="fa fa-trash-o"> Sterge Imagine</i></a>
							</div>
							</div>
							<div style="clear:both"></div>
							@endforeach

							<div class="uploadimg">
								<form action="/adminhotels/upload"  class="dropzone" id="upimg" enctype="multipart/form-data">
										<input type="text" name="hotelid" value="{{$row['idx']}}" style="display:none;">

								</form>
							</div>


								<a href="{{'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']}}" type="button"  class="btn btn-primary btn-sm" style="display:block;margin:0 auto;width:15%;margin-top:15px;">
									<i class="fa fa-save "></i> {{ Lang::get('core.sb_saveimage') }}
								</a>
						</div>

					</div>
			</div>



		</div>
	</div>
</div>



<script type="text/javascript">
	$(".uploadimg").dropzone({ url: "/adminhotels/upload" });
</script>
<style type="text/css">
    .bootstrap-switch.bootstrap-switch-large .bootstrap-switch-handle-off, .bootstrap-switch.bootstrap-switch-large .bootstrap-switch-handle-on, .bootstrap-switch.bootstrap-switch-large .bootstrap-switch-label {
        padding: 2px 0px !important;
        font-size: 11px !important;
        width: 30px !important;
    }
</style>
<script type="text/javascript">
	$(document).ready(function() {

		$("#continent_id").jCombo("{{ URL::to('adminhotels/comboselect?filter=geographies:id:name#limit=WHERE:id_parent:=:1') }}", {
			selected_value : '{{ $row["continent_id"] }}'
		});

		$("#country_id").jCombo("{{ URL::to('adminhotels/comboselect?filter=geographies:id:name#limit=WHERE:id_parent:=:') }}", {
			selected_value : '{{ $row["country_id"] }}',
			parent : "#continent_id",
			parent_value : '{{ $row["continent_id"] }}'
		});

		$("#area_id").jCombo("{{ URL::to('adminhotels/comboselect?filter=geographies:id:name#limit=WHERE:id_parent:=:') }}", {
			selected_value : '{{ $row["area_id"] }}',
			parent : "#country_id",
			parent_value : '{{ $row["country_id"] }}'
		});

		$("#city_id").jCombo("{{ URL::to('adminhotels/comboselect?filter=geographies:id:name#limit=WHERE:id_parent:=:') }}", {
			selected_value : '{{ $row["city_id"] }}',
			parent : "#area_id",
			parent_value : '{{ $row["area_id"] }}'
		});




		$("#themes").jCombo("{{ URL::to('adminhotels/comboselect?filter=admin_themes:text:text') }}", {
			selected_value : '{{ $row["themes"] }}'
		});
		$("#amenities").jCombo("{{ URL::to('adminhotels/comboselect?filter=admin_amenities:text:text') }}", {
			selected_value : '{{ $row["amenities"] }}'
		});


		// $('.birthday').datepicker({
			// format : 'mm-dd-yyyy',
			// autoClose : true
		// })


	});


	function deleteDetail(id) {
	    if (confirm("Are you sure?")) {
	    	$('#delete_'+id).val(1);
	    	$('#detail_'+id).hide();
	    }
	    return false;
	}


	function addDetail() {


		var detail = parseInt($('#detail').val())+1;
		$('#detail').val(detail);

		var html = '<div class="form-group  " id="detail_label_'+detail+'"><label for="label" class=" control-label col-md-4 text-left">Detail</label>';
		html += '<div class="col-md-6">';
		html += '<input class="form-control" placeholder="" name="label_new_'+detail+'" type="text" value="">';
		html += '<textarea id="detail_new_'+detail+'" name="detail_new_'+detail+'" rows="3" class="editor form-control"" ></textarea>';
		html += '</div>';
		html += '<div class="col-md-2"><a href="javascript:;" onclick="if (confirm(\'Are you sure?\')) { $(\'#detail_label_'+detail+'\').remove(); }else {return false;}" class="tips jdelete btn btn-xs btn-white" title="{{ Lang::get('core.btn_remove') }}"></a></div></div>';

		$('#content_details').append(html);

		$('#detail_new_'+detail).summernote();

	}

	function addRoomCategory(){
		var lastIdInput = $("#lastIdRoomCategories");
		var newRoomCategoriesDiv = $("#newRoomCategories");
		var newId = lastIdInput.val();
		//console.log(newId);
		var html = '<div class="form-group" id="room_category_'+newId+'">'+
				   '<label for="label" class="control-label col-md-4 text-left">Nume camera</label>'+
				   '<div class="col-md-6">'+
				   '<input class="form-control" placeholder=""  name="roomCategory['+newId+'][name]" type="text" value="" />'+
				   '<input type="hidden" name="roomCategory['+newId+'][id]" type="text" value="0" />'+
				   '<input type="hidden" name="roomCategory['+newId+'][deleted]" type="text" value="0" />'+
				   '</div>'+
				   '<div class="col-md-2">'+
				   '<a href="javascript:;" onclick="deleteRoomCategory('+newId+');" class="tips jdelete btn btn-xs btn-white" title="core.btn_remove"><i class="fa fa-trash-o"></i></a>'+
				   '</div></div>';
		newRoomCategoriesDiv.append(html);
		lastIdInput.val(parseInt(newId)+1);
	}

	function deleteRoomCategory(id){
		if (confirm("Are you sure?")) {
			var roomCatDiv = $('#room_category_'+id);
			var deletedInput = $('input[name="roomCategory['+id+'][deleted]"]');
			roomCatDiv.hide();
			deletedInput.val(1);
		}
		return false;
	}

	function deleteImage(id){
		if (confirm("Are you sure?")) {
			$('#div_image_'+id).hide();
			$('#image_'+id+'_delete').val(1);
		}
		return false;
	}

	function addImage() {
		var img = parseInt($('#new_image').val())+1;
		$('#new_image').val(img);

		var html = '<div class="form-group  " id="div_new_'+img+'"><label for="label" class=" control-label col-md-4 text-left">Imagine</label>';
		html += '<div class="col-md-4">';
		html += '<input  type="file" name="image_new_'+img+'" id="image_new_'+img+'" style="width:150px !important;"  />';
		html += '</div>';
		html += '<div class="col-md-2"><img src="/uploads/images/no-image.png" border="0" width="50" class="img-square"></div>';
		html += '<div class="col-md-2"><a href="javascript:;" onclick="if (confirm(\'Are you sure?\')) { $(\'#div_new_'+img+'\').remove(); }else {return false;}" class="tips jdelete btn btn-xs btn-white" title="{{ Lang::get('core.btn_remove') }}"></a></div></div>';
		$('#content_image').append(html);
	}

</script>
@stop
