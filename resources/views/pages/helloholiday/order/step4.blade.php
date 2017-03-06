
<?php
if(strlen($details['ORDER']) < 18){
  $amountzero = 18 - strlen($details['ORDER']);
  for($i=0;$i<$amountzero;$i++){
    $details['ORDER']='0'.$details['ORDER'];
  }
}

$fields = array(//date de test pentru preautentificare
  //'ACTION'=>'0',
  //'RC'=>'00',
  //'MESSAGE'=>'Sales completion',
  'ORDER'=>$details['ORDER'],
	'AMOUNT'=>$details['AMOUNT'],
	'CURRENCY'=>$details['CURRENCY'],
	'RRN'=>$details['RRN'],
	'INT_REF'=>$details['INT_REF'],
	'TRTYPE'=>'21',
  'TERMINAL'=>'60001099',
	'TIMESTAMP'=>gmdate('YmdHis'),
	'NONCE'=>md5(time()),
  'BACKREF'=>'http://travel2.denku.ro/'
);

//http://travel2.denku.ro/finalizare
//var_dump(fields);
$fields_string='';
$cheie='3F6F4235AB32D147BC5F67FE1BAF3E33';
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
<form class="" action="https://www.activare3dsecure.ro/teste3d/cgi-bin/" method="post" style="margin-top:120px;">
  <?php /*<input type="hidden" name="ACTION" value="ACTION">
  <input type="hidden" name="RC" value="00">
  <input type="hidden" name="MESSAGE" value="Sales completion"> */ ?>
  <input type="hidden" name="ORDER" value="{{$details['ORDER']}}">
  <input type="hidden" name="AMOUNT" value="{{$details['AMOUNT']}}">
  <input type="hidden" name="CURRENCY" value="{{$details['CURRENCY']}}">
  <input type="hidden" name="RRN" value="{{$details['RRN']}}">
  <input type="hidden" name="INT_REF" value="{{$details['INT_REF']}}">
  <input type="hidden" name="TRTYPE" value="21">
  <input type="hidden" name="TERMINAL" value="{{$fields['TERMINAL']}}">
  <input type="hidden" name="TIMESTAMP" value="{{$fields['TIMESTAMP']}}">
  <input type="hidden" name="NONCE" value="{{$fields['NONCE']}}">
  <input type="hidden" name="BACKREF" value="http://travel2.denku.ro/">
  <input type="hidden" name="P_SIGN" value="{{$p_sign}}">

  <input type="submit"  value="Confirma">
</form>
