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
		<li><a href="{{ URL::to('adminhotels?return='.$return) }}">{{ $pageTitle }}</a></li>
        <li class="active"> {{ Lang::get('core.detail') }} </li>
      </ul>
	 </div>


 	<div class="page-content-wrapper m-t">

<div class="sbox animated fadeInRight">
	<div class="sbox-title">
   		<a href="{{ URL::to('adminhotels?return='.$return) }}" class="tips btn btn-xs btn-default pull-right" title="{{ Lang::get('core.btn_back') }}"><i class="fa fa-arrow-circle-left"></i>&nbsp;{{ Lang::get('core.btn_back') }}</a>
		@if($access['is_add'] ==1)
   		<a href="{{ URL::to('adminhotels/update/'.$id.'?return='.$return) }}" class="tips btn btn-xs btn-primary pull-right" title="{{ Lang::get('core.btn_edit') }}"><i class="fa fa-edit"></i>&nbsp;{{ Lang::get('core.btn_edit') }}</a>
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
						<td width='30%' class='label-view text-right'>Code</td>
						<td>{{ $row->code }} </td>

					</tr>

					<tr>
						<td width='30%' class='label-view text-right'>Name</td>
						<td>{{ $row->name }} </td>

					</tr>

					<tr>
						<td width='30%' class='label-view text-right'>Available</td>
						<td>{{ $row->available }} </td>
					</tr>

					<tr>
						<td width='30%' class='label-view text-right'>Class</td>
						<td>{{ $row->class }} </td>

					</tr>

					<tr>
						<td width='30%' class='label-view text-right'>Description</td>
						<td>{{ $row->description }} </td>

					</tr>

					<tr>
						<td width='30%' class='label-view text-right'>Address</td>
						<td>{{ $row->address }} </td>

					</tr>

					<tr>
						<td width='30%' class='label-view text-right'>Zip</td>
						<td>{{ $row->zip }} </td>

					</tr>

					<tr>
						<td width='30%' class='label-view text-right'>Phone</td>
						<td>{{ $row->phone }} </td>

					</tr>

					<tr>
						<td width='30%' class='label-view text-right'>Fax</td>
						<td>{{ $row->fax }} </td>

					</tr>

					<tr>
						<td width='30%' class='label-view text-right'>Location</td>
						<td>{{ $row->location }} </td>

					</tr>

					<tr>
						<td width='30%' class='label-view text-right'>Url</td>
						<td>{{ $row->url }} </td>

					</tr>

					<tr>
						<td width='30%' class='label-view text-right'>Latitude</td>
						<td>{{ $row->latitude }} </td>

					</tr>

					<tr>
						<td width='30%' class='label-view text-right'>Longitude</td>
						<td>{{ $row->longitude }} </td>

					</tr>

					<tr>
						<td width='30%' class='label-view text-right'>Extra Class</td>
						<td>{{ $row->extra_class }} </td>

					</tr>

					<tr>
						<td width='30%' class='label-view text-right'>Use Individually</td>
						<td>{{ $row->use_individually }} </td>

					</tr>

					<tr>
						<td width='30%' class='label-view text-right'>Use On Packages</td>
						<td>{{ $row->use_on_packages }} </td>

					</tr>

					<tr>
						<td width='30%' class='label-view text-right'>Soap Client</td>
						<td>{{ $row->soap_client }} </td>

					</tr>

			</tbody>
		</table>



	</div>
</div>

	</div>
</div>

@stop