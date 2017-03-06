@extends('layouts.master')
@section('slideshow')
@stop
@section('content')
<script src="/js/jquery.form.js"></script>
<script>
(function($) {
    var errorsDivToClear = [];
    $(document).ready(function() {
        function clearErrors(){
            $.each(errorsDivToClear,function($val,$field){
                $field.removeClass('has-error');
                $field.tooltip("destroy");
            })
        }
        $('#contact-button').click(function(){
        	$("#loadingPage").parent().parent().removeClass('pace-inactive');
            $("#loadingBar").hide();
            $("#loadingPage").parent().parent().addClass('pace-active');
            $("#loadingText").html("Se trimite mesajul...");
        });
        $('#contact-form').ajaxForm(function(response){
        	$("#loadingPage").parent().parent().addClass('pace-inactive');
            $("#loadingPage").parent().parent().removeClass('pace-active');
        	clearErrors();
            if(response.status == "ERROR"){
                if(response.type == "E_VALIDATION"){
                	$.each(response.messages,function($name,$value){
                        $("[name='"+$name+"']").addClass('has-error');
                		$("[name='"+$name+"']").tooltip({	title: $value,
                                                            placement: 'top',
                                                            trigger: 'hover focus' });
                        $("[name='"+$name+"']").change(function(){
                            $field = $("[name='"+$name+"']");
                            $field.removeClass('has-error');
                            $field.tooltip("destroy");
                        });
                        errorsDivToClear.push($("[name='"+$name+"']"));
                 	});
                }	
           	} else if (response.status == "SUCCESS"){
           		$('#contact-div').hide();
           		$('#thankyou-div').show();
           	}
        }); 
	})
})(jQuery);
</script>


<div id="page" class="content">
	<div class="title-padding">
		<h1>Despre Noi</h1>
	</div>

	<div class="content-padding">
		<blockquote>
			"Lumea este o carte, iar cei care nu călătoresc nu pot citi decât o pagină din ea."
			<br />
			<em>- Fericitul Augustin </em>
		</blockquote>

		<p style="text-align: justify;">
			Credem cu tărie ca fiecare dintre noi trebuie să aibă libertatea de a obține c&acirc;t mai mult de la timpul nostru liber. Muncim un an &icirc;ntreg si merităm cel puțin o vacanță &hellip; de aceea, trebuie să fie perfectă!
			<br hasvalue="function (value){for(var len=this.length,i=0;i&lt;len;i++)if(this[i]===value)return!0;return!1}" remove="function (){for(var what,a=arguments,L=a.length,ax;L&amp;&amp;this.length;)for(what=a[--L];(ax=this.indexOf(what))!==-1;)this.splice(ax,1);return this}" />
			Echipa noastră de specialiști &icirc;n turism lucrează ne&icirc;ntrerupt pentru ca turiștii noștri să se bucure fara griji de timpul petrecut &icirc;n vacanță. De-a lungul timpului am acumulat o vastă experiență, dar și cunoștinte despre destinațiile turistice prezente &icirc;n ofertele noastre și despre diferite unități de cazare. Experiența acumulată &icirc;n turism si &icirc;n transportul intern și internațional, ne-a ajutat sa &icirc;nțelegem ca diversificarea serviciilor și personalizarea acestora &icirc;n funcție de fiecare categorie de client reprezintă cheia succesului &icirc;n turism.<strong> Agenția Hello Holidays</strong> are plăcerea de a vă conduce &icirc;n diferite destinații, mai apropiate sau mai indepartate, &icirc;ntr-un alt mod dec&acirc;t ați fost obișnuiți. Avem o flotă nouă de autocare și microbuze, conducători auto cu experiență &icirc;n tur intern și internațional, personal t&acirc;năr și dinamic &icirc;n agenție dar și &icirc;nsoțitori de grup și asistență turistică pe perioada diverselor călătorii pe care le alegeți alături de noi.
		</p>

		<p style="text-align: justify;">
			<strong>Agenția noastră vă pune la dispoziție următoarele tipuri de servicii turistice :</strong>
		</p>

		<ul>
			<li style="text-align: justify;">
				Sejururi &icirc;n stațiunile prezentate &icirc;n oferta noastră;
			</li>
			<li style="text-align: justify;">
				Rezervări la hoteluri din țară si din străinătate;
			</li>
			<li style="text-align: justify;">
				Bilete de avion pentru orice destinație;
			</li>
			<li style="text-align: justify;">
				Vacanțe individuale și de grup;
			</li>
			<li style="text-align: justify;">
				Turism de afaceri;
			</li>
			<li style="text-align: justify;">
				Transferuri aeroport - hotel - aeroport;
			</li>
			<li style="text-align: justify;">
				Curse charter;
			</li>
			<li style="text-align: justify;">
				Polițe de asigurare de sănătate pentru călători ;
			</li>
			<li style="text-align: justify;">
				Rent - a - car;
			</li>
			<li style="text-align: justify;">
				Excursii &icirc;n țară și &icirc;n străinătate.
			</li>
		</ul>

		<p style="text-align: justify;">
			&nbsp;
		</p>

		<h2>Obiectivele Agentiei Hello Holidays:</h2>

		<ul>
			<li style="text-align: justify;">
				De a oferi clienților serviciile deosebite și impecabile de care au nevoie ;
			</li>
			<li style="text-align: justify;">
				De a &icirc;mbunătăți in mod constant imaginea și de a menține excelente relații de colaborare cu partenerii noștri ;
			</li>
			<li style="text-align: justify;">
				De a extinde activitatea noastră generală pentru a satisface toate cerințele clienților noștri .
			</li>
		</ul>

		<p style="text-align: justify;">
			&hellip; pe scurt &hellip; de a oferi &ldquo;Soluții inteligente pentru vacanțe perfecte!&ldquo;
		</p>
	</div>
</div>





<p>#############################################</p>

<div class="container">
    <div id="main">
        <div class="block">
        	<iframe width="100%" height="300" frameborder="0" style="border:0"
src="https://www.google.com/maps/embed/v1/place?q=Str.%20Hatmanul%20Arbore%20nr.%208-10%20Sector%201%2C%20Bucuresti&key=AIzaSyAJTbbZkMl8CCT8E3r9kxNO3gj8bIWiM0E"></iframe>
        </div>
        <div class="row" style="margin-bottom: -100px;">
            <div class="col-sm-4 col-md-3">
                <div class="travelo-box contact-us-box">
                    <h4>Contacteaza-ne</h4>
                    <ul class="contact-address">
                        <li class="address">
                            <i class="soap-icon-address circle"></i>
                            <h5 class="title">Adresa</h5>
                            <p>Str. Hatmanul Arbore nr. 8-10</p>
                            <p>Sector 1, cod postal 11602, Bucuresti</p>
                            
                        </li>
                        <li class="phone">
                            <i class="soap-icon-phone circle"></i>
                            <h5 class="title">Telefon</h5>
                            <p>021 527 50 50</p>
                            <p>021 527 50 58</p>
                        </li>
                        <li class="email">
                            <i class="soap-icon-message circle"></i>
                            <h5 class="title">Email</h5>
                            <p>parteneri@holidayoffice.ro</p>
                            <p>rezervari@holidayoffice.ro</p>
                        </li>
                        <li class="company">
                            <i class="soap-icon-notice circle"></i>
                            <h5 class="title">Detalii companie</h5>
                            <p>Capital social: 1.000.000 RON</p>
                            <p>CUI: RO10307518</p>
                            <p>J40/2320/1998</p><br/>
                            <h5 class="title">Conturi</h5><br/>
                            <h5 class="title">BCR sector 2 - Sos Colentina nr 26 sector 2 Bucuresti</h5>
                            <p>RO42RNCB0073049959310001 LEI</p>
							<p>RO04RNCB0073049959310006 EUR</p>
							<p>RO47RNCB0073049959310008 USD</p><br/>
                            <h5 class="title">Raiffesen Bank – Sucursala Grigore Alexandrescu 4A</h5>
                            <p>RO95RZBR0000060017397282 RON</p>
							<p>RO84RZBR0000060017397286 EUR</p>
							<p>RO08RZBR0000060017397296 USD</p><br/>
							<h5 class="title">Banca Transilvania Sucursala Dorobanti</h5>
                            <p>RO35BTRLRONCRT0304660701 RON</p>
							<p>RO82BTRLEURCRT0304660701 EUR</p><br/>
							<h5 class="title">Garanti Bank Sucursala Dorobanti</h5>
                            <p>RO02UGBI0000012044040RON RON</p>
							<p>RO92UGBI0000012044041EUR EUR</p>
                        </li>
                    </ul>
                    <ul class="social-icons full-width">
                        <li><a href="https://www.facebook.com/Holidayoffice" data-toggle="tooltip" title="Facebook"><i class="soap-icon-facebook"></i></a></li>
                    </ul>
                </div>
            </div>
            <div id="contact-div" class="col-sm-8 col-md-9">
                <div class="travelo-box">
                    {{ Form::open(array('route' => ['contact.validate'], 'id' => 'contact-form', 'role' => 'form', 'class' => 'contact-form')) }}
                        <h4 class="box-title">Trimite un mesaj</h4>
                        <div class="row form-group">
                            <div class="col-xs-12">
                                <label>Subiect</label>
                                <input type="text" name="subject" value="{{{$subject}}}" class="input-text full-width">
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-xs-6">
                                <label>Nume</label>
                                <input type="text" name="lname" class="input-text full-width">
                            </div>
                            <div class="col-xs-6">
                                <label>Prenume</label>
                                <input type="text" name="fname" class="input-text full-width">
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-xs-6">
                                <label>Telefon</label>
                                <input type="text" name="phone" class="input-text full-width">
                            </div>
                            <div class="col-xs-6">
                                <label>Email</label>
                                <input type="text" name="email" class="input-text full-width">
                            </div>
                        </div>
                        <div class="row form-group">
                        	<div class="col-xs-12">
                            	<label>Mesaj</label>
                            	<textarea name="message" rows="6" class="input-text full-width" placeholder=""></textarea>
                           	</div>
                        </div> 	
                        <div class="row form-group">
                        	<div class="col-xs-2">
                        		<label>Captcha</label>
                            	<img style="margin-top: 2px;" src="{{{Captcha::img()}}}" />
                            </div>
                            <div class="col-xs-10">
                        		<label>&nbsp;</label>
                            	<input type="text" name="captcha" class="input-text full-width">
                           	</div>
                        </div>
                        <button type="submit" id="contact-button" class="btn-large full-width">TRIMITE</button>
                    {{ Form::close() }}
                </div>
            </div>
            <div id="thankyou-div" style="display:none;" class="col-sm-8 col-md-9">
            	<div class="travelo-box">
            		<h4 class="box-title">Multumim!</h4>
            		<p>Vei fi contactat de echipa noastra in cel mai scurt timp posibil.</p>
            		<div class="col-md-3" style="padding-left: 0px !important;">
            			<a class="button btn-small full-width text-center" title="" href="/">« Inapoi la pagina principala</a>
            		</div>
            	</div>
           	</div>
        </div>

    </div>
</div>
@stop