<link rel="stylesheet" href="/sximo/themes/default/js/jquery-loading/waitMe.css" />
<script type="text/javascript" src="/sximo/themes/default/js/jquery-loading/waitMe.min.js"></script>
<script type="text/javascript" src="{{ asset('sximo/themes/helloholiday/js/search_ajax_jsonp.js')}}"></script>
<script type="text/javascript">
	$(document).ready(function(){
  		$('#guests1').roomify(null);
  		$('#guests2').roomify(null);
  		$('#guests3').roomify(null);
		$("#circuitsTabActivator").click();
	});
</script>
<div id="main" class="home"><div class="inner">

    <section class="slider">
            <div id="full-width-slider" class="royalSlider rsMinW">
            	
            	<div class="rsContent">
	            <a href="http://helloholidays.ro/page/reducere-voucher"><img src="{{ asset('sximo/themes/helloholiday/images/lib/HH-Xmas-gift-voucher-slider.jpg')}}" alt="" /></a>
	            </div>
	            
            	<div class="rsContent">
              	<a href="http://helloholidays.ro/oferte/circuite?page=1&locationFiltering=child&locationId=10032&sortBy=price&sortOrder=ASC&categoryId=8"><img src="{{ asset('sximo/themes/helloholiday/images/lib/slider-revelion-budapesta...jpg')}}" alt="" /></a>
              </div>
              
              <div class="rsContent">
              	<a href="http://www.helloholidays.ro/oferte/circuite?categoryId=7"><img src="{{ asset('sximo/themes/helloholiday/images/lib/slider-o-zi-vacanta.jpg')}}" alt="" /></a>
              </div>
              
	        		<!-- <div class="rsContent">
	            <a href="/oferte/Circuite/Ungaria/Piata-de-Craciun-de-la-Budapesta_11_1317_HH_sid0"><img src="{{ asset('sximo/themes/helloholiday/images/lib/piata-craciun-budapesta-12-decembrie.jpg')}}" alt="" /></a>
	            </div>
	            
	            <div class="rsContent">
	            <a href="http://helloholidays.ro/oferte/Hoteluri/Cehia/Piata-de-Craciun-de-la-Praga_11_1318_HH_sid0"><img src="{{ asset('sximo/themes/helloholiday/images/lib/banner-craciun-praga.jpg')}}" alt="" /></a>
	            </div> -->
	            
	        		<div class="rsContent">
	            <a href="/oferte/sejururi?page=1&offerTypes=1&sortBy=price&sortOrder=ASC"><img src="{{ asset('sximo/themes/helloholiday/images/lib/slider_hello_grecia_2017.jpg')}}" alt="" /></a>
	            </div>
	            
	            <!-- <div class="rsContent">
	            <a href="http://helloholidays.ro/oferte/Hoteluri/Romania/Intalnire-cu-Spiridusii-lui-Mos-Craciun---Curtea-de-Arges_11_91291_LOCAL_sid0"><img src="{{ asset('sximo/themes/helloholiday/images/lib/banner-spiridusi-last-minute.jpg')}}" alt="" /></a>
	            </div> -->
	            <!-- <div class="rsContent">
	            <a href="http://helloholidays.ro/oferte/Hoteluri/Romania/Intalnire-cu-Spiridusii-lui-Mos-Craciun---Curtea-de-Arges_11_91291_LOCAL_sid0"><img src="{{ asset('sximo/themes/helloholiday/images/lib/banner-spiridusi-17-new.jpg')}}" alt="" /></a>
	            </div> -->
	            
                
            </div>
        	<h2>Cele mai bune oferte</h2>
            <img src="{{ asset('sximo/themes/helloholiday/images/slide-icon.png')}}" alt="Slide icon" class="slide-icon" />
    </section>









    <section class="home-search"><div id="search-module">

        <div class="tabs">
            <ul class="tab-links">
            <li id="circuitsTabActivator" class="active"><a href="#tab1">Circuite</a></li>
            <li id="staysTabActivator"><a href="#tab2">Sejururi</a></li>
            <li id="hotelsTabActivator"><a href="#tab3">Hoteluri</a></li>
            </ul>

            <div class="tab-content">
            	<div id="tab1" class="tab active">
              	<h4 class="text-center">Caută circuitul dorit</h4>
								<div class="search_section">
							    <div class="selector">
								    <label>Transport</label>
							        <select id="searchTransportTypeCircuit">
						            	<option value="0">* Tip transport</option>
						            	<option value="1">Avion</option>
						            	<option value="2">Autocar</option>
						            	<option value="3">Transport Individual</option>
							         </select>
								    </div>
								</div>

								<div class="search_section">
									<div class="selector">
										<label>Plecare din</label>
										<select id="searchDeparturePointCircuit">
											<option value="0">* Plecare din</option>
										</select>
									</div>
								</div>

								<div class="search_section">
									<div class="selector">
										<label>Destinatie</label>
										<select id="searchCountryDestinationCircuit">
											<option value="0">* Destinatie</option>
										</select>
									</div>
								</div>

								<div class="search_section">
									<div class="selector">
										<label>Oras</label>
										<select id="searchCityDestinationCircuit">
										    <option value="0">* Oras</option>
										 </select>
									</div>
								</div>

								<div class="search_section">
									<div class="selector">
										<label>Data plecare</label>
										<select id="searchDepartureDateCircuit">
									    <option value="0">* Data plecare</option>
									  </select>
									</div>
								</div>

								<div class="search_section">
									<div class="selector">
										<label>Durata</label>
										<select id="searchDurationCircuit">
									    <option value="0">* Durata</option>
									  </select>
									</div>
								</div>

								<div class="search_section roomifi_here">
									<div class="selector">
										<label>Camere/persoane</label>
										<div id="guests1"></div>
									</div>
								</div>
								<div class="clear"></div>
								<div class="search_button text-center">
								    <button type="submit" id="circuitsSearchButton" class="full-width animated">CAUTĂ</button>
								</div>

            </div>

            <div id="tab2" class="tab">
                <h4 class="text-center">Caută sejurul dorit</h4>
                <div class="search_section">
							    <div class="selector">
								    <label>Transport</label>
							        <select id="searchTransportTypeStay">
						            <option value="0">* Tip transport</option>
						            <option value="1">Avion</option>
						            <option value="2">Autocar</option>
						            <option value="3">Transport Individual</option>
							         </select>
								    </div>
								</div>

								<div class="search_section">
									<div class="selector">
										<label>Plecare din</label>
										<select id="searchDeparturePointStay">
											<option value="0">* Plecare din</option>
										</select>
									</div>
								</div>

								<div class="search_section">
									<div class="selector">
										<label>Destinatie</label>
										<select id="searchCountryDestinationStay">
											<option value="0">* Destinatie</option>
										</select>
									</div>
								</div>

								<div class="search_section">
									<div class="selector">
										<label>Oras</label>
										<select id="searchCityDestinationStay">
										    <option value="0">* Oras</option>
										 </select>
									</div>
								</div>

								<div class="search_section">
									<div class="selector">
										<label>Data plecare</label>
										<select id="searchDepartureDateStay">
									    <option value="0">* Data plecare</option>
									  </select>
									</div>
								</div>

								<div class="search_section">
									<div class="selector">
										<label>Durata</label>
										<select id="searchDurationStay">
									    <option value="0">* Durata</option>
									  </select>
									</div>
								</div>

								<div class="search_section roomifi_here">
									<div class="selector">
										<label>Camere/persoane</label>
										<div id="guests2"></div>
									</div>
								</div>
								<div class="clear"></div>
								<div class="search_button text-center">
								    <button type="submit" id="staysSearchButton" class="full-width animated">CAUTĂ</button>
								</div>
            </div>

            <div id="tab3" class="tab">
                <h4 class="text-center">Caută hotelul dorit</h4>
								<div class="search_section">
									<div class="selector">
										<label>Destinatie</label>
										<select id="searchCountryDestinationHotel">
											<option value="0">* Destinatie</option>
										</select>
									</div>
								</div>

								<div class="search_section">
									<div class="selector">
										<label>Oras</label>
										<select id="searchCityDestinationHotel">
										    <option value="0">* Oras</option>
										 </select>
									</div>
								</div>

								<div class="search_section">
									<div class="selector">
										<label>Checkin</label>
										<input type="text" id="searchDepartureDateHotel" value="* Checkin" class="datepicker" readonly/>
									</div>
								</div>

								<div class="search_section">
									<div class="selector">
										<label>Checkout</label>
										<input type="text" id="searchArrivalDateHotel" value="* Checkout" class="datepicker" readonly/>
									</div>
								</div>

								<div class="search_section roomifi_here">
									<div class="selector">
										<label>Camere/persoane</label>
										<div id="guests3"></div>
									</div>
								</div>

								<div class="clear"></div>
								<div class="search_button text-center">
								    <button type="submit" id="hotelsSearchButton" class="full-width animated">CAUTĂ</button>
								</div>
            </div>
          </div>
        </div>

    </div></section>



    </div>





    <section class="accordion-stuff">
    	<h2>Oferte speciale</h2>

        <div class="accordion">
        	<?php $ac_no = 1; ?>
      		@foreach($featuredPackages as $package)
						<?php
					        $location = App\Models\Travel\Geography::getCountryForHotel($package->id_hotel,$package->soap_client);
					        $transport = App\Models\Travel\PackageInfo::getTransportCode($package->is_tour,$package->is_bus,$package->is_flight);
					        $link = "/oferte/".$pageTitle.'/'.$location.'/'.str_replace("/","-",str_replace(" ", "-", str_replace("_","-",$package->hotel->name)))."_".$transport."_".$package->id_hotel."_".$package->soap_client."_sid0";
					    ?>
						<article>
							<div class="accordion-section">
								<a class="accordion-section-title cf" href="#accordion-<?php echo $ac_no; ?>" title="">
								<div class="box-60">
              		@if ($package->is_flight)
              			<span class="accordion-transportation plane">Tr.</span>
                  @elseif ($package->is_bus)
                  	<span class="accordion-transportation bus">Tr.</span>
                  @else
                  	<span class="accordion-transportation individual">Tr.</span>
                  @endif
									<span>{{$package->hotel->name}}</span>
								</div>
								<div class="box-40">
									<span class="box-40">
									
									@if($package->duration > 1)
										{{$package->duration}} nopti
									@else
										{{$package->duration}} noapte
									@endif
									</span>
									<?php  
										$c = \DB::table('cached_prices')->where('id_package','=',$package->id_package)->orderBy('gross','asc')->first();
										//dd($c);
									 ?>
									<span class="accordion-price box-40">@if($c->gross)
									 de la {{number_format($c->gross,2,'.',',')}} 
									@if($c->currency == 0)	
									 € 
									@else
									 LEI
									@endif 
									 @endif</span>
								</div> </a>

								<div id="accordion-<?php echo $ac_no; ?>" class="accordion-section-content">
									<div class="accordion-section-content-wrapp">
										<p>{{$package->hotel->name}} – {{$package->hotel->getFormatedLocation()}}</p>
									</div>
									<a href="{{$link}}" class="accordion-details">Detalii oferta</a>
								</div>
							</div>
						</article>
						<?php $ac_no++; ?>
					@endforeach
        </div><!--end .accordion-->

    </section>

	<div class="inner">








    <section>
    	<h2>Oferte turistice curente</h2>

        <article class="box box-spacer">
            <header>
            <h1><a href="/oferte/circuite?page=1&locationFiltering=child&locationId=133&categoryId=8&sortBy=price&sortOrder=ASC" rel="bookmark" title="">Revelion in Bulgaria</a></h1>
            </header>
            <a href="/oferte/circuite?page=1&locationFiltering=child&locationId=133&categoryId=8&sortBy=price&sortOrder=ASC">
            <img src="{{ asset('sximo/themes/helloholiday/images/lib/banner-revelion-in-Bulgaria.jpg')}}" alt="" />
            <p>Sejururi de la 149 €/pers</p>
            <span class="box-arrow">Detalii oferta</span>
            </a>
        </article>

        <article class="box box-spacer">
            <header>
            <h1><a href="http://www.helloholidays.ro/oferte/Hoteluri/Croatia/Capitale-Balcanice_11_91502_LOCAL_sid0" rel="bookmark" title="">Capitale Balcanice</a></h1>
            </header>
            <a href="http://www.helloholidays.ro/oferte/Hoteluri/Croatia/Capitale-Balcanice_11_91502_LOCAL_sid0">
            <img src="{{ asset('sximo/themes/helloholiday/images/lib/banner-capitale-balcanice.jpg')}}" alt="" />
            <p>Bucuresti – Belgrad – Sarajevo – Dubrovnik – Tirana – Skopje – Sofia – Bucuresti</p>
            <span class="box-arrow">Detalii oferta</span>
            </a>
        </article>

        <article class="box box-spacer">
            <header>
            <h1><a href="http://www.helloholidays.ro/oferte/Hoteluri/Ungaria/Budapesta-program-lowcost-hotel-3*_11_1334_HH_sid0" rel="bookmark" title="">Budapesta – lowcost</a></h1>
            </header>
            <a href="http://www.helloholidays.ro/oferte/Hoteluri/Ungaria/Budapesta-program-lowcost-hotel-3*_11_1334_HH_sid0">
            <img src="{{ asset('sximo/themes/helloholiday/images/lib/banner-budapesta-lowcost.jpg')}}" alt="" />
            <p>Budapesta program lowcost 3 nopti, 2017</p>
            <span class="box-arrow">Detalii oferta</span>
            </a>
        </article>

        <article class="box box-spacer">
            <header>
            <h1><a href="http://www.helloholidays.ro/oferte/circuite?categoryId=20" rel="bookmark" title="">Paste 2017</a></h1>
            </header>
            <a href="http://www.helloholidays.ro/oferte/circuite?categoryId=20">
            <img src="{{ asset('sximo/themes/helloholiday/images/lib/banner-paste-2017-new.jpg')}}" alt="" />
            <p>Calatori de sarbatori</p>
            <span class="box-arrow">Detalii oferta</span>
            </a>
        </article>

        <article class="box box-spacer">
            <header>
            <h1><a href="http://www.helloholidays.ro/oferte/Hoteluri/Grecia/Revelion-Atena_11_291_HH_sid0" rel="bookmark" title="">Revelion Atena</a></h1>
            </header>
            <a href="http://www.helloholidays.ro/oferte/Hoteluri/Grecia/Revelion-Atena_11_291_HH_sid0">
            <img src="{{ asset('sximo/themes/helloholiday/images/lib/banner-revelion-atena-new.jpg')}}" alt="" />
            <p>Petrece un Revelion de poveste</p>
            <span class="box-arrow">Detalii oferta</span>
            </a>
        </article>

        <article class="box box-spacer">
            <header>
            <h1><a href="/oferte/sejururi?page=1&locationFiltering=child&locationId=145&sortBy=price&sortOrder=ASC" title="">Ultra First Minute</a></h1>
            </header>
            <a href="/oferte/sejururi?page=1&locationFiltering=child&locationId=145&sortBy=price&sortOrder=ASC">
            <img src="{{ asset('sximo/themes/helloholiday/images/lib/banner-grecia-vara-2017.jpg')}}" alt="" />
            <p>Grecia, Vara 2017</p>
            <span class="box-arrow">Detalii oferta</span>
            </a>
        </article>

        <article class="box box-spacer">
            <header>
            <h1><a href="/oferte/circuite?page=1&locationFiltering=child&locationId=161&sortBy=price&sortOrder=ASC&categoryId=7" rel="bookmark" title="">Excursii de 1 zi in Romania</a></h1>
            </header>
            <a href="/oferte/circuite?page=1&locationFiltering=child&locationId=161&sortBy=price&sortOrder=ASC&categoryId=7">
            <img src="{{ asset('sximo/themes/helloholiday/images/lib/banner-excursii-de-1-zi-in-Romania.jpg')}}" alt="" />
            <p>Descopera minunile din jurul tau!</p>
            <span class="box-arrow">Detalii oferta</span>
            </a>
        </article>

        <article class="box box-spacer">
            <header>
            <h1><a href="http://www.helloholidays.ro/oferte/Hoteluri/Romania/Castelul-Miko-–-Cetatea-Neamt-–-Humulesti–-Agapia---Varatec_11_91500_LOCAL_sid0" rel="bookmark" title="">Circuit Romania</a></h1>
            </header>
            <a href="http://www.helloholidays.ro/oferte/Hoteluri/Romania/Castelul-Miko-–-Cetatea-Neamt-–-Humulesti–-Agapia---Varatec_11_91500_LOCAL_sid0">
            <img src="{{ asset('sximo/themes/helloholiday/images/lib/banner-agapia-varatec-neamt.jpg')}}" alt="" />
            <p>Castelul Miko – Cetatea Neamt – Humulesti – Agapia – Varatec</p>
            <span class="box-arrow">Detalii oferta</span>
            </a>
        </article>
    </section>


    <section>
        	<h2>Cele mai populare oferte</h2>
            <!-- <h3 class="text-center padding-bottom-20">Sejururi in Grecia, Tursia si Bulgaria. Destinatiile de vacanta preferate de turisti.</h3> -->

            <!--<div class="box-50 box-spacer background-color-lochmara"><div class="inner">
            	<article>
                <h1 class="text-blue-dark text-22">VACANTE 2016</h1>
                <ul class="text-white text-18 padding-top-30">
                    <li><a href="#">REVELION 2016</a></li>
                    <li><a href="#">PROGRAME CRACIUN</a></li>
                    <li><a href="#">PROGRAME REVELION 2016</a></li>
                    <li><a href="#">CIRCUITE EUROPA</a></li>
                    <li><a href="#">CIRCUITE EXOTICE</a></li>
                    <li><a href="#">CIRCUITE ASIA</a></li>
                    <li><a href="#">CIRCUITE AFRICA</a></li>
                    <li><a href="#">CIRCUITE AMERICA</a></li>
                    <li><a href="#">PELERINAJE</a></li>
                    <li><a href="#">EXCURSII DE 1 ZI</a></li>
                    <li><a href="#">MARTI 3 CEASURI BUNE</a></li>
                </ul>
                </article>
            </div></div>-->

            <div class="box-50 box-spacer background-color-java promo"><div class="box-50-inner">
            	<article>
                <!-- <h1 class="text-blue-dark text-20">Oferte Hello Holidays</h1> -->
                <!-- <ul class="text-white text-18 padding-top-20">
                    <li><a href="/oferte/sejururi?page=1&locationFiltering=child&locationId=9331&sortBy=price&sortOrder=ASC" class="individual">Grecia  - Thassos<span>de la 75 Euro</span></a></li>
                    <li><a href="/oferte/sejururi?page=1&locationFiltering=child&locationId=9486&sortBy=price&sortOrder=ASC" class="individual">Grecia - Evia <span>de la 80 Euro</span></a></li>
                    <li><a href="/oferte/sejururi?page=1&locationFiltering=child&locationId=9327&sortBy=price&sortOrder=ASC" class="individual">Grecia - Lefkada <span>de la 84 Euro</span></a></li>
                    <li><a href="/oferte/sejururi?page=1&locationFiltering=child&locationId=9330&sortBy=price&sortOrder=ASC" class="individual">Grecia - Riviera Olympului <span>de la 75 Euro</span></a></li>
                    <li><a href="/oferte/sejururi?page=1&locationFiltering=child&locationId=5945&sortBy=price&sortOrder=ASC" class="individual">Turcia  - Bodrum <span>de la 130 Euro</span></a></li>
                </ul> -->
                <ul class="text-white text-18 padding-top-20">
                    <li><a href="/oferte/Hoteluri/Polonia/City-Break-Cracovia_11_91504_LOCAL_sid0" class="bus">City Break Cracovia <span>239 eur/pers</span></a></li>
                    <li><a href="http://www.helloholidays.ro/oferte/Hoteluri/Ungaria/Circuit-Ungaria_11_91503_LOCAL_sid0" class="bus">Circuit Ungaria <span>179 eur/pers</span></a></li>
                    <li><a href="http://www.helloholidays.ro/oferte/Hoteluri/Romania/Bucovina,-Maramures-si-Transilvania_11_91501_LOCAL_sid0" class="bus">Bucovina, Maramures si Transilvania <span>799 Lei</span></a></li>
                    <li><a href="/oferte/Sejururi/Grecia/STUDIO-PANORAMA_03_32_HH_sid0" class="bus">STUDIO PANORAMA - GOLDEN BEACH - THASSOS <span>80 eur/pers</span></a></li>
                    <li><a href="/oferte/Hoteluri/Ungaria/Revelion-Budapesta-H.-Ibis_11_1288_HH_sid0" class="bus">Revelion Budapesta H. Ibis 4 nopti <span>279 eur/pers</span></a></li>
                </ul>
                </article>
            </div></div>

            <div class="box-50 box-spacer background-color-lochmara promo">
            	<a href="/oferte/Sejururi/Grecia/STUDIO-KONSTANTINOS_03_1189_HH_sid0"><img src="{{ asset('sximo/themes/helloholiday/images/lib/hello_grecia_evia_26_10.jpg')}}" alt="" /></a>
            </div>

        </section>
    </div>
    </div><!-- end #main -->
