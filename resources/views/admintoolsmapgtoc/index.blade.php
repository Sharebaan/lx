@extends('layouts.app')
@section('content')
  <div class="page-content row">
    <!-- Page header -->
    <div class="page-header">
      <div class="page-title">
        <h3> {{ $pageTitle }} <small>{{ $pageNote }}</small></h3>
      </div>

      <ul class="breadcrumb">
        <li><a href="{{ URL::to('dashboard') }}"> Dashboard </a></li>
        <li class="active">{{ $pageTitle }}</li>
      </ul>	  
	  
    </div>

    <div class="page-content-wrapper m-t">	
		{!! Form::open(array('url'=>'admintoolsmapgtoc/save?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
		<div class="col-md-6">
			<div class="sbox">
				<div class="sbox-title">
					<h4>
						<i class="fa fa-cog"></i> {{Lang::get('travel.mapgtoc_categories')}}
					</h4>
				</div>
				<div class="sbox-content">	
					<div class="form-group  " >
						<div class="col-md-12">
							@foreach($categories as $category)
								<label class='radio radio-inline ba-radio-no-padding-left'>
									<input type='checkbox' name='categories[]' value ='{{$category->id}}' > {{$category->name}} 
								</label>
								<br/>
								<br/>
							@endforeach
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="sbox">
				<div class="sbox-title">
					<h4>
						<i class="fa fa-cog"></i> {{Lang::get('travel.mapgtoc_geographies')}}
					</h4>
				</div>
				<div class="sbox-content">	
					<div class="form-group  " >
						<div class="col-md-12">
							@foreach($geographies as $geography)
								<label style="margin-left: {{$geography->tree_level * 25}}px" class='radio radio-inline ba-radio-no-padding-left'>
									<input type='checkbox' name='geographies[]' value ='{{$geography->id}}' > {{$geography->name}} 
								</label>
								<br/>
								<br/>
							@endforeach
						</div>
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
				<button type="button" onclick="location.href='{{ URL::to('admintoolsmapgtoc?return='.$return) }}' " class="btn btn-success btn-sm "><i class="fa  fa-arrow-circle-left "></i>  {{ Lang::get('core.sb_cancel') }} </button>
				</center>	  
		
			  </div>
			</div>
		</div>
	</div>
    </div>

	
@stop