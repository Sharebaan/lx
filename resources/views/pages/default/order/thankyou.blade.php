
<div class="sixteen columns">
	<div class="ba-thankyou container clearfix" style="width: 940px;margin-bottom: 200px;">
		<div class="ba-order-rooms"  style="font-size: 16px;margin-top: 20px;">
          <div class="ba-order-title-box" style="margin-bottom: 20px;">
            Multumim pentru rezervare
          </div>
          @if(isset($status))
			Ati primit pe mail toate detaliile rezervarii. Numarul rezervarii este {{{$booking->Reference}}}. Rezervarea este valabila pentru 24 de ore.
		  @else
			Ati primit pe mail toate detaliile rezervarii. Numarul rezervarii este {{{$booking->Reference}}}.
		  @endif
		  <br/><br/>
		  <center><a href="http://europatravel.ro" class="ba-blue-button-m" style="width: 200px;">Continua</a></center>
       	</div>
	</div>
</div>
