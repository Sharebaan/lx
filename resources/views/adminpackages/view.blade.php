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
		<li><a href="{{ URL::to('adminpackages?return='.$return) }}">{{ $pageTitle }}</a></li>
        <li class="active"> {{ Lang::get('core.detail') }} </li>
      </ul>
	 </div>


 	<div class="page-content-wrapper m-t">

<div class="sbox animated fadeInRight">
	<div class="sbox-title">
   		<a href="{{ URL::to('adminpackages?return='.$return) }}" class="tips btn btn-xs btn-default pull-right" title="{{ Lang::get('core.btn_back') }}"><i class="fa fa-arrow-circle-left"></i>&nbsp;{{ Lang::get('core.btn_back') }}</a>
		@if($access['is_add'] ==1)
   		<a href="{{ URL::to('adminpackages/update/'.$id.'?return='.$return) }}" class="tips btn btn-xs btn-primary pull-right" title="{{ Lang::get('core.btn_edit') }}"><i class="fa fa-edit"></i>&nbsp;{{ Lang::get('core.btn_edit') }}</a>
		@endif
	</div>
	<div class="sbox-content" style="background:#fff;">

		<table class="table table-striped table-bordered" >
			<tbody>

					<tr>
						<td width='30%' class='label-view text-right'>Idx</td>
						<td>{{ $row->idx }} </td>

					</tr>

					<tr>
						<td width='30%' class='label-view text-right'>Id</td>
						<td>{{ $row->id }} </td>

					</tr>

					<tr>
						<td width='30%' class='label-view text-right'>Name</td>
						<td>{{ $row->name }} </td>

					</tr>

					<tr>
						<td width='30%' class='label-view text-right'>Is Tour</td>
						<td>{{ $row->is_tour }} </td>

					</tr>

					<tr>
						<td width='30%' class='label-view text-right'>Is Bus</td>
						<td>{{ $row->is_bus }} </td>

					</tr>

					<tr>
						<td width='30%' class='label-view text-right'>Is Flight</td>
						<td>{{ $row->is_flight }} </td>

					</tr>

					<tr>
						<td width='30%' class='label-view text-right'>Duration</td>
						<td>{{ $row->duration }} </td>

					</tr>

					<tr>
						<td width='30%' class='label-view text-right'>Id Hotel</td>
						<td>{{ $row->id_hotel }} </td>

					</tr>

					<tr>
						<td width='30%' class='label-view text-right'>Outbound Transport Duration</td>
						<td>{{ $row->outbound_transport_duration }} </td>

					</tr>

					<tr>
						<td width='30%' class='label-view text-right'>Description</td>
						<td>{{ $row->description }} </td>

					</tr>

					<tr>
						<td width='30%' class='label-view text-right'>Destination</td>
						<td>{{ $row->destination }} </td>

					</tr>

					<tr>
						<td width='30%' class='label-view text-right'>Included Services</td>
						<td>{{ $row->included_services }} </td>

					</tr>

					<tr>
						<td width='30%' class='label-view text-right'>Not Included Services</td>
						<td>{{ $row->not_included_services }} </td>

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
