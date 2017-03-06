<script type="text/javascript" src="http://malsup.github.io/min/jquery.form.min.js"></script>
<script>
(function($) {
    var errorsDivToClear = [];
    $(document).ready(function() {
        function clearErrors(){
            $.each(errorsDivToClear,function($val,$field){
                $field.removeClass('has-error');
                //$field.tooltip("destroy");
            })
        }
        $('#contact-form').ajaxForm(function(response){
            clearErrors();
            console.log(response);
            if(response.status == "ERROR"){
                if(response.type == "E_VALIDATION"){
                    $.each(response.messages,function($name,$value){
                        $("[name='"+$name+"']").addClass('has-error');
                        /*$("[name='"+$name+"']").tooltip({   title: $value,
                                                            placement: 'top',
                                                            trigger: 'hover focus' });*/
                        $("[name='"+$name+"']").change(function(){
                            $field = $("[name='"+$name+"']");
                            $field.removeClass('has-error');
                            //$field.tooltip("destroy");
                        });
                        errorsDivToClear.push($("[name='"+$name+"']"));
                    });
                }   
            } else if (response.status == "SUCCESS"){
                $('#main').hide();
                $('#thankyou-div').show();
            }
        }); 
    })
})(jQuery);
</script>
<section class="packages hotel raul12345">
<div class="nicdark_container nicdark_vc nicdark_clearfix">
<div class="sixteen columns">
  <div class="ba-order container clearfix">
      <div class="ba-order-left two-thirds column ba-column-no-margin-left vc_col-sm-8">
      	<div id="main">
      		<form action="/cere_oferta/ref{{$askForOffer->id}}/valideaza" id="contact-form" role="form" class="ba-order-form" method="POST">
	        <div class="ba-order-payment-details">
	          <div class="ba-order-title-box">
	            Cere oferta 345
	          </div>
	          <div class="ba-order-payment-details-fields">
	              <input name="lname" type="text" value="" placeholder="Nume" />
	              <input name="fname" type="text" value="" placeholder="Prenume" />
	              <input name="phone" type="text" value="" placeholder="Telefon" />
	              <input name="email" type="text" value="" placeholder="Email" />
	              <textarea class="ba-additional-message" name="message" rows="6" placeholder="Mesaj aditional"></textarea>
	          </div>
	          <button class="ba-blue-button-m" id="contact-button" type="submit" title="">Trimite</button>
	        </div>
	        </form>
        </div>
        <div id="thankyou-div" style="display: none;">
	      	<div class="ba-order-title-box">
	            Multumim!
	        </div>
            <p class="ba-thankyou-description">Vei fi contactat de echipa noastra in cel mai scurt timp posibil.</p>
            <div class="ba-thankyou-button" style="">
                <a class="ba-blue-button-s" title="" href="{{ url()}}">« Inapoi la pagina principala</a>
            </div>
      	</div>
      </div>
      
      <div class="ba-order-right one-third column ba-column-no-margin-right vc_col-sm-4" style="margin-bottom: 40px;">
        <div class="ba-order-details">
          <div class="ba-order-title-box">
            Detalii Oferta
          </div>
          <div class="ba-order-details-content">
            <?php
            	if(count($askForOffer->hotel->images) != 0){
	                $image = $askForOffer->hotel->images[0]; 
	                $mimeArray = explode('/', $image->mime_type);
	                $type = $mimeArray[count($mimeArray)-1];
	                if($askForOffer->soap_client == "HO"){
	                    $imgUrl = "/images/offers/{$image->id}.{$type}";
	                } else {
	                    $imgUrl = "/images/offers/{$askForOffer->soap_client}/{$image->id}.{$type}";    
	                }
				} else {
					$imgUrl = "/images/210x140.jpg";
				}
            ?>
            <span class="ba-order-details-image"><img src="{{{$imgUrl}}}" /></span>
            <p class="ba-order-details-hotel-name">{{{$askForOffer->hotel->name}}}</p>
            <p class="ba-order-details-hotel-location simple_trip_row"><i class="fa fa-map-marker"></i> {{{$askForOffer->hotel->getFormatedLocationMin()}}}</p>
            <div class="ba-order-detail">
              <span class="ba-order-detail-name">Check In:</span>
              <span class="ba-order-detail-value">{{{$askForOffer->departure_date}}}</span>
            </div>
            <div class="ba-order-detail">
              <span class="ba-order-detail-name">Check Out:</span>
              <span class="ba-order-detail-value">{{{$askForOffer->return_date}}}</span>
            </div>
            <div class="ba-order-detail">
              <span class="ba-order-detail-name">Durata:</span>
              <span class="ba-order-detail-value">{{{$askForOffer->duration}}} {{{$askForOffer->duration == 1 ? "zi" : "zile"}}}</span>
            </div>
            @if($askForOffer->soap_client != "LOCAL")
            <div class="ba-order-detail">
              <span class="ba-order-detail-name">Camere:</span>
              <span class="ba-order-detail-value">{{{$noRooms}}} x {{{$askForOffer->room_category}}} pentru {{{$noAdults == 1 ? "1 Adult" : $noAdults. " Adulti"}}} {{{$noKids == 0 ? "" : ($noKids == 1 ? "si 1 Copil" : "si ".$noKids." Copii")}}}</span>
            </div>
            @endif
            <div class="ba-order-detail">
              <span class="ba-order-detail-name">Tipul {{{$noRooms == 1 ? "camerei" : "camerelor"}}}:</span>
              <span class="ba-order-detail-value">{{{$askForOffer->room_category}}}</span>
            </div>
            <div class="ba-order-detail">
              <span class="ba-order-detail-name">Tipul mesei:</span>
              <span class="ba-order-detail-value">{{{$askForOffer->meal_plan}}}</span>
            </div>
            <br/>
            <div class="ba-order-detail-price">
              <span class="ba-order-detail-price-name">Pret estimativ:</span>
              <span class="ba-order-detail-price-value">{{{$askForOffer->price}}}&euro;</span>
            </div>
          </div>
        </div>
      </div>
      <div class="clear"></div>
  </div>
</div>
</div>
</section>
