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
				<a href="{{ URL::to('admingeographiestemp?return='.$return) }}">{{ $pageTitle }}</a>
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

				{!! Form::open(array('url'=>'admingeographiestemp/save?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
				<div class="col-md-12">
					<fieldset>
						<legend>
							Mapping Geographies - <b>XT</b>
						</legend>
						
						@foreach ($results['rows'] as $key => $row)
						<div class="form-group">
							<label for="label" class=" control-label col-md-2 text-left"> 
								{{$row->parrent}} - <b>{{$row->name}}</b> 
							</label>
							<div class="col-md-2">
								<select id="geo{{$key}}" name='geo_map' rows='5' id='geo_map' code='' class='select2 geo'  required  ></select>
							</div>
							<div class="col-md-8">
							</div>
						</div>
						@endforeach
							

					</fieldset>
				</div>

				<div style="clear:both"></div>

				<div class="form-group">
					<div class="col-sm-4  text-right">
						<button type="submit" name="apply" class="btn btn-info btn-sm" >
							<i class="fa  fa-check-circle"></i> {{ Lang::get('core.sb_apply') }}
						</button>
						<button type="submit" name="submit" class="btn btn-primary btn-sm" >
							<i class="fa  fa-save "></i> {{ Lang::get('core.sb_save') }}
						</button>
						<button type="button" onclick="location.href='{{ URL::to('admingeographiestemp?return='.$return) }}' " class="btn btn-success btn-sm ">
							<i class="fa  fa-arrow-circle-left "></i> {{ Lang::get('core.sb_cancel') }}
						</button>
					</div>
					<label class="col-sm-8 text-right">&nbsp;</label>

				</div>

				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {


		@foreach ($results['rows'] as $key => $row)
			$("#geo{{$key}}").jCombo("{{ URL::to('admingeographiestemp/comboselect?filter=geographies:id:name') }}");
		@endforeach

		


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