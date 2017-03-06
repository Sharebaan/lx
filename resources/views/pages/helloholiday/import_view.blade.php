<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
         
        <script>
        var source = 'THE SOURCE';
         
        function start_task()
        {
            source = new EventSource('/import/etrip');
             
            //a message is received
            source.addEventListener('message' , function(e) 
            {
                var result = JSON.parse( e.data );
                 
                
                 
                document.getElementById('progressor').style.width = result.progress + "%";
                 
                if(e.data.search('TERMINATE') != -1)
                {
                    source.close();
                } else {
                	add_log(result.message);
                }
            });
             
            source.addEventListener('error' , function(e)
            {
                add_log('Error occured');
                 
                //kill the object ?
                source.close();
            });
        }
         
        function stop_task()
        {
            source.close();
            add_log('Interrupted');
        }
         
        function add_log(message)
        {
            var r = document.getElementById('results');
            r.innerHTML += message + '<br>';
            r.scrollTop = r.scrollHeight;
        }
        </script>
    </head>
    <body>
    	Etrip import<br/><br/>
        <div style="border:1px solid #ccc; width:300px; height:20px; overflow:auto; background:#eee;">
            <div id="progressor" style="background:#07c; width:0%; height:100%;"></div>
        </div><br/>
        <div id="results" style="border:1px solid #000; padding:10px; width:280px; height:200px; overflow:auto; background:#eee;"></div>
    	<br/>
        <input type="button" onclick="start_task();"  value="Start import" />
        <input type="button" onclick="stop_task();"  value="Stop import" />
    </body>
</html> 