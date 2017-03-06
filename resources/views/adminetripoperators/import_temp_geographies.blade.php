@extends('layouts.app')

@section('content')

  <div class="page-content row">
    <!-- Page header -->
    <div class="page-header">
      <div class="page-title">
        <h3>Import Temporary Geopgraphies</h3>
      </div>
      <ul class="breadcrumb">
        <li><a href="{{ URL::to('dashboard') }}">Dashboard</a></li>
		<li><a href="{{ URL::to('adminetripoperators') }}">Operator Settings</a></li>
        <li class="active">Import Temporary Geographies</li>
      </ul>
	  	  
    </div>
 
 	<div class="page-content-wrapper">
 		<br/><br/>
 		<div class="col-md-4"></div>
 		
 		<div class="col-md-4">
		<div style="border:1px solid #ccc; width:380px; height:20px; overflow:auto; background:#eee;">
            <div id="progressor" style="background:#07c; width:0%; height:100%;"></div>
        </div><br/>
        <center><input type="button" onclick="start_task();" id="startImport" value="Start import" /><div id="messages"></div></center>
        </div>
        <div class="col-md-4"></div>
	</div>	
</div>

<script>
        var source = 'THE SOURCE';
         
        function start_task()
        {
        	$("#startImport").hide();
        	$("#messages").html("Temp geographies are being imported for {{$idOperator}} operator...");
            source = new EventSource('/adminetripoperators/importgeographiesaction/{{$idOperator}}');
             
            //a message is received
            source.addEventListener('message' , function(e) 
            {
                var result = JSON.parse( e.data );
                 
                
                 
                document.getElementById('progressor').style.width = result.progress + "%";
                 
                if(e.data.search('TERMINATE') != -1)
                {
                	$("#messages").html("Temporary geographies succesfully imported!");
                    source.close();
                }
            });
             
            source.addEventListener('error' , function(e)
            {
                
                //kill the object ?
                source.close();
            });
        }
         
        function add_log(message)
        {
            var r = document.getElementById('results');
            r.innerHTML += message + '<br>';
            r.scrollTop = r.scrollHeight;
        }
</script>			 
@stop