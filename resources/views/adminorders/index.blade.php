@extends('layouts.app')

@section('content')
{{--*/ usort($tableGrid, "SiteHelpers::_sort") /*--}}
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

<div class="sbox">
	<div class="sbox-title"> <h5> <i class="fa fa-table"></i> </h5>
<div class="sbox-tools" >
		<a href="{{ url($pageModule) }}" class="btn btn-xs btn-white tips" title="Clear Search" ><i class="fa fa-trash-o"></i> Clear Search </a>
		@if(Session::get('gid') ==1)
			<a href="{{ URL::to('sximo/module/config/'.$pageModule) }}" class="btn btn-xs btn-white tips" title=" {{ Lang::get('core.btn_config') }}" ><i class="fa fa-cog"></i></a>
		@endif
		</div>
	</div>
	<div class="sbox-content">
	    <div class="toolbar-line ">

			@if($access['is_remove'] ==1)
				@if(Auth::user()->group_id != 2 && Auth::user()->group_id != 2)
				<a href="javascript://ajax"  onclick="SximoDelete();" class="tips btn btn-sm btn-white" title="{{ Lang::get('core.btn_remove') }}">
				<i class="fa fa-minus-circle "></i>&nbsp;{{ Lang::get('core.btn_remove') }}</a>
				@endif
			@endif
			<a href="{{ URL::to( 'adminhotels/search') }}" class="btn btn-sm btn-white" onclick="SximoModal(this.href,'Advance Search'); return false;" ><i class="fa fa-search"></i> Search</a>


		</div>

	
	 {!! Form::open(array('url'=>'adminorders/delete/', 'class'=>'form-horizontal' ,'id' =>'SximoTable' )) !!}
	 <div class="table-responsive" style="min-height:300px;">
    <table class="table table-striped ">
        <thead>
			<tr>
				<th class="number"> No </th>
				<th> <input type="checkbox" class="checkall" /></th>

				@foreach ($tableGrid as $t)
					@if($t['view'] =='1')
						<?php $limited = isset($t['limited']) ? $t['limited'] :''; ?>
						@if(SiteHelpers::filterColumn($limited ))




              <th>{{ $t['label'] }}</th>
						@endif
					@endif
				@endforeach
				<th width="70" >{{ Lang::get('core.btn_action') }}</th>
			  </tr>
        </thead>
        <tbody>

            @foreach ($rowData as $row)
                <tr>
					<td width="30"> {{ ++$i }} </td>
					<td width="50"><input type="checkbox" class="ids" name="ids[]" value="{{ $row->id }}" />  </td>
				 @foreach ($tableGrid as $field)
					 @if($field['view'] =='1')
					 	<?php $limited = isset($field['limited']) ? $field['limited'] :''; ?>
					 	@if(SiteHelpers::filterColumn($limited ))
						 <td>

						 	@if($field['attribute']['image']['active'] =='1')
								{!! SiteHelpers::showUploadedFile($row->$field['field'],$field['attribute']['image']['path']) !!}
							@else
								{{--*/ $conn = (isset($field['conn']) ? $field['conn'] : array() ) /*--}}
                @if($field['field'] == 'rezervare' && $row->$field['field'] == 1)
                  {!! SiteHelpers::gridDisplay("Plata Electronica",$field['field'],$conn) !!}
                @elseif($field['field'] == 'rezervare' && $row->$field['field'] == 0)
                  {!! SiteHelpers::gridDisplay("Cash (la sediu)",$field['field'],$conn) !!}
                @elseif($field['field'] == 'rezervare' && $row->$field['field'] == 2)
                  {!! SiteHelpers::gridDisplay("Ordin de plata",$field['field'],$conn) !!}
                @elseif($field['field'] == 'achitat' && $row->$field['field'] == 0)
                  {!! SiteHelpers::gridDisplay("Nu",$field['field'],$conn) !!}
                @elseif($field['field'] == 'achitat' && $row->$field['field'] == 1)
                  {!! SiteHelpers::gridDisplay("Da",$field['field'],$conn) !!}
                @elseif($field['field'] == 'refuzat' && $row->$field['field'] == 0)
                  {!! SiteHelpers::gridDisplay("Nu",$field['field'],$conn) !!}
                @elseif($field['field'] == 'refuzat' && $row->$field['field'] == 1)
                  {!! SiteHelpers::gridDisplay("Da",$field['field'],$conn) !!}
                @elseif($field['field'] == 'error' && $row->$field['field'] == 1)
                  {!! SiteHelpers::gridDisplay("Da",$field['field'],$conn) !!}
                @elseif($field['field'] == 'error' && $row->$field['field'] == 0)
                  {!! SiteHelpers::gridDisplay("Nu",$field['field'],$conn) !!}
                @else
                  {!! SiteHelpers::gridDisplay($row->$field['field'],$field['field'],$conn) !!}

                @endif

							@endif
						 </td>
						@endif
					 @endif
				 @endforeach
				 <td>

           <a  href="{{ URL::to('adminorders/update/'.$row->id.'?return='.$return) }}" class="tips btn btn-xs btn-success" title="{{ Lang::get('core.btn_edit') }}"><i class="fa fa-search "></i></a>


				</td>
                </tr>

            @endforeach

        </tbody>

    </table>
	<input type="hidden" name="md" value="" />
	</div>
	{!! Form::close() !!}

	@include('footer')
	</div>
</div>
	</div>
</div>

<div class="col-md-4">
  <div class="panel panel-info">
    <div class="panel-heading">Curs Euro</div>
    <div class="panel-body">
      <form class="" action="adminorders/curseuro" method="post">
        <h5 style="color:red;">{{$errors->first('curseuro')}}</h5>
        <input type="text" class="form-control" name="curseuro" placeholder="Curs Euro" value="{{$curs}}">
        <input type="submit" class="btn btn-primary" value="Seteaza">
      </form>
    </div>
  </div>
</div>


@stop