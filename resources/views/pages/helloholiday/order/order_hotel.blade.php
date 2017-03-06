

<style media="screen">
  .has-error{
    border-bottom: 1px solid #EC0035;
  }
</style>
<script type="text/javascript" src="http://malsup.github.io/min/jquery.form.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){

    var errorsDivToClear = [];
    function clearErrors(){
        $.each(errorsDivToClear,function($val,$field){
            $field.removeClass('has-error');
            $field.tooltip("destroy");
        })
    }
    var form=null;
    $('#payment-type1').click(function(){
        form = $('#puform');
        $('#suma').val(form.children('.amount1').val());
    });
    $('#payment-type2').click(function(){
        form = $('#puform2');
        $('#suma').val(form.children('.amount2').val());
    });
    $('#cashMethod').click(function(){
      $('#paytype').val('Offer');
      $('.currency').hide();
    });
    $('#onlinePayuMethod').click(function(){
      $('#paytype').val('Romcard');
      $('.currency').show();
    });
    $('#ordinPlata').click(function(){
      $('#paytype').val('OP');
      $('.currency').hide();
    });

    $('#optff').change(function(){
        if($(this).prop('checked')){
          $('#ba-order-payment-ff').removeClass('ba-hidden');
          $('#ba-order-payment-ff').show();
          $('#ba-order-payment-ff-hr').removeClass('ba-hidden');
        } else {
          $('#ba-order-payment-ff').addClass('ba-hidden');
          $('#ba-order-payment-ff').hide();
          $('#ba-order-payment-ff-hr').addClass('ba-hidden');
        }
      });
    $('#create-order').ajaxForm(function(response){
        clearErrors();
        if(response.status == "ERROR"){
            if(response.type == "E_VALIDATION"){
                $.each(response.messages,function($name,$value){

                  if($name.indexOf("options-") > -1){
                      $("[name='"+$name+"']").parent().addClass('has-error');
                      $("[name='"+$name+"']").parent().tooltip({
                                                                      title: $value,
                                                                      placement: 'top',
                                                                      trigger: 'hover focus'
                                                                  });
                      $("[name='"+$name+"']").change(function(){
                          $field = $("[name='"+$name+"']").parent();
                          $field.removeClass('has-error');
                          $field.tooltip("destroy");
                      });
                      errorsDivToClear.push($("[name='"+$name+"']").parent());
                  } else {
                    $("[name='"+$name+"']").addClass('has-error');
                    $("[name='"+$name+"']").tooltip({
                                                title: $value,
                                                placement: 'top',
                                                trigger: 'hover focus'
                                            });
                    $("[name='"+$name+"']").change(function(){
                        $field = $("[name='"+$name+"']");
                        $field.removeClass('has-error');
                        $field.tooltip("destroy");
                    });
                    errorsDivToClear.push($("[name='"+$name+"']"));
                  }
                });
            } else if (response.type ==  "E_SOAP"){
                window.location.href = response.URL;
            } else if (response.type == "E_EXPIRED"){
                window.location.href = response.URL;
            }
        } else if (response.status == "SUCCESS"){

            if($('#paytype').val() == 'Romcard'){
              var link = '/savetosession/';
              $('#create-order :input').each(function(i,v){
                if(i==0){
                  link +='?'+v.name+'='+v.value;
                }else{
                  link +='&'+v.name+'='+v.value;
                }
              });

              $.get(link+'&packageid='+<?php echo $bookingHotelSearch->id_hotel; ?>+'&paytype='+$('#paytype').val()).done(function(data){
                if(data.savetosession == 'done'){
                  form.submit();
                }
              });
            }else{
              window.location.replace(response.URL);
            }

        }
    });

});
/*  ;*/
</script>

<div id="main" class="hotel-pages has-js">
	<div class="inner">
		<div id="hotel" class="content">
			<h1 class="ask_offer_title">{{{$bookingHotelSearch->hotel->name}}}</h1>
			<div class="sep-20"></div>
			<div class="hotel-aside">
				<div class="ask_offer_img">

					<?php
          $images = $bookingHotelSearch->hotel->images;
          if(count($images) != 0){
            $mimeArray = explode('/', $images[0]->mime_type);
            $type = $mimeArray[count($mimeArray)-1];
            if($images[0]->soap_client == "HO"){
                if(file_exists(public_path()."/images/offers/{$images[0]->name}")){
                  $imgUrl = "/images/offers/{$images[0]->name}";
                }else{
                  $imgUrl = "/images/offers/{$images[0]->id}.{$type}";
                }
            } else {
                if(file_exists(public_path()."/images/offers/{$images[0]->soap_client}/{$images[0]->name}")){
                  $imgUrl = "/images/offers/{$images[0]->soap_client}/{$images[0]->name}";
                }else{
                  $imgUrl = "/images/offers/{$images[0]->soap_client}/{$images[0]->id}.{$type}";
                }
            }
            } else {
                $imgUrl = "/images/210x140.jpg";
            }
          ?>
          <img src="{{{$imgUrl}}}" />
          <?php
					  $url_back = htmlspecialchars($_SERVER['HTTP_REFERER']);
					?>
					<div class="sep-20"></div>
          <div class="ask_offer_info">
          	<div class="box_ask_offer">
	          	<h2 class="ask_offer_subtitle">Detalii Rezervare</h2>
							<div class="sep-10"></div>
							<p><strong>Check In: </strong>{{{$bookingHotelSearch->check_in}}}</p>
							<p><strong>Check Out: </strong>{{{$bookingHotelSearch->check_out}}}</p>
							<p><strong>Durata: </strong>{{{$bookingHotelSearch->duration}}} {{{$bookingHotelSearch->duration == 1 ? "zi" : "zile"}}}</p>
							<p><strong>Camere: </strong>{{{$noRooms}}} x {{{$bookingHotelSearch->room_category}}} pentru {{{$noAdults == 1 ? "1 Adult" : $noAdults. " Adulti"}}} {{{$noKids == 0 ? "" : ($noKids == 1 ? "si 1 Copil" : "si ".$noKids." Copii")}}}</p>
							<p><strong>Tipul {{{$noRooms == 1 ? "camerei" : "camerelor"}}}: </strong>{{{$bookingHotelSearch->room_category}}}</p>
							<p><strong>Tipul mesei: </strong>{{{$bookingHotelSearch->meal_plan}}}</p>

		        	<p><strong>Total: </strong>{{{$bookingHotelSearch->price}}}&euro;</p>
	        	</div>
        	</div>
        	<div class="sep-20"></div>
        	<a href="<?php echo $url_back ?>" class="ask_offer_button back" title="">Inapoi la oferta</a>
				</div>
			</div>
      <form action="/rezerva/hotel/ref{{$bookingHotelSearchId}}/valideaza" id="create-order" class="ba-order-form" method="post">
			<div class="hotel-main">
				<div class="ask_offer_info">
	        <h2 class="ask_offer_subtitle">Detalii Persoane</h2>
	        <div class="ba-order-rooms-persons">
	          @foreach ($rooms as $i => $room)
	            <h3 class="ba-order-room-title">Camera {{{$i + 1}}}</h3>
	          @endforeach
	          @for ($j = 1; $j <= $room->Adults; $j++)
	              <p class="ba-order-room-person-title" style="display:none;">Adult {{{ $j }}}:

	              	@if($i == 0 && $j == 1)
		                  <input value="c{{{$i+1}}}-a{{{$j}}}" type="radio" name="contact-person" checked> Contact
		              @else
		                  <input value="c{{{$i+1}}}-a{{{$j}}}" type="radio" name="contact-person" > Contact
		              @endif

              	</p>
              	<div class="search_section">
	              <div class="selector">
		              <select name="c{{{$i+1}}}-a{{{$j}}}-gender" class="full-width">
		                  <option value="0">Sex</option>
		                  <option value="1">Dl</option>
		                  <option value="2">Dna</option>
		              </select>
	              </div>
	              </div>
	              <div class="search_section">
	              <input name="c{{{$i+1}}}-a{{{$j}}}-lname" type="text" class="input-text full-width" value="" placeholder="Nume" />
	              </div>
	              <div class="search_section">
	              <input name="c{{{$i+1}}}-a{{{$j}}}-fname" type="text" class="input-text full-width" value="" placeholder="Prenume" />
	              </div>
	              <div class="search_section">
	              <input name="c{{{$i+1}}}-a{{{$j}}}-birthdate" type="text" class="datepicker_b" placeholder="Data nasterii" id="c{{{$i+1}}}-a{{{$j}}}-birthdate">
	              </div>
	              <div class="sep-20"></div>
	          @endfor
	          @if ($room->ChildAges != 0)
	              @foreach ($room->ChildAges as $j => $child)
	                  <p class="ba-order-room-person-title" style="display:none;">Copil {{{ $j + 1 }}}:</p>
	                  <div class="search_section">
	              		<div class="selector">
	                  <select name="c{{{$i+1}}}-c{{{$j+1}}}-gender" class="full-width">
	                      <option value="0">Sex</option>
	                      <option value="1">B</option>
	                      <option value="2">F</option>
	                  </select>
	                  </div>
			              </div>
			              <div class="search_section">
	                  <input name="c{{{$i+1}}}-c{{{$j+1}}}-lname" type="text" class="input-text full-width" value="" placeholder="Nume" />
	                  </div>
			              <div class="search_section">
	                  <input name="c{{{$i+1}}}-c{{{$j+1}}}-fname" type="text" class="input-text full-width" value="" placeholder="Prenume" />
	                  </div>
			              <div class="search_section">
	                  <input name="c{{{$i+1}}}-c{{{$j+1}}}-birthdate" type="text" class="datepicker_b" placeholder="Data nasterii" id="c{{{$i+1}}}-c{{{$j+1}}}-birthdate" />
	                  </div>
	                  <div class="sep-20"></div>
	              @endforeach
	          @endif
	          <div class="clear"></div>
	        </div>
	      </div>
	      <div class="sep-20"></div>

	      <div class="ask_offer_info">
	      	<!-- COUNTDOWN
	        <div class="ba-order-payment-countdown">
	          <span class="ba-order-payment-countdown-title">Plata</span>
	          <span class="ba-order-payment-countdown-image"><i class="fa fa-clock-o"></i></span>
	          <span class="ba-order-payment-countdown-time">15:00</span>
	          <div class="ba-order-payment-countdown-additional">
	            <span class="ba-order-payment-countdown-additional-title">Oferta este valabila pentru 15 minute</span>
	            <span class="ba-order-payment-countdown-additional-description">Pentru confirmarea rezervarii completati formularul</span>
	          </div>
	        </div>
	       	-->
		        <div class="ba-order-payment-details">
		          <h2 class="ask_offer_subtitle">Detalii Factura</h2>
		          <div class="box_pay_inside">
			          <!-- <div class="ba-order-payment-options clearfix">
			            <span class="ba-order-payment-option-radio"><input type="radio" id="cashMethod" data-toggle="#payment-method-{{{App\Http\Controllers\Travel\OrdersController::M_CASH}}}" name="payment-method" value="{{{App\Http\Controllers\Travel\OrdersController::M_CASH}}}" checked /><span class="ba-order-payment-option-title">Cash </span><span class="ba-order-payment-option-title-additional">(la sediu)</span></span>

			            <span class="ba-order-payment-option-radio"><input type="radio" id="onlinePayuMethod"  class="ba-order-payment-option-radio" name="payment-method" /><span class="ba-order-payment-option-title">Online </span><span class="ba-order-payment-option-title-additional">(PayU)</span></span>
			          </div> -->
			          <div class="sep-10"></div>
			          <div class="ba-order-payment-details-fields">
			          	<div class="box-50 box-spacer-large">
			              <input name="payment-lname" type="text" value="" placeholder="Nume" />
		              </div>
		              <div class="box-50 box-spacer-large">
			              <input name="payment-fname" type="text" value="" placeholder="Prenume" />
		              </div>
		              <div class="box-50 box-spacer-large">
			              <input name="payment-address" type="text" value="" placeholder="Adresa" />
		              </div>
		              <div class="box-50 box-spacer-large">
			              <input name="payment-city" type="text" value="" placeholder="Localitate" />
		              </div>
		              <div class="box-50 box-spacer-large">
			              <input name="payment-zone" type="text" value="" placeholder="Judet/Sector" />
		              </div>
		              <div class="box-50 box-spacer-large">
			              <input name="payment-country" type="text" value="" placeholder="Tara" />
		              </div>
		              <div class="box-50 box-spacer-large">
			              <input name="payment-email" type="text" value="" placeholder="Email" />
		              </div>
		              <div class="box-50 box-spacer-large">
			              <input name="payment-phone" type="text" value="" placeholder="Telefon" />
		              </div>
		              <div class="clear"></div>
			          </div>
		          </div>
		        </div>
		        <div id="ba-order-payment-ff" class="ba-order-payment-ff " hidden>
		        	<div class="sep-20"></div>
		          <h3 class="ba-order-room-title">Date facturare pentru companii</h3>
		          <div class="box_pay_inside">
			          <div class="ba-order-payment-ff-fields">
		              <div class="box-50 box-spacer-large">
			            <input name="company-name" type="text" class="input-text full-width" value="" placeholder="Nume firma" />
			            </div>
		              <div class="box-50 box-spacer-large">
			            <input name="company-address" type="text" class="input-text full-width" value="" placeholder="Adresa" />
			            </div>
		              <div class="box-50 box-spacer-large">
			            <input name="company-city" type="text" class="input-text full-width" value="" placeholder="Oras" />
			            </div>
		              <div class="box-50 box-spacer-large">
			            <input name="company-zone" type="text" class="input-text full-width" value="" placeholder="Judet/Sector" />
			            </div>
		              <div class="box-50 box-spacer-large">
			            <input name="company-country" type="text" class="input-text full-width" value="" placeholder="Tara" />
			            </div>
		              <div class="box-50 box-spacer-large">
			            <input name="company-nrc" type="text" class="input-text full-width" value="" placeholder="Numar registrul comertului" />
			            </div>
		              <div class="box-50 box-spacer-large">
			            <input name="company-cui" type="text" class="input-text full-width" value="" placeholder="CUI" />
			            </div>
		              <div class="box-50 box-spacer-large">
			            <input name="company-bank-account" type="text" class="input-text full-width" value="" placeholder="Cont bancar" />
			            </div>
		              <div class="box-50 box-spacer-large">
			            <input name="company-bank" type="text" class="input-text full-width" value="" placeholder="Banca" />
			            </div>
			          </div>
		          </div>
		        </div>
		        <div class="clear sep-10"></div>
		        <div class="ba-order-payment-additional">
		            <span><input name="options-ff" id="optff" value="1" type="checkbox"> Emite factura fiscala <strong>(optiune pentru companii)</strong></span><br/>
		            <!-- <span><input name="options-tac" value="1" type="checkbox"> Am citit si sunt de acord cu <a href="#"><span class="skin-color">conditiile si termenii de comercializare a produselor</span></a>.</span><br/>
		            <span><input name="options-newsletter" value="1" type="checkbox" checked>Vreau sa ma abonez la newsletter.</span> -->
		        </div>
		        <input type="hidden" name="soap_client" value="{{$bookingHotelSearch->hotel->soap_client}}">
            <input type="hidden" name="packageid" value="{{$bookingHotelSearch->id_hotel}}">
		        <!-- <br/>
		        <center><button class="ask_offer_button" id="order-button" type="submit">Confirma Rezervarea</button></center>
		        <br/> -->
		      <!-- </form> -->




      	</div>
      	<div class="sep-20"></div>
      	<div class="ask_offer_info">
		        <div class="ba-order-payment-details">
		          <h2 class="ask_offer_subtitle">Detalii Plata</h2>
		          <div class="box_pay_inside">
			          <div class="ba-order-payment-options clearfix">
			            <span class="ba-order-payment-option-radio"><input type="radio" id="cashMethod" data-toggle="#payment-method-{{{App\Http\Controllers\Travel\OrdersController::M_CASH}}}" name="payment-method" value="{{{App\Http\Controllers\Travel\OrdersController::M_CASH}}}" checked /><span class="ba-order-payment-option-title">Cash </span><span class="ba-order-payment-option-title-additional">(la sediu)</span></span>
			            <span class="ba-order-payment-option-radio"><input type="radio" id="onlinePayuMethod"  class="ba-order-payment-option-radio" name="payment-method" /><span class="ba-order-payment-option-title">Plata online</span></span>
			            <span class="ba-order-payment-option-radio"><input type="radio" id="ordinPlata"  class="ba-order-payment-option-radio" name="payment-method" /><span class="ba-order-payment-option-title">Ordin de plata</span></span>
			          </div>
                <div class="currency" style="display:none;">
                	<h2 class="ask_offer_subtitle">Detalii Plata Online</h2>
                  <p>Pentru o rezervare ferma, achita un avans de 30% din contravaloarea vacantei tale.</p>
                  <p>In ce moneda preferi sa aiba loc tranzactionarea ?</p>
                  <p class="input_row"><input type="radio" name="payment-type" id="payment-type1" value="EUR"> <strong>EUR.</strong>  Platesti {{$bookingHotelSearch->price * 0.3}} EUR</p>
                  <p class="input_row"><input type="radio" name="payment-type" id="payment-type2" value="RON"> <strong>RON.</strong>  Platesti {{number_format($bookingHotelSearch->price * \DB::table('curseuro')->where('id','=',1)->first()->curseuro * 0.3,2)}} RON</p>
                </div>
			          <div class="sep-10"></div>
		          </div>
		        </div>
		        <div class="clear"></div>
		        <div class="ba-order-payment-additional">
		            <span><input name="options-tac" value="1" type="checkbox">Am citit si sunt de acord cu <a href="/page/termeni-si-conditii" target="_blank"><u>conditiile si termenii de comercializare a produselor</u></a>.</span><br/>
		            <span><input name="options-confirmare" value="1" type="checkbox">Confirm datele introduse, atat pe cele personale, cat si pe cele de calatorie.</span><br/>
		            <span><input name="options-newsletter" value="1" type="checkbox" checked>Vreau sa ma abonez la newsletter.</span>
		        </div>
            <input type="hidden" name="paytype" id="paytype" value="Offer">
            <input type="hidden" name="suma" id="suma" value="{{$bookingHotelSearch->price}}">
            <input type="hidden" name="hotel" value="1">
		        <input type="hidden" name="soap_client" value="{{$bookingHotelSearch->hotel->soap_client}}">
		        <br/>

		        <center><button class="ask_offer_button" id="order-button" type="submit">Confirma Rezervarea</button></center>
		        <br/>




      	</div>
      </div>
      </form>
      <?php
      $url = 'https://www.activare3dsecure.ro/teste3d/cgi-bin/';
      $fields = array(
        'AMOUNT'=>number_format($bookingHotelSearch->price *0.3,2),
        'CURRENCY'=>'EUR',
        'ORDER'=>$bookingHotelSearch->id,
        'DESC'=>substr($bookingHotelSearch->hotel->name,0,50),
        'MERCH_NAME'=>'HELLO HOLIDAYS SRL',
        'MERCH_URL'=>'http://hello.infora.ro',
        'MERCHANT'=>'000000060000780',
        'TERMINAL'=>'60000780',
        'EMAIL'=>'dutu_ionutcatalin@yahoo.com',
        'TRTYPE'=>'0',
        'COUNTRY'=>'',
        'MERCH_GMT'=>'',
        'TIMESTAMP'=>gmdate('YmdHis'),
        'NONCE'=>md5(time()),
        'BACKREF'=>'http://hello.infora.ro/inregistrare_plata_client'
      );
      $fields2 = array(
        'AMOUNT'=>number_format($bookingHotelSearch->price * \DB::table('curseuro')->where('id','=',1)->first()->curseuro*0.3,2),
        'CURRENCY'=>'RON',
        'ORDER'=>$bookingHotelSearch->id,
        'DESC'=>substr($bookingHotelSearch->hotel->name,0,50),
        'MERCH_NAME'=>'HELLO HOLIDAYS SRL',
        'MERCH_URL'=>'http://hello.infora.ro',
        'MERCHANT'=>'000000060000780',
        'TERMINAL'=>'60000780',
        'EMAIL'=>'dutu_ionutcatalin@yahoo.com',
        'TRTYPE'=>'0',
        'COUNTRY'=>'',
        'MERCH_GMT'=>'',
        'TIMESTAMP'=>gmdate('YmdHis'),
        'NONCE'=>md5(time()),
        'BACKREF'=>'http://hello.infora.ro/inregistrare_plata_client'
      );



      //$fields_string='';
      $cheie='A479A9DD6B21C0A015F5B88ED46A45D1';
      $p_sign='';
      $p_sign2='';
      foreach($fields as $key=>$value) {
        if ($value!='')$p_sign .= strlen($value).$value;
        else $p_sign.='-';
        //$fields_string .= $key.'='.urlencode($value);
      }
      foreach($fields2 as $key=>$value) {
        if ($value!='')$p_sign2 .= strlen($value).$value;
        else $p_sign2.='-';
        //$fields_string .= $key.'='.urlencode($value);
      }

      $hex_key = pack("H*", $cheie);
      $p_sign = strtoupper(hash_hmac('sha1', $p_sign, $hex_key));
      $p_sign2 = strtoupper(hash_hmac('sha1', $p_sign2, $hex_key));

  ?>
      <form name="allData" action="https://www.activare3dsecure.ro/teste3d/cgi-bin/" id="puform" method="POST">

        <input maxlength="250" size="80" type="hidden" name="submit_to" value="{{$url}}" />
        <input maxlength="12" size="12" type="hidden" name="AMOUNT" class="amount1" value="{{$fields['AMOUNT']}}" />
        <input maxlength="3" size="3" type="hidden" name="CURRENCY" value="{{$fields['CURRENCY']}}" />
        <input maxlength="32" size="32" type="hidden" name="ORDER" value="{{$fields['ORDER']}}" />
        <input maxlength="50" size="50" type="hidden" name="DESC" value="{{$fields['DESC']}}" />
        <input maxlength="50" size="50" type="hidden" name="MERCH_NAME" value="{{$fields['MERCH_NAME']}}" />
        <input maxlength="250" size="80" type="hidden" name="MERCH_URL" value="{{$fields['MERCH_URL']}}" />
        <input maxlength="15" size="15" type="hidden" name="MERCHANT" value="{{$fields['MERCHANT']}}" />
        <input maxlength="8" size="8" type="hidden" name="TERMINAL" value="{{$fields['TERMINAL']}}" />
        <input maxlength="80" size="80" type="hidden" name="EMAIL" value="{{$fields['EMAIL']}}" />
        <input maxlength="2" size="2" type="hidden" name="TRTYPE" value="{{$fields['TRTYPE']}}" />
        <input maxlength="2" size="2" type="hidden" name="COUNTRY" value="{{$fields['COUNTRY']}}" />
        <input maxlength="5" size="5" type="hidden" name="MERCH_GMT" value="{{$fields['MERCH_GMT']}}" />
        <input maxlength="14" size="14" type="hidden" name="TIMESTAMP" value="{{$fields['TIMESTAMP']}}" />
        <input maxlength="64" size="64" type="hidden" name="NONCE" value="{{$fields['NONCE']}}" />
        <input maxlength="250" size="80" type="hidden" name="BACKREF" value="{{$fields['BACKREF']}}" />
        <input maxlength="256" size="80" type="hidden" name="P_SIGN" value="{{$p_sign}}" />
        <input type="hidden" name="LANG" value="en" />


    </form>
    <form name="allData" action="https://www.activare3dsecure.ro/teste3d/cgi-bin/" id="puform2" method="POST">

      <input maxlength="250" size="80" type="hidden" name="submit_to" value="{{$url}}" />
      <input maxlength="12" size="12" type="hidden"  name="AMOUNT" class="amount2" value="{{$fields2['AMOUNT']}}" />
      <input maxlength="3" size="3" type="hidden"  name="CURRENCY" value="{{$fields2['CURRENCY']}}" />
      <input maxlength="32" size="32" type="hidden" name="ORDER" value="{{$fields2['ORDER']}}" />
      <input maxlength="50" size="50" type="hidden" name="DESC" value="{{$fields2['DESC']}}" />
      <input maxlength="50" size="50" type="hidden" name="MERCH_NAME" value="{{$fields2['MERCH_NAME']}}" />
      <input maxlength="250" size="80" type="hidden" name="MERCH_URL" value="{{$fields2['MERCH_URL']}}" />
      <input maxlength="15" size="15" type="hidden" name="MERCHANT" value="{{$fields2['MERCHANT']}}" />
      <input maxlength="8" size="8" type="hidden" name="TERMINAL" value="{{$fields2['TERMINAL']}}" />
      <input maxlength="80" size="80" type="hidden" name="EMAIL" value="{{$fields2['EMAIL']}}" />
      <input maxlength="2" size="2" type="hidden" name="TRTYPE" value="{{$fields2['TRTYPE']}}" />
      <input maxlength="2" size="2" type="hidden" name="COUNTRY" value="{{$fields2['COUNTRY']}}" />
      <input maxlength="5" size="5" type="hidden" name="MERCH_GMT" value="{{$fields2['MERCH_GMT']}}" />
      <input maxlength="14" size="14" type="hidden" name="TIMESTAMP" value="{{$fields2['TIMESTAMP']}}" />
      <input maxlength="64" size="64" type="hidden" name="NONCE" value="{{$fields2['NONCE']}}" />
      <input maxlength="250" size="80" type="hidden" name="BACKREF" value="{{$fields2['BACKREF']}}" />
      <input maxlength="256" size="80" type="hidden" name="P_SIGN" value="{{$p_sign2}}" />
      <input type="hidden" name="LANG" value="en" />


  </form>
		<div class="clear"></div>
		</div>
	</div>
</div>
