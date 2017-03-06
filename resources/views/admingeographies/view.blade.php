@extends('layouts.app')

@section('content')
<div class="page-content row">
    <!-- Page header -->
    <div class="page-header">
      <div class="page-title">
        <h3> {{ $pageTitle }} <small>{{ $pageNote }}</small></h3>
      </div>
      <ul class="breadcrumb">
        <li><a href="{{ URL::to('dashboard') }}">{{ Lang::get('core.home') }}</a></li>
		<li><a href="{{ URL::to('admingeographies?return='.$return) }}">{{ $pageTitle }}</a></li>
        <li class="active"> {{ Lang::get('core.detail') }} </li>
      </ul>
	 </div>  
	 
	 
 	<div class="page-content-wrapper m-t">   

<div class="sbox animated fadeInRight">
	<div class="sbox-title"> 
   		<a href="{{ URL::to('admingeographies?return='.$return) }}" class="tips btn btn-xs btn-default pull-right" title="{{ Lang::get('core.btn_back') }}"><i class="fa fa-arrow-circle-left"></i>&nbsp;{{ Lang::get('core.btn_back') }}</a>
		@if($access['is_add'] ==1)
   		<a href="{{ URL::to('admingeographies/update/'.$id.'?return='.$return) }}" class="tips btn btn-xs btn-primary pull-right" title="{{ Lang::get('core.btn_edit') }}"><i class="fa fa-edit"></i>&nbsp;{{ Lang::get('core.btn_edit') }}</a>
		@endif 
	</div>
	<div class="sbox-content" style="background:#fff;"> 	

		<table class="table table-striped table-bordered" >
			<tbody>	
		
					<tr>
						<td width='30%' class='label-view text-right'>Id</td>
						<td>{{ $row->id }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Int Name</td>
						<td>{{ $row->int_name }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Child Label</td>
						<td>{{ $row->child_label }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Description</td>
						<td>{{ $row->description }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Tree Level</td>
						<td>{{ $row->tree_level }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Id Parent</td>
						<td>{{ $row->id_parent }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Name</td>
						<td>{{ $row->name }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Min Val</td>
						<td>{{ $row->min_val }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Max Val</td>
						<td>{{ $row->max_val }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Availability</td>
						<td>{{ $row->availability }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>Available in Stays</td>
						<td>{{ $row->available_in_stays }} </td>
						
					</tr>
					
					<tr>
						<td width='30%' class='label-view text-right'>Available in Circuits</td>
						<td>{{ $row->available_in_circuits }} </td>
						
					</tr>
				
			</tbody>	
		</table>   

	 
	
	</div>
</div>	

	</div>
</div>
	  
@stop