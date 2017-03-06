
<script type="text/javascript" src="http://malsup.github.io/min/jquery.form.min.js"></script>
<script>
(function($) {
    var errorsDivToClear = [];
    $(document).ready(function() {
        function clearErrors(){
            $.each(errorsDivToClear,function($val,$field){
                $field.removeClass('has-error');
            })
        }
        $('#contact-form').ajaxForm(function(response){

            clearErrors();

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
                        });
                        errorsDivToClear.push($("[name='"+$name+"']"));
                    });
                    $('#cerr').show();
                }
            } else if (response.status == "SUCCESS"){
                //$('#main').hide();
                //$('#thankyou-div').show();
                //$('#cerr').hide();
                window.location.replace(response.URL);
            }
        });
    })
})(jQuery);
</script>

<div id="main" class="hotel-pages has-js">
	<div class="inner">
		<div id="hotel" class="content">
			<h1 class="ask_offer_title">{{{$askForOffer->package->name}}}</h1>
			<div class="sep-20"></div>
			<div class="hotel-aside">
				<div class="ask_offer_img">
					<?php
        	if(count($askForOffer->package->hotel->images) != 0){
              $image = $askForOffer->package->hotel->images[0];
              $mimeArray = explode('/', $image->mime_type);
              $type = $mimeArray[count($mimeArray)-1];
              if($askForOffer->soap_client == "LOCAL"){
                $imgUrl = "/images/offers/{$askForOffer->soap_client}/{$image->name}";
              }else{
                if($askForOffer->soap_client == "HO"){
                    $imgUrl = "/images/offers/{$image->id}.{$type}";
                } else {
                    $imgUrl = "/images/offers/{$askForOffer->soap_client}/{$image->id}.{$type}";
                }
              }

					} else {
						$imgUrl = "/images/640x360.jpg";
					}
          ?>
          <img src="{{{$imgUrl}}}" />
          <?php
					  $url_back = htmlspecialchars($_SERVER['HTTP_REFERER']);
					?>
          <a href="<?php echo $url_back ?>" class="ask_offer_button back" title="">Inapoi la oferta</a>
				</div>
			</div>
			<div class="hotel-main">
				<div class="ask_offer_info">
					<div class="box-50 box-spacer-large box_ask_offer offer_form">
						<h2 class="ask_offer_subtitle">Cere oferta</h2>
            <form action="/cere_oferta/ref{{$askForOffer->id}}/valideaza" id="contact-form" role="form" class="ba-order-form" method="POST">
						<div class="ask_offer_form">
		            <input name="lname" type="text" value="" placeholder="Nume" />
		            <input name="fname" type="text" value="" placeholder="Prenume" />
		            <input name="phone" type="text" value="" placeholder="Telefon" />
		            <input name="email" type="text" value="" placeholder="Email" />
		            
		            <textarea class="ba-additional-message" name="message" rows="6" placeholder="Mesaj aditional"></textarea>
		        </div>
		        <center><button class="ask_offer_button" id="contact-button" title="">Trimite</button></center>
            <center><p id="cerr" style="color:red;display:none;">Completati toate campurile de mai sus.</p></center>
          </form>
            <div id="thankyou-div"  style="display:none;">
			      	<div class="ba-order-title-box" style="display:none;">
			            Multumim!
			        </div>
		            <p class="ba-thankyou-description">Vei fi contactat de echipa noastra in cel mai scurt timp posibil.</p>
		            <div class="ba-thankyou-button" style="">
		                <a class="ba-blue-button-s" title="" href="{{ url()}}">« Inapoi la pagina principala</a>
		            </div>
		      	</div>
					</div>

					<div class="box-50 box-spacer-large box_ask_offer">
						<h2 class="ask_offer_subtitle">Detalii Oferta</h2>
						<p class="hotel-pinpoint">{{{$askForOffer->package->hotel->getFormatedLocationMin()}}}</p>
						<div class="sep-10"></div>
						
						<p><strong>Check In: </strong>{{{date('d-m-Y', strtotime($askForOffer->departure_date))}}}</p>
						@if($askForOffer->package->day_night == 0)
						<p><strong>Check Out: </strong>{{{date('d-m-Y', strtotime($askForOffer->return_date))}}}</p>
						@endif
						<p><strong>Durata: </strong>{{{$askForOffer->duration}}} 
						@if($askForOffer->package->day_night == 1)
							zi
						@else
							{{{$askForOffer->duration == 1 ? "noapte" : "nopti"}}}
						@endif
						</p>
					  @if($askForOffer->soap_client != "LOCAL")
						<p><strong>Camere: </strong>{{{$noRooms}}} x {{{$askForOffer->room_category}}} pentru {{{$noAdults == 1 ? "1 Adult" : $noAdults. " Adulti"}}} {{{$noKids == 0 ? "" : ($noKids == 1 ? "si 1 Copil" : "si ".$noKids." Copii")}}}</p>
	          @endif
						<p><strong>Tipul {{{$noRooms == 1 ? "camerei" : "camerelor"}}}: </strong>{{{$askForOffer->room_category}}}</p>
						<p><strong>Tipul mesei: </strong>{{{$askForOffer->meal_plan}}}</p>
						<p><strong>Tip transport: </strong>
						<?php if($askForOffer->package->is_flight){ ?>
	        	Avion
	        	<?php } else if($askForOffer->package->is_bus){ ?>
	        	Autocar
	        	<?php } else { ?>
	        	Individual
	        	<?php } ?>
	        	</p>

            <?php
              $currency;
              if($askForOffer->soap_client == 'LOCAL'){
                $currency = \DB::table('cached_prices')->where('id_package','=',$askForOffer->id_package)->first()->currency;
              }else{
                $currency = 0;
              }
            ?>
	        	<p><strong>Pret: </strong>{{{$askForOffer->price}}}@if($currency == 0) &euro; @else LEI @endif</p>
					</div>
					<div class="clear"></div>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
</div>
