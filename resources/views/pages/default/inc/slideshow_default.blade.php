<script type="text/javascript" src="/js/custom/rooms_view_package.js"></script>
<script type="text/javascript" src="/js/custom/search_ajax.js"></script>
<div class="container slider">
	<div class="row">
	<div class="left_search col-md-3">
        <div class="left_search_inside">
			<div class="search_section">
		      	<h4 class="title">Cautare</h4>
		     	<div class="selector">
	          		<select id="searchHolidayType" class="full-width">
	            		<option value="1" selected>Sejururi</option>
                        <option value="2">Circuite</option>
                        <option value="3">Hoteluri</option>
	          		</select>
	        	</div>
		    </div>
		    <div class="search_section input-others">
		    	<div class="selector">
				    <select id="searchTransportType" class="full-width">
				      	<option value="0">* Tip transport</option>
			            <option value="1">Avion</option>
			            <option value="2">Autocar</option>
			            <option value="3">Transport Individual</option>
			         </select>
		    	</div>
	   		</div>
	   		<div class="search_section">
		    	<div class="row">
			        <div class="col-xs-6">
			            <div class="selector">
						    <select id="searchCountryDestination" class="full-width">
						      	<option value="0">* Destinatie</option>
					         </select>
				    	</div>
			        </div>
			        <div class="col-xs-6">
			            <div class="selector">
						    <select id="searchCityDestination" class="full-width">
						      	<option value="0">* Oras</option>
					         </select>
				    	</div>
		        	</div>
	      		</div>
	   		</div>
	   		<div class="search_section input-others">
		    	<div class="row">
			        <div class="col-xs-6">
			            <div class="selector input-others">
						    <select id="searchDepartureDate" class="full-width">
						      	<option value="0">* Data plecare</option>
					         </select>
				    	</div>
			        </div>
			        <div class="col-xs-6">
			            <div class="selector">
						    <select id="searchDuration" class="full-width">
						      	<option value="0">* Durata</option>
					         </select>
				    	</div>
		        	</div>
	      		</div>
	   		</div>
            <div class="search_section input-hotel">
                <div class="row">
                    <div class="col-xs-6">
                        <div class="datepicker-wrap">
                            <input name="searchDepartureDateHotel" type="text" data-min-date="today" class="input-text full-width" placeholder="Data plecare" id="searchDepartureDateHotel" />
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="datepicker-wrap">
                            <input name="searchArrivalDateHotel" type="text" data-min-date="today" class="input-text full-width" placeholder="Data intoarcere" id="searchArrivalDateHotel" />
                        </div>
                    </div>
                </div>
            </div>
	   		<div class="search_section">
	   			<div class="row">
                            <div class="col-xs-4">
                                <label>Camere</label>
                                <div class="selector">
                                    <select class="full-width" id="noRooms">
                                        <option value="1">01</option>
                                        <option value="2">02</option>
                                        <option value="3">03</option>
                                        <option value="4">04</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <label>Adulti</label>
                                <div class="selector">
                                    <select class="full-width" id="noAdults-1">
                                        <option value="1">01</option>
                                        <option value="2" selected>02</option>
                                        <option value="3">03</option>
                                        <option value="4">04</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <label>Copii</label>
                                <div class="selector">
                                    <select class="full-width" id="noKids-1">
                                        <option value="0">00</option>
                                        <option value="1">01</option>
                                        <option value="2">02</option>
                                        <option value="3">03</option>
                                        <option value="4">04</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- Adding DIVs for possible options -->
                        <div class="row selector_kids">
                            <div class="col-xs-3 right ba-not-show" id="noKids-div-1-c4">
                                <label>Copil 4</label>
                                <div class="selector">
                                    <select class="full-width" id="noKids-1-c4" >
                                        <option value="1">01</option>
                                        <option value="2">02</option>
                                        <option value="3">03</option>
                                        <option value="4">04</option>
                                        <option value="5">05</option>
                                        <option value="6">06</option>
                                        <option value="7">07</option>
                                        <option value="8">08</option>
                                        <option value="9">09</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="13">13</option>
                                        <option value="14">14</option>
                                        <option value="15">15</option>
                                        <option value="16">16</option>
                                        <option value="17">17</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-3 right ba-not-show" id="noKids-div-1-c3">
                                <label>Copil 3</label>
                                <div class="selector">
                                    <select class="full-width" id="noKids-1-c3">
                                        <option value="1">01</option>
                                        <option value="2">02</option>
                                        <option value="3">03</option>
                                        <option value="4">04</option>
                                        <option value="5">05</option>
                                        <option value="6">06</option>
                                        <option value="7">07</option>
                                        <option value="8">08</option>
                                        <option value="9">09</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="13">13</option>
                                        <option value="14">14</option>
                                        <option value="15">15</option>
                                        <option value="16">16</option>
                                        <option value="17">17</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-3 right ba-not-show" id="noKids-div-1-c2">
                                <label>Copil 2</label>
                                <div class="selector">
                                    <select class="full-width" id="noKids-1-c2">
                                        <option value="1">01</option>
                                        <option value="2">02</option>
                                        <option value="3">03</option>
                                        <option value="4">04</option>
                                        <option value="5">05</option>
                                        <option value="6">06</option>
                                        <option value="7">07</option>
                                        <option value="8">08</option>
                                        <option value="9">09</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="13">13</option>
                                        <option value="14">14</option>
                                        <option value="15">15</option>
                                        <option value="16">16</option>
                                        <option value="17">17</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-3 right ba-not-show"  id="noKids-div-1-c1">
                                <label>Copil 1</label>
                                <div class="selector">
                                    <select class="full-width" id="noKids-1-c1">
                                        <option value="1">01</option>
                                        <option value="2">02</option>
                                        <option value="3">03</option>
                                        <option value="4">04</option>
                                        <option value="5">05</option>
                                        <option value="6">06</option>
                                        <option value="7">07</option>
                                        <option value="8">08</option>
                                        <option value="9">09</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="13">13</option>
                                        <option value="14">14</option>
                                        <option value="15">15</option>
                                        <option value="16">16</option>
                                        <option value="17">17</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4">
                                
                            </div>
                            <div class="col-xs-4 ba-not-show" id="noAdults-div-2">
                                <label>Adulti</label>
                                <div class="selector">
                                    <select class="full-width" id="noAdults-2">
                                        <option value="1">01</option>
                                        <option value="2">02</option>
                                        <option value="3">03</option>
                                        <option value="4">04</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-4 ba-not-show" id="noKids-div-2">
                                <label>Copii</label>
                                <div class="selector">
                                    <select class="full-width" id="noKids-2">
                                        <option value="0">00</option>
                                        <option value="1">01</option>
                                        <option value="2">02</option>
                                        <option value="3">03</option>
                                        <option value="4">04</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-3 right ba-not-show" id="noKids-div-2-c4">
                                <label>C4</label>
                                <div class="selector">
                                    <select id="noKids-2-c4">
                                        <option value="1">01</option>
                                        <option value="2">02</option>
                                        <option value="3">03</option>
                                        <option value="4">04</option>
                                        <option value="5">05</option>
                                        <option value="6">06</option>
                                        <option value="7">07</option>
                                        <option value="8">08</option>
                                        <option value="9">09</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="13">13</option>
                                        <option value="14">14</option>
                                        <option value="15">15</option>
                                        <option value="16">16</option>
                                        <option value="17">17</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-3 right ba-not-show" id="noKids-div-2-c3">
                                <label>C3</label>
                                <div class="selector">
                                    <select class="full-width" id="noKids-2-c3">
                                        <option value="1">01</option>
                                        <option value="2">02</option>
                                        <option value="3">03</option>
                                        <option value="4">04</option>
                                        <option value="5">05</option>
                                        <option value="6">06</option>
                                        <option value="7">07</option>
                                        <option value="8">08</option>
                                        <option value="9">09</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="13">13</option>
                                        <option value="14">14</option>
                                        <option value="15">15</option>
                                        <option value="16">16</option>
                                        <option value="17">17</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-3 right ba-not-show" id="noKids-div-2-c2">
                                <label>C2</label>
                                <div class="selector">
                                    <select class="full-width" id="noKids-2-c2">
                                        <option value="1">01</option>
                                        <option value="2">02</option>
                                        <option value="3">03</option>
                                        <option value="4">04</option>
                                        <option value="5">05</option>
                                        <option value="6">06</option>
                                        <option value="7">07</option>
                                        <option value="8">08</option>
                                        <option value="9">09</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="13">13</option>
                                        <option value="14">14</option>
                                        <option value="15">15</option>
                                        <option value="16">16</option>
                                        <option value="17">17</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-3 right ba-not-show" id="noKids-div-2-c1">
                                <label>C1</label>
                                <div class="selector">
                                    <select class="full-width" id="noKids-2-c1">
                                        <option value="1">01</option>
                                        <option value="2">02</option>
                                        <option value="3">03</option>
                                        <option value="4">04</option>
                                        <option value="5">05</option>
                                        <option value="6">06</option>
                                        <option value="7">07</option>
                                        <option value="8">08</option>
                                        <option value="9">09</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="13">13</option>
                                        <option value="14">14</option>
                                        <option value="15">15</option>
                                        <option value="16">16</option>
                                        <option value="17">17</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4">
                                
                            </div>
                            <div class="col-xs-4 ba-not-show" id="noAdults-div-3">
                                <label>Adulti</label>
                                <div class="selector">
                                    <select class="full-width" id="noAdults-3">
                                        <option value="1">01</option>
                                        <option value="2">02</option>
                                        <option value="3">03</option>
                                        <option value="4">04</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-4 ba-not-show" id="noKids-div-3">
                                <label>Copii</label>
                                <div class="selector">
                                    <select class="full-width" id="noKids-3">
                                        <option value="0">00</option>
                                        <option value="1">01</option>
                                        <option value="2">02</option>
                                        <option value="3">03</option>
                                        <option value="4">04</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-3 right ba-not-show" id="noKids-div-3-c4">
                                <label>C4</label>
                                <div class="selector">
                                    <select id="noKids-3-c4">
                                        <option value="1">01</option>
                                        <option value="2">02</option>
                                        <option value="3">03</option>
                                        <option value="4">04</option>
                                        <option value="5">05</option>
                                        <option value="6">06</option>
                                        <option value="7">07</option>
                                        <option value="8">08</option>
                                        <option value="9">09</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="13">13</option>
                                        <option value="14">14</option>
                                        <option value="15">15</option>
                                        <option value="16">16</option>
                                        <option value="17">17</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-3 right ba-not-show" id="noKids-div-3-c3">
                                <label>C3</label>
                                <div class="selector">
                                    <select class="full-width" id="noKids-3-c3">
                                        <option value="1">01</option>
                                        <option value="2">02</option>
                                        <option value="3">03</option>
                                        <option value="4">04</option>
                                        <option value="5">05</option>
                                        <option value="6">06</option>
                                        <option value="7">07</option>
                                        <option value="8">08</option>
                                        <option value="9">09</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="13">13</option>
                                        <option value="14">14</option>
                                        <option value="15">15</option>
                                        <option value="16">16</option>
                                        <option value="17">17</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-3 right ba-not-show" id="noKids-div-3-c2">
                                <label>C2</label>
                                <div class="selector">
                                    <select class="full-width" id="noKids-3-c2">
                                        <option value="1">01</option>
                                        <option value="2">02</option>
                                        <option value="3">03</option>
                                        <option value="4">04</option>
                                        <option value="5">05</option>
                                        <option value="6">06</option>
                                        <option value="7">07</option>
                                        <option value="8">08</option>
                                        <option value="9">09</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="13">13</option>
                                        <option value="14">14</option>
                                        <option value="15">15</option>
                                        <option value="16">16</option>
                                        <option value="17">17</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-3 right ba-not-show" id="noKids-div-3-c1">
                                <label>C1</label>
                                <div class="selector">
                                    <select class="full-width" id="noKids-3-c1">
                                        <option value="1">01</option>
                                        <option value="2">02</option>
                                        <option value="3">03</option>
                                        <option value="4">04</option>
                                        <option value="5">05</option>
                                        <option value="6">06</option>
                                        <option value="7">07</option>
                                        <option value="8">08</option>
                                        <option value="9">09</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="13">13</option>
                                        <option value="14">14</option>
                                        <option value="15">15</option>
                                        <option value="16">16</option>
                                        <option value="17">17</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4">
                                
                            </div>
                            <div class="col-xs-4 ba-not-show" id="noAdults-div-4">
                                <label>Adulti</label>
                                <div class="selector">
                                    <select class="full-width" id="noAdults-4">
                                        <option value="1">01</option>
                                        <option value="2">02</option>
                                        <option value="3">03</option>
                                        <option value="4">04</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-4 ba-not-show" id="noKids-div-4">
                                <label>Copii</label>
                                <div class="selector">
                                    <select class="full-width" id="noKids-4">
                                        <option value="0">00</option>
                                        <option value="1">01</option>
                                        <option value="2">02</option>
                                        <option value="3">03</option>
                                        <option value="4">04</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-3 right ba-not-show" id="noKids-div-4-c4">
                                <label>C4</label>
                                <div class="selector">
                                    <select id="noKids-4-c4">
                                        <option value="1">01</option>
                                        <option value="2">02</option>
                                        <option value="3">03</option>
                                        <option value="4">04</option>
                                        <option value="5">05</option>
                                        <option value="6">06</option>
                                        <option value="7">07</option>
                                        <option value="8">08</option>
                                        <option value="9">09</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="13">13</option>
                                        <option value="14">14</option>
                                        <option value="15">15</option>
                                        <option value="16">16</option>
                                        <option value="17">17</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-3 right ba-not-show" id="noKids-div-4-c3">
                                <label>C3</label>
                                <div class="selector">
                                    <select class="full-width" id="noKids-4-c3">
                                        <option value="1">01</option>
                                        <option value="2">02</option>
                                        <option value="3">03</option>
                                        <option value="4">04</option>
                                        <option value="5">05</option>
                                        <option value="6">06</option>
                                        <option value="7">07</option>
                                        <option value="8">08</option>
                                        <option value="9">09</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="13">13</option>
                                        <option value="14">14</option>
                                        <option value="15">15</option>
                                        <option value="16">16</option>
                                        <option value="17">17</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-3 right ba-not-show" id="noKids-div-4-c2">
                                <label>C2</label>
                                <div class="selector">
                                    <select class="full-width" id="noKids-4-c2">
                                        <option value="1">01</option>
                                        <option value="2">02</option>
                                        <option value="3">03</option>
                                        <option value="4">04</option>
                                        <option value="5">05</option>
                                        <option value="6">06</option>
                                        <option value="7">07</option>
                                        <option value="8">08</option>
                                        <option value="9">09</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="13">13</option>
                                        <option value="14">14</option>
                                        <option value="15">15</option>
                                        <option value="16">16</option>
                                        <option value="17">17</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-3 right ba-not-show" id="noKids-div-4-c1">
                                <label>C1</label>
                                <div class="selector">
                                    <select class="full-width" id="noKids-4-c1">
                                        <option value="1">01</option>
                                        <option value="2">02</option>
                                        <option value="3">03</option>
                                        <option value="4">04</option>
                                        <option value="5">05</option>
                                        <option value="6">06</option>
                                        <option value="7">07</option>
                                        <option value="8">08</option>
                                        <option value="9">09</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="13">13</option>
                                        <option value="14">14</option>
                                        <option value="15">15</option>
                                        <option value="16">16</option>
                                        <option value="17">17</option>
                                    </select>
                                </div>
                            </div>
                        </div>
	   		</div>
                        
      		<div class="search_section">
        		<label class="hidden-xs">&nbsp;</label>
        		<button type="submit" id="searchButton" class="full-width animated" data-animation-type="bounce" data-animation-duration="1">CAUTA ACUM</button>
      		</div>
        </div>

	</div>
	<div class="col-md-9">
		<!-- <img src="images/slider/img_dubai.jpg" alt="imagine" /> -->
		<div id="slideshow">
		    <div class="fullwidthbanner-container">
		        <div class="revolution-slider" style="height: 0; overflow: hidden;">
		            <ul>  
		            	<!-- <li data-transition="fade" data-slotamount="7" data-masterspeed="1500">
		                    <a href="/oferte/sejururi?page=1&offerTypes=2&sortBy=price&sortOrder=ASC"><img src="images/slider/topit_site.jpg" alt=""></a>
		                </li> -->
		                
		            	<li data-transition="fade" data-slotamount="7" data-masterspeed="1500">
		                    <a href="/oferte/sejururi?page=1&locationFiltering=child&locationId=181&sortBy=price&sortOrder=ASC"><img src="images/slider/iordania_BANNER.jpg" alt=""></a>
		                </li>
		            	  
		                <li data-transition="fade" data-slotamount="7" data-masterspeed="1500">
		                    <a href="/oferte/sejururi?page=1&locationFiltering=child&locationId=5419&sortBy=price&sortOrder=ASC"><img src="images/slider/RODOS_BANNER.jpg" alt=""></a>
		                </li>
		                
		                <li data-transition="fade" data-slotamount="7" data-masterspeed="1500">
		                    <a href="/oferte/sejururi?page=1&locationFiltering=child&locationId=5941&sortBy=price&sortOrder=ASC"><img src="images/slider/ANTALYA_BANNER.jpg" alt=""></a>
		                </li>
		                
		                <li data-transition="fade" data-slotamount="7" data-masterspeed="1500">
		                    <a href="/oferte/sejururi?page=1&locationFiltering=child&locationId=5394&sortBy=price&sortOrder=ASC"><img src="images/slider/CRETA_BANNER2.jpg" alt=""></a>
		                </li>
		                
		                <li data-transition="fade" data-slotamount="7" data-masterspeed="1500">
		                    <a href="/oferte/sejururi?page=1&locationFiltering=child&locationId=9337&sortBy=price&sortOrder=ASC"><img src="images/slider/CORFU_BANNER.jpg" alt=""></a>
		                </li>
		            </ul>
		        </div>
		    </div>
		</div>
	</div>
	</div>
<!-- <div id="slideshow">
    <div class="fullwidthbanner-container">
        <div class="revolution-slider" style="height: 0; overflow: hidden;">
            <ul>    
                <li data-transition="slidedown" data-slotamount="7" data-masterspeed="1500">
                    <img src="images/slider/slide_dubai.jpg" alt="">
                </li>
                
                <li data-transition="slidedown" data-slotamount="7" data-masterspeed="1500">
                    <img src="images/slider/slide_roma.jpg" alt="">
                </li>
                
                <li data-transition="slidedown" data-slotamount="7" data-masterspeed="1500">
                    <img src="images/slider/slide_maldive.jpg" alt="">
                </li>
            </ul>
        </div>
    </div>
</div> -->
	<div class="clear"></div>
</div>