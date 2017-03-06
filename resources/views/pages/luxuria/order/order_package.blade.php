@extends('layouts.master')
@section('additional-head')
<script type="text/javascript" src="http://malsup.github.io/min/jquery.form.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    var errorsDivToClear = [];
    function clearErrors(){
        $.each(errorsDivToClear,function($val,$field){
            $field.removeClass('has-error');
            $field.tooltip("destroy");
        })
    }
    $('.ba-order-payment-option-radio').click(function(){
      var input = $(this).find('input');
      $('ba-order-payment-option-radio').attr('checked',false);
      input.attr('checked',true);
      $('.payment_method_div').hide();
      $(input.data('toggle')).show();
    });
    $('[name=options-ff]').change(function(){
      if($(this).prop('checked')){
        $('#ba-order-payment-ff').removeClass('ba-hidden');
        $('#ba-order-payment-ff-hr').removeClass('ba-hidden');
      } else {
        $('#ba-order-payment-ff').addClass('ba-hidden');
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
            if(response.type == {{{OrdersController::M_CASH}}}){
                window.location.href = response.URL;
            } else if(response.type == "ONLINE"){
                $("#mobilpay_env_key").val(response.mobilpay_env_key);
                $("#mobilpay_data").val(response.mobilpay_data);
                $("#mobilpay_form").submit();
            }
        }
    });
  });
</script>
@stop
@section('content')
<div class="sixteen columns">
  <div class="ba-order container clearfix">
      <div class="ba-order-left two-thirds column ba-column-no-margin-left">
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
        {{Form::open(array('route' => ['order.validate', $bookingPackageSearchId ], 'id' => 'create-order', 'role' => 'form', 'class' => 'ba-order-form'))}}
        <div class="ba-order-rooms">
          <div class="ba-order-title-box">
            Detalii Persoane
          </div>
          <div class="ba-order-rooms-persons">
            @foreach ($rooms as $i => $room)
              <span class="ba-order-room-title">Camera {{{$i + 1}}}</span>
            @endforeach
            @for ($j = 1; $j <= $room->Adults; $j++)
              <div class="ba-order-room-person">
                <span class="ba-order-room-person-title">Adult {{{ $j }}}:</span>
                <select name="c{{{$i+1}}}-a{{{$j}}}-gender" class="full-width">
                    <option value="0">Sex</option>
                    <option value="1">Dl</option>
                    <option value="2">Dna</option>
                </select>
                <input name="c{{{$i+1}}}-a{{{$j}}}-lname" type="text" class="input-text full-width" value="" placeholder="Nume" />
                <input name="c{{{$i+1}}}-a{{{$j}}}-fname" type="text" class="input-text full-width" value="" placeholder="Prenume" />
                <input name="c{{{$i+1}}}-a{{{$j}}}-birthdate" type="text" data-min-date="01/01/1920" data-max-date="today" class="ba-datepicker" placeholder="Data nasterii" id="c{{{$i+1}}}-a{{{$j}}}-birthdate">
                @if($i == 0 && $j == 1)
                    <input value="c{{{$i+1}}}-a{{{$j}}}" type="radio" name="contact-person" checked> Contact
                @else
                    <input value="c{{{$i+1}}}-a{{{$j}}}" type="radio" name="contact-person" > Contact
                @endif
              </div>
            @endfor
            @if ($room->ChildAges != 0)
                @foreach ($room->ChildAges as $j => $child)
                <div class="ba-order-room-person">
                    <span class="ba-order-room-person-title">Copil {{{ $j + 1 }}}:</span>
                    <select name="c{{{$i+1}}}-c{{{$j+1}}}-gender" class="full-width">
                        <option value="0">Sex</option>
                        <option value="1">B</option>
                        <option value="2">F</option>
                    </select>
                    <input name="c{{{$i+1}}}-c{{{$j+1}}}-lname" type="text" class="input-text full-width" value="" placeholder="Nume" />
                    <input name="c{{{$i+1}}}-c{{{$j+1}}}-fname" type="text" class="input-text full-width" value="" placeholder="Prenume" />
                    <input name="c{{{$i+1}}}-c{{{$j+1}}}-birthdate" type="text" data-min-date="01/01/1920" data-max-date="today" class="ba-datepicker" placeholder="Data nasterii" id="c{{{$i+1}}}-c{{{$j+1}}}-birthdate" />
                </div>
                @endforeach
            @endif
          </div>
        </div>
        <hr/>
        <div class="ba-order-payment-details">
          <div class="ba-order-title-box">
            Detalii Plata
          </div>
          <div class="ba-order-payment-options clearfix">
            <span class="ba-order-payment-option-radio"><input type="radio" id="cashMethod" data-toggle="#payment-method-{{{OrdersController::M_CASH}}}" name="payment-method" value="{{{OrdersController::M_CASH}}}" checked /><span class="ba-order-payment-option-title">Cash </span><span class="ba-order-payment-option-title-additional">(la sediu)</span></span>
            <span class="ba-order-payment-option-radio"><input type="radio" id="onlinePaypalMethod" data-toggle="#payment-method-{{{OrdersController::M_PAYPAL}}}" class="ba-order-payment-option-radio" name="payment-method" value="{{{OrdersController::M_PAYPAL}}}" /><span class="ba-order-payment-option-title">Online </span><span class="ba-order-payment-option-title-additional">(PayPal)</span></span>
            <span class="ba-order-payment-option-radio"><input type="radio" id="onlinePayuMethod" data-toggle="#payment-method-{{{OrdersController::M_PAYU}}}" class="ba-order-payment-option-radio" name="payment-method" value="{{{OrdersController::M_PAYU}}}" /><span class="ba-order-payment-option-title">Online </span><span class="ba-order-payment-option-title-additional">(PayU)</span></span>
          </div>
          <div class="ba-order-payment-details-fields">
              <input name="payment-lname" type="text" value="" placeholder="Nume" />
              <input name="payment-fname" type="text" value="" placeholder="Prenume" />
              <input name="payment-address" type="text" value="" placeholder="Adresa" />
              <input name="payment-city" type="text" value="" placeholder="Localitate" />
              <input name="payment-zone" type="text" value="" placeholder="Judet/Sector" />
              <input name="payment-country" type="text" value="" placeholder="Tara" />
              <input name="payment-email" type="text" value="" placeholder="Email" />
              <input name="payment-phone" type="text" value="" placeholder="Telefon" />
          </div>
        </div>
        <hr/>
        <div id="ba-order-payment-ff" class="ba-order-payment-ff ba-hidden">
          <div class="ba-order-title-box">
            Detalii Factura Fiscala
          </div>
          <div class="ba-order-payment-ff-fields">
            <input name="company-name" type="text" class="input-text full-width" value="" placeholder="Nume firma" />
            <input name="company-address" type="text" class="input-text full-width" value="" placeholder="Adresa" />
            <input name="company-city" type="text" class="input-text full-width" value="" placeholder="Oras" />
            <input name="company-zone" type="text" class="input-text full-width" value="" placeholder="Judet/Sector" />
            <input name="company-country" type="text" class="input-text full-width" value="" placeholder="Tara" />
            <input name="company-nrc" type="text" class="input-text full-width" value="" placeholder="Numar registrul comertului" />
            <input name="company-cui" type="text" class="input-text full-width" value="" placeholder="CUI" />
            <input name="company-bank-account" type="text" class="input-text full-width" value="" placeholder="Cont bancar" />
            <input name="company-bank" type="text" class="input-text full-width" value="" placeholder="Banca" />
          </div>
        </div>
        <hr id="ba-order-payment-ff-hr" class="ba-hidden" />
        <div class="ba-order-payment-additional">
            <span><input name="options-ff" value="1" type="checkbox"> Emite factura fiscala <strong>(optiune pentru companii)</strong></span><br/>
            <span><input name="options-tac" value="1" type="checkbox"> Am citit si sunt de acord cu <a href="#"><span class="skin-color">conditiile si termenii de comercializare a produselor</span></a>.</span><br/>
            <span><input name="options-newsletter" value="1" type="checkbox" checked>Vreau sa ma abonez la newsletter.</span>
        </div>
        <br/>
        <center><button class="ba-blue-button-m" id="order-button" title="">Confirma Rezervarea</button></center>
        <br/>
        {{Form::close()}}
      </div>
      <div class="ba-order-right one-third column ba-column-no-margin-right">
        <div class="ba-order-details">
          <div class="ba-order-title-box">
            Detalii Rezervare
          </div>
          <div class="ba-order-details-content">
            <?php
            if(count($bookingPackageSearch->package->hotel->images) != 0){
                $image = $bookingPackageSearch->package->hotel->images[0];
                $mimeArray = explode('/', $image->mime_type);
                $type = $mimeArray[count($mimeArray)-1];
                $imgUrl = "/images/offers/{$image->id}.{$type}";
            } else {
                $imgUrl = "/images/210x140.jpg";
            }
            ?>
            <span class="ba-order-details-image"><img src="{{{$imgUrl}}}" /></span>
            <span class="ba-order-details-hotel-name">{{{$bookingPackageSearch->package->name}}}</span>
            <span class="ba-order-details-hotel-location">{{{$bookingPackageSearch->package->hotel->getFormatedLocationMin()}}}</span>
            <br/>
            <div class="ba-order-detail">
              <span class="ba-order-detail-name">Check In:</span>
              <span class="ba-order-detail-value">{{{$bookingPackageSearch->check_in}}}</span>
            </div>
            <div class="ba-order-detail">
              <span class="ba-order-detail-name">Check Out:</span>
              <span class="ba-order-detail-value">{{{$bookingPackageSearch->check_out}}}</span>
            </div>
            <div class="ba-order-detail">
              <span class="ba-order-detail-name">Durata:</span>
              <span class="ba-order-detail-value">{{{$bookingPackageSearch->duration}}} {{{$bookingPackageSearch->duration == 1 ? "zi" : "zile"}}}</span>
            </div>
            <div class="ba-order-detail">
              <span class="ba-order-detail-name">Camere:</span>
              <span class="ba-order-detail-value">{{{$noRooms}}} x {{{$bookingPackageSearch->room_category}}} pentru {{{$noAdults == 1 ? "1 Adult" : $noAdults. " Adulti"}}} {{{$noKids == 0 ? "" : ($noKids == 1 ? "si 1 Copil" : "si ".$noKids." Copii")}}}</span>
            </div>
            <div class="ba-order-detail">
              <span class="ba-order-detail-name">Tipul {{{$noRooms == 1 ? "camerei" : "camerelor"}}}:</span>
              <span class="ba-order-detail-value">{{{$bookingPackageSearch->room_category}}}</span>
            </div>
            <div class="ba-order-detail">
              <span class="ba-order-detail-name">Tipul mesei:</span>
              <span class="ba-order-detail-value">{{{$bookingPackageSearch->meal_plan}}}</span>
            </div>
            <div class="ba-order-detail">
              <span class="ba-order-detail-name">Tip transport:</span>
              @if($bookingPackageSearch->package->is_flight == 1)
              <span class="ba-order-detail-value">Avion</span>
              @elseif($bookingPackageSearch->package->is_bus == 1)
              <span class="ba-order-detail-value">Autocar</span>
              @else
              <span class="ba-order-detail-value">Individual</span>
              @endif
            </div>
            <br/>
            <div class="ba-order-detail-price">
              <span class="ba-order-detail-price-name">Total:</span>
              <span class="ba-order-detail-price-value">{{{$bookingPackageSearch->price}}}&euro;</span>
            </div>
          </div>
        </div>
      </div>
  </div>
</div>
@stop
