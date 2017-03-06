@extends('layouts.app')

@section('content')
<div class="page-content row">
	<!-- Page header -->
	<div class="page-header">
		<div class="page-title">
			<h3> Import/Export data</h3>
		</div>

	</div>
	
	<div class="page-content-wrapper m-t">	 	

		<div class="sbox animated fadeInRight">
			<div class="sbox-title"> <h5> <i class="fa fa-table"></i> </h5>

			</div>
			
			<div class="table-responsive" style="min-height:300px;">


				<div class="container">
					<div class="row form-group">
						<div class="col-xs-12">
							<ul class="nav nav-pills nav-justified thumbnail setup-panel">
								<li class="active"><a href="#step-1">
									<h4 class="list-group-item-heading">Step 1</h4>

								</a></li>
								<li class="disabled"><a href="#step-2">
									<h4 class="list-group-item-heading">Step 2</h4>

								</a></li>
							</ul>
						</div>
					</div>
					<div class="row setup-content" id="step-1">
						<div class="col-xs-12">
							<h1 class="text-center">  STEP 1</h1>
							<div class="col-md-12 well text-center">
								<div class="form-group">
										{!! Form::open(array('url'=>'importdata/importhotels/one', 'enctype'=> 'multipart/form-data', 'class'=>'form-horizontal' ,'id' =>'SximoTable' )) !!}
										<div class="col-md-10">
									
											<div class="form-group  " >
												<label for="file" class=" control-label col-md-4 text-left"> 
													{{Lang::get('travel.import_file')}} 
												</label>
												<div class="col-md-8">
													<input type="file" name="csv_file" id="csv_file" required>
												</div>
											</div>
											<div class="form-group  " >
												<label for="file" class=" control-label col-md-4 text-left"> 
													{{Lang::get('travel.soap_client')}} 
												</label>
												<div class="col-md-8">
													<input class="form-control" placeholder="" name="soap_client" type="text" value="" />
												</div>
											</div>
											<div class="form-group  " >
												<label for="file" class=" control-label col-md-4 text-left"> 
													{{Lang::get('travel.import_name')}} 
												</label>
												<div class="col-md-8">
													<input class="form-control" placeholder="" name="name" type="text" value="" />
												</div>
											</div>
											<div class="form-group  " >
												<label for="file" class=" control-label col-md-4 text-left"> 
													{{Lang::get('travel.import_description')}} 
												</label>
												<div class="col-md-8">
													<textarea name="description" rows="3" class="editor form-control"></textarea>
												</div>
											</div>
											<button type="submit" name="import1" class="btn btn-lg btn-primary pull-right">{{Lang::get('travel.import_data')}} </button>										
										</div>
										
										
										<div class="col-md-2">
											
										</div>
										{!! Form::close() !!}
									</div>
								</div>

							</div>
						</div>
					</div>
					<div class="row setup-content" id="step-2">
						<div class="col-xs-12">
							<h1 class="text-center"> STEP 2</h1>
							<div class="col-md-12 well">
								{!! Form::open(array('url'=>'importdata/importhotels/two', 'enctype'=> 'multipart/form-data', 'class'=>'form-horizontal' ,'id' =>'SximoTable','novalidate' => '' )) !!}
								@if($step == 'one')
									<?php 
									$data= serialize($columns); 
									$encoded=htmlentities($data, ENT_QUOTES );
									$alphas = range('A', 'Z'); 
									?>
									@while (current($columns)) 
									
									<div class="form-group">
										<label class="col-md-2 control-label">{{ key($columns) }}</label>
										<div class="col-md-2">
											@for($i=0;$i <= count($columns); $i++)
											@if($i==0)
											<?php  //reset($columns); ?>
											<select name="select_{{ key($columns) }}">
												<option value="1000">Select</option>
												@foreach ($column_scv as $key => $element)
												<option value="{{ $key }}"> ({{ $alphas[$key] }}) - {{ $element }}</option>
												@endforeach
												<option value="IMP_{{$soap_client}}">{{$soap_client}}</option>
											</select>
											{{ next($columns) }}
											@endif
											@endfor
										</div>
									</div>
									@endwhile
									<div class="form-group">
										<div class="col-md-2"></div>
										<div class="col-md-2">
											<input type="hidden" name="json_csv" value="{{ serialize($columns_scv) }}">
											<button type="submit" name="import" class="btn btn-lg btn-primary">Import data</button>
										</div>
										<div class="col-md-8"></div>
								@endif
								{!! Form::close() !!}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		{!! Form::close() !!}

	</div>	
</div>	  
</div>	
<script type="text/javascript">
	$(document).ready(function() {

		var navListItems = $('ul.setup-panel li a'),
		allWells = $('.setup-content');

		allWells.hide();

		navListItems.click(function(e)
		{
			e.preventDefault();
			var $target = $($(this).attr('href')),
			$item = $(this).closest('li');

			if (!$item.hasClass('disabled')) {
				navListItems.closest('li').removeClass('active');
				$item.addClass('active');
				allWells.hide();
				$target.show();
			}
		});

		$('ul.setup-panel li.active a').trigger('click');

		
		var url      = window.location.href;  
		var param = url.substring(url.lastIndexOf('/') + 1);
		if (param == 'one') {
			$('ul.setup-panel li:eq(1)').removeClass('disabled');
			$('ul.setup-panel li a[href="#step-2"]').trigger('click');
			$(this).remove();
		}    
	});


</script>
@stop