<h1>Confirmare Plata</h1>
<ul>
  <li>{{$details['DESC']}}</li>
  <li>{{$details['AMOUNT']}} {{$details['CURRENCY']}}</li>
</ul>
<?php
if(strlen($details['ORDER']) < 18){
  $amountzero = 18 - strlen($details['ORDER']);
  for($i=0;$i<$amountzero;$i++){
    $details['ORDER']='0'.$details['ORDER'];
  }
}

$fields = array(//date de test pentru preautentificare
  'ORDER'=>$details['ORDER'],
	'AMOUNT'=>$details['AMOUNT'],
	'CURRENCY'=>$details['CURRENCY'],
	'RRN'=>$details['RRN'],
	'INT_REF'=>$details['INT_REF'],
	'TRTYPE'=>'21',
  'TERMINAL'=>$details['TERMINAL'],
	'TIMESTAMP'=>gmdate('YmdHis'),
	'NONCE'=>md5(time()),
	'BACKREF'=>'http://travel2.denku.ro/finalizare'
);

//http://travel2.denku.ro/finalizare
//var_dump(fields);
$fields_string='';
$cheie='A479A9DD6B21C0A015F5B88ED46A45D1';
//$key=pack('H*', $key);
$p_sign='';
foreach($fields as $key=>$value) {//construim sirul ce va fi criptat precum si variabilele ce vor fi trimise prin curl
	if ($value!='')$p_sign .= strlen($value).$value;
	else $p_sign.='-';
	$fields_string .= $key.'='.urlencode($value);
}

$hex_key = pack("H*", $cheie);
$p_sign = strtoupper(hash_hmac('sha1', $p_sign, $hex_key));


?>
<form class="" action="https://www.activare3dsecure.ro/teste3d/cgi-bin/" method="post">
  <input type="hidden" name="ORDER" value="{{$details['ORDER']}}">
  <input type="hidden" name="AMOUNT" value="{{$details['AMOUNT']}}">
  <input type="hidden" name="CURRENCY" value="{{$details['CURRENCY']}}">
  <input type="hidden" name="RRN" value="{{$details['RRN']}}">
  <input type="hidden" name="INT_REF" value="{{$details['INT_REF']}}">
  <input type="hidden" name="TRTYPE" value="21">
  <input type="hidden" name="TERMINAL" value="{{$details['TERMINAL']}}">
  <input type="hidden" name="TIMESTAMP" value="{{$fields['TIMESTAMP']}}">
  <input type="hidden" name="NONCE" value="{{$fields['NONCE']}}">
  <input type="hidden" name="BACKREF" value="http://travel2.denku.ro/finalizare">
  <input type="hidden" name="P_SIGN" value="{{$p_sign}}">

  <input type="submit"  value="Confirma">
</form>
