@extends('layouts.app')

@section('content')

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
				<a href="{{ URL::to('admingeographies?return='.$return) }}">{{ $pageTitle }}</a>
			</li>
			<li class="active">
				{{ Lang::get('core.addedit') }}
			</li>
		</ul>

	</div>

	<div class="page-content-wrapper">

		<ul class="parsley-error-list">
			@foreach($errors->all() as $error)
			<li>
				{{ $error }}
			</li>
			@endforeach
		</ul>
		<div class="sbox animated fadeInRight">
			<div class="sbox-title">
				<h4><i class="fa fa-table"></i></h4>
			</div>
			<div class="sbox-content">

				{!! Form::open(array('url'=>'admingeographies/save?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
				<div class="col-md-12">
					<fieldset>
						<legend>
							Geographies
						</legend>

						<input type="hidden" name="id" value="{{isset($row['id']) ? $row['id'] : 0}}" />
						<div class="form-group  " >
							<label for="Int Name" class=" control-label col-md-4 text-left"> Int Name </label>
							<div class="col-md-6">
								{!! Form::text('int_name', $row['int_name'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
							</div>
							<div class="col-md-2">

							</div>
						</div>
						<div class="form-group  " >
							<label for="Child Label" class=" control-label col-md-4 text-left"> Child Label </label>
							<div class="col-md-6">
								{!! Form::text('child_label', $row['child_label'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
							</div>
							<div class="col-md-2">

							</div>
						</div>
						<div class="form-group  " >
							<label for="Description" class=" control-label col-md-4 text-left"> Description </label>
							<div class="col-md-6">
								{!! Form::text('description', $row['description'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
							</div>
							<div class="col-md-2">

							</div>
						</div>
						<div class="form-group  " >
							<label for="Tree Level" class=" control-label col-md-4 text-left"> Tree Level </label>
							<div class="col-md-6">
								{!! Form::text('tree_level', $row['tree_level'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
							</div>
							<div class="col-md-2">

							</div>
						</div>
						<div class="form-group  " >
							<label for="Id Parent" class=" control-label col-md-4 text-left"> Id Parent </label>
							<div class="col-md-6">
								<select name='id_parent' rows='5' id='id_parent' class='select2 '   ></select>
							</div>
							<div class="col-md-2">

							</div>
						</div>
						<div class="form-group  " >
							<label for="Name" class=" control-label col-md-4 text-left"> Name </label>
							<div class="col-md-6">
								{!! Form::text('name', $row['name'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
							</div>
							<div class="col-md-2">

							</div>
						</div>
						<div class="form-group  " >
							<label for="Min Val" class=" control-label col-md-4 text-left"> Min Val </label>
							<div class="col-md-6">
								{!! Form::text('min_val', $row['min_val'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
							</div>
							<div class="col-md-2">

							</div>
						</div>
						<div class="form-group  " >
							<label for="Max Val" class=" control-label col-md-4 text-left"> Max Val </label>
							<div class="col-md-6">
								{!! Form::text('max_val', $row['max_val'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
							</div>
							<div class="col-md-2">

							</div>
						</div>
						<div class="form-group  " >
							<label for="Availability" class=" control-label col-md-4 text-left"> Availability </label>
							<div class="col-md-6">
								<select name='availability[]' multiple rows='5' id='availability' class='select2 ' ></select>
							</div>
							<div class="col-md-2">

							</div>
						</div>
						<div class="form-group  " >
							<label for="Available in Stays" class=" control-label col-md-4 text-left"> Available in Stays </label>
							<div class="col-md-6">
								<label style="padding-left: 0px !important;" class='radio radio-inline ba-radio-no-padding-left'>
									<input type='checkbox' value="1" name='available_in_stays' @if($row['available_in_stays'] == "1") checked="checked" @endif >
								</label>
							</div>
							<div class="col-md-2">

							</div>
						</div>
						<div class="form-group  " >
							<label for="Available in Circuits" class=" control-label col-md-4 text-left"> Available in Circuits </label>
							<div class="col-md-6">
								<label style="padding-left: 0px !important;" class='radio radio-inline ba-radio-no-padding-left'>
									<input type='checkbox' value="1"  name='available_in_circuits' @if($row['available_in_circuits'] == "1") checked="checked" @endif >
								</label>
							</div>
							<div class="col-md-2">

							</div>
						</div>
					</fieldset>
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
						<button type="button" onclick="location.href='{{ URL::to('admingeographies?return='.$return) }}' " class="btn btn-success btn-sm ">
							<i class="fa  fa-arrow-circle-left "></i> {{ Lang::get('core.sb_cancel') }}
						</button>
					</div>

				</div>

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {

		$("#id_parent").jCombo("{{ URL::to('admingeographies/comboselect?filter=geographies:id:name') }}", {
			selected_value : '{{ $row["id_parent"] }}'
		});

		$("#availability").jCombo("{{ URL::to('admingeographies/comboselect?filter=geographies_temp:idx:soap_client|name') }}", {
			selected_value : '{{ $row["availability"] }}'
		});

		$('.removeCurrentFiles').on('click', function() {
			var removeUrl = $(this).attr('href');
			$.get(removeUrl, function(response) {
			});
			$(this).parent('div').empty();
			return false;
		});

	});
</script>
@stop
