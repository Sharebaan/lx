@extends('layouts.master')
@section('slideshow')
@stop
@section('content')

<script type="text/javascript" src="/js/custom/rooms_view_package.js"></script>
<script type="text/javascript">
    (function($) {
        $(document).ready(function() {
            var rooms = <?php echo $rooms; ?>;
            $("#noRooms").val(rooms.length).trigger('change');
            $.each(rooms,function(i,room){
                var j = i+1;
                $("#noAdults-"+j).val(room.adults).trigger('change');
                if(room.kids != null){
                    $("#noKids-"+j).val(room.kids.length).trigger('change');
                    $.each(room.kids,function(t,kid){
                        var u = t+1;
                        $("#noKids-"+j+"-c"+u).val(kid).trigger('change');
                    });
                }
            });
        });
    })(jQuery);
</script>
<div class="container">
    <div class="row">
    	<div class="view_trip">
    		<div class="col-md-12">
                    <span class="review ba-sejur-title-transport"><i class="soap-icon-car circle"></i></span>
                <h2 class="box-title ba-sejur-title">
                    {{{$hotel->name}}}
                    <small>{{{$hotel->getFormatedLocation()}}}</small>
                </h2>
            </div>

        <div id="main" class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
            <div class="tab-container style1" id="hotel-main-content">
                
                <div class="tab-content">
                    <div id="photos-tab" class="tab-pane fade in active">
                        <div class="photo-gallery style1" data-animation="slide" data-sync="#photos-tab .image-carousel">
                            <ul class="slides">
                                @foreach($hotel->images as $image)
                                <?php 
                                    $mimeArray = explode('/', $image->mime_type);
                                    $type = $mimeArray[count($mimeArray)-1];
                                    if($hotel->soap_client == "HO"){
                                        $imgUrl = "/images/offers/{$image->id}.{$type}";
                                    } else {
                                        $imgUrl = "/images/offers/".$hotel->soap_client."/{$image->id}.{$type}";
                                    }
                                ?>
                                <li class="slider_li_img" style="background: url('{{{$imgUrl}}}') center center no-repeat; background-size: cover;"><img src="/images/watermark_581x280.png" /></li>
                                @endforeach
                                @if (count($hotel->images) == 0)
                                <li class="slider_li_img" ><img src="/images/nopicture_581x280.png" /></li>
                                @endif
                                
                            </ul>
                        </div>
                        <div class="image-carousel style1" data-animation="slide" data-item-width="70" data-item-margin="10" data-sync="#photos-tab .photo-gallery">
                            <ul class="slides">
                                @foreach($hotel->images as $image)
                                <?php 
                                    $mimeArray = explode('/', $image->mime_type);
                                    $type = $mimeArray[count($mimeArray)-1];
                                    if($hotel->soap_client == "HO"){
                                        $imgUrl = "/images/offers/{$image->id}.{$type}";
                                    } else {
                                        $imgUrl = "/images/offers/".$hotel->soap_client."/{$image->id}.{$type}";
                                    }
                                ?>
                                <li><img width="70px" height="70px" src="{{{$imgUrl}}}" alt="" /></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div id="hotel-features" class="tab-container">
                <ul class="tabs">
                    <li class="active"><a href="#hotel-availability" data-toggle="tab">Oferta</a></li>
                    <li><a href="#hotel-description" data-toggle="tab">Informatii</a></li>
                    <li><a href="#hotel-location" data-toggle="tab">Locatie</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade in" id="hotel-description">
                        <div class="long-description">
                            <h2>Despre {{{$hotel->name}}}</h2>
                            <p>
                                {{{$hotel->description}}}
                            </p>
                            <h2>Informatii aditionale</h2>
                            @foreach($hotel->detailedDescriptions as $detailedDescription)
                                <h4>{{{$detailedDescription->label}}}</h4>
                                <?php 
                                    $doc = new DOMDocument();
                                    $doc->loadHTML($detailedDescription->text);
                                    echo $doc->saveHTML();
                                ?>
                            @endforeach
                        </div>
                    </div>
                    <div class="tab-pane fade in active" id="hotel-availability">
                        <div id="descriptionOutput" class="col-md-12">
                            Check In : {{{$check_in}}} | Check Out : {{{$check_out}}} | Numar de camere : {{{$roomsOutput}}} 
                        </div>
                        
                        <div id="pricesOutput" class="col-md-12">
                        @if (count($prices) > 0)
                            @foreach($prices as $package => $packagePrices)
                                <div class="table-wrapper">
                                    <div class="table-row">
                                        <div class="table-cell col-md-4"><strong><h3>Tip Camera</h3></strong></div>
                                        <div class="table-cell col-md-3"><strong><h3>Tip Masa</h3></strong></div>
                                        <div class="table-cell col-md-1"><strong></strong></div>
                                        <div class="table-cell col-md-2"><strong><h3>Pret</h3></strong></div>
                                        <div class="table-cell col-md-2"><strong><h3><center></center></h3></strong></div>
                                    </div>
                                    @foreach($packagePrices as  $roomCategory => $roomCategoryPrices)
                                        @foreach($roomCategoryPrices as  $mealPlan => $mealPlanPrice)
                                            <div class="table-row">
                                                <div class="table-cell col-md-4"><p><strong>{{{$roomCategory}}}</strong></p></div>
                                                <div class="table-cell col-md-3"><p>{{{$mealPlan}}}</p></div>
                                                @if($mealPlanPrice['isBookable'])
                                                <i style="color:green;" class="fa fa-check"></i>
                                                @else
                                                <i class="fa fa-envelope-o"></i>
                                                @endif
                                                <div class="table-cell col-md-2"><span class="price" style="float: left !important;">€{{{($mealPlanPrice['price'])}}}</span></div>
                                                @if($mealPlanPrice['isBookable'] && $mealPlanPrice['isMainSoapClient'])
                                                    <div class="table-cell col-md-2"><center><a href="" onClick="{{{$mealPlanPrice['function']}}}" title="" class="button btn-small full-width text-center">Rezerva</a></center></div>
                                                @else
                                                    <div class="table-cell col-md-2"><center><a href="" onClick="askForOffer({{{$package}}},'{{{$soapClientId}}}','{{{$roomCategory}}}','{{{$mealPlan}}}',{{{$rooms}}},{{{($mealPlanPrice['price'])}}},'{{{$check_in}}}','{{{$check_out}}}')" title="" class="button btn-small full-width text-center">Cere oferta</a></center></div>
                                                @endif
                                            </div>
                                        @endforeach
                                    @endforeach
                                </div>
                            @endforeach
                           
                         @else
                         	<p style="color:red;font-weight: bold;margin-top:20px;">Nu exista oferte disponibile pentru optiunile selectate. Selectati alte date.</p>
                         @endif
                        </div>
                        <div id="legend" class="table-wrapper col-md-12">
                        	@if (count($prices) > 0)
                            <div class="table-row">
                            	<div class="table-cell col-md-4"><i style="color:green;" class="fa fa-2x fa-check"></i>&nbsp;Disponibil</div>
                                <div class="table-cell col-md-4"><i class="fa fa-2x fa-envelope-o"></i>&nbsp;La cerere</div>
                                <div class="table-cell col-md-4"><i class="fa fa-2x fa-times"></i>&nbsp;Indisponibil</div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="tab-pane fade" id="hotel-location">
                        <embed class="col-md-12" height="600px"
                          frameborder="0" style="border:0"
                          src="https://www.google.com/maps/embed/v1/place?key=AIzaSyAJTbbZkMl8CCT8E3r9kxNO3gj8bIWiM0E&q={{{$hotel->name}}}">
                        </embed>
                    </div>
                </div>
            
            </div>
        </div>
        <div class="sidebar col-xs-12 col-sm-6 col-md-4 col-lg-4">
            <article class="detailed-logo" style="padding-top: 0px;">
                <?php
                    if(isset($hotel->images[0])){
                        $image = $hotel->images[0]; 
                        $mimeArray = explode('/', $image->mime_type);
                        $type = $mimeArray[count($mimeArray)-1];
                        if($hotel->soap_client == "HO"){
                            $imgUrl = "/images/offers/{$image->id}.{$type}";
                        } else {
                            $imgUrl = "/images/offers/".$hotel->soap_client."/{$image->id}.{$type}";
                        }
                    } else {
                        $imgUrl = "/images/270x160.png";
                    }
                ?>
                <figure style="padding: 0px;">
                    <img style="height: 180px !important; width: 270px !important;" src="{{{$imgUrl}}}" alt="">
                </figure>
                <div class="details">
                    <div class="feedback clearfix">
                    	@if($hotel->class != 0)
                        <img src="/images/<?php echo $hotel->class; ?>-star.png" alt="stars" />
                    	@endif
                    </div>
                    <p class="description" style="text-align: justify;"> {{{$hotel->description}}} </p>
                    <div class="clearfix">

                        <div class="row">
                            <div class="col-xs-6">
                                <label>Check In</label>
                                <div class="datepicker-wrap">
                                    <input name="searchDepartureDateHotel" value="{{{$check_in}}}" type="text" data-min-date="today" class="input-text full-width" placeholder="Data plecare" id="searchDepartureDateHotel" />
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <label>Check Out</label>
                                <div class="datepicker-wrap">
                                    <input name="searchArrivalDateHotel" value="{{{$check_out}}}" type="text" data-min-date="today" class="input-text full-width" placeholder="Data intoarcere" id="searchArrivalDateHotel" />
                                </div>
                            </div>
                        </div><br/>
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
                            <div class="col-xs-4" id="noAdults-div-1">
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
                            <div class="col-xs-4" id="noKids-div-1">
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
                    <br/><br/>
                    <a id="searchButton" class="button yellow full-width uppercase btn-small">confirma datele</a><br/><br/>
                </div>
            </article>
        </div>
      </div>
    </div>
</div>

<script type="text/javascript">
var rememberedRooms = <?php echo $rooms; ?>;
(function($) {
    $(document).ready(function() {
        var urlSearchAjaxSingleHotelSearch = "/ajax_search/singleHotelSearch";

        $("#searchButton").click(function(){
        	$("#loadingPage").parent().parent().removeClass('pace-inactive');
            $("#loadingBar").hide();
            $("#loadingPage").parent().parent().addClass('pace-active');
            $("#loadingText").html("Se cauta oferte...");
            $("#pricesOutput").html('');
            var departureDateHotelField = $("#searchDepartureDateHotel");
            var arrivalDateHotelField = $("#searchArrivalDateHotel");
            var hotelSearchObject = new Object();
            hotelSearchObject.check_in = departureDateHotelField.val();
            hotelSearchObject.check_out = arrivalDateHotelField.val();
            var date1Array = departureDateHotelField.val().split("/");
            var date2Array = arrivalDateHotelField.val().split("/");
            var date1 = new Date(parseInt(date1Array[2]),parseInt(date1Array[1]),parseInt(date1Array[0]));
            var date2 = new Date(parseInt(date2Array[2]),parseInt(date2Array[1]),parseInt(date2Array[0]));
            var timeDiff = Math.abs(date2.getTime() - date1.getTime());
            hotelSearchObject.stay = Math.ceil(timeDiff / (1000*3600*24));
            var noRooms = $("#noRooms").val();
            var Rooms = [];
            var roomsOutput = "";
            roomsOutput += noRooms + " (";
            for(var i = 0; i < noRooms; i++){
                Rooms[i] = new Object();
                Rooms[i].adults = $("#noAdults-"+(i+1)).val();
                if(Rooms[i].adults == 1){
                    roomsOutput += "1 adult";
                } else  {
                    roomsOutput += Rooms[i].adults+" adulti";
                }
                Rooms[i].kids = [];
                var noKids = $("#noKids-"+(i+1)).val();
                for(var j = 0; j < noKids; j++){
                    Rooms[i].kids[j] = $("#noKids-"+(i+1)+"-c"+(j+1)).val();
                }
                if(Rooms[i].kids.length > 0){
                    if(Rooms[i].kids.length == 1){
                        if(i == noRooms - 1){
                            roomsOutput += " si 1 copil)";
                        } else {
                            roomsOutput += " si 1 copil )";
                        }
                        
                    } else {
                        if(i == noRooms - 1){
                            roomsOutput += " si "+Rooms[i].kids.length+" copii)";
                        } else {
                            roomsOutput += " si "+Rooms[i].kids.length+" copii / ";
                        }
                    }
                } else {
                    if(i == noRooms - 1){
                        roomsOutput += ")";
                    } else {
                        roomsOutput += " / ";
                    }
                }
            }
            hotelSearchObject.hotel = {{{$hotel->id}}};
            hotelSearchObject.destination = {{{$hotelSearchCached->destination}}};
            hotelSearchObject.rooms = Rooms;
            hotelSearchObject.soap_client = "{{{$hotel->soap_client}}}";
            $("#descriptionOutput").html("Check In : "+departureDateHotelField.val()+" | Check Out : "+arrivalDateHotelField.val()+" | Numar de camere : "+roomsOutput);   
            
            $.get(urlSearchAjaxSingleHotelSearch,{ hotelSearch: hotelSearchObject },function(data){
                $("#loadingPage").parent().parent().addClass('pace-inactive');
            	$("#loadingPage").parent().parent().removeClass('pace-active');
                rememberedRooms = Rooms;
                var availablePackages = false;
                var jsonData = $.parseJSON(data);
                prices = jsonData.prices;
                $("#pricesOutput").html("");
                var appendedText = "";
                $.each(prices , function(packageKey,packages) {
                        availablePackages = true;
                        appendedText = "";
                        appendedText = '<div class="table-row">'+
                                            '<div class="table-cell col-md-4"><strong><h3>Tip Camera</h3></strong></div>'+
                                            '<div class="table-cell col-md-3"><strong><h3>Tip Masa</h3></strong></div>'+
                                            '<div class="table-cell col-md-1"><strong><h3><center></center></h3></strong></div>'+
                                            '<div class="table-cell col-md-2"><strong><h3>Pret</h3></strong></div>'+
                                            '<div class="table-cell col-md-2"><strong><h3><center></center></h3></strong></div>'+
                                            '</div>';
                        $.each(packages, function(priceKey,price){
                        	var text = '<div class="table-row">'+
    	                                '<div class="table-cell col-md-4"><p><strong>'+price.roomCategory+'</strong></p></div>'+
    	                                '<div class="table-cell col-md-3"><p>'+price.mealPlan+'</p></div>'+
    	                                '<div class="table-cell col-md-1">';
                            if(price.isBookable){
                                text += '<i style="color:green;" class="fa fa-check"></i>';
                            } else {
                                text += '<i class="fa fa-envelope-o"></i>';
                            }
                            
                			text += '</div>'+
                                    '<div class="table-cell col-md-2"><span class="price" style="float: left !important;">€'+price.price+'</span></div>';
                            if(price.isBookable && price.isMainSoapClient){
                                text += '<div class="table-cell col-md-2"><center><a href="" onClick="'+price.onClickFunction+'" title="" class="button btn-small full-width text-center">Rezerva</a></center></div>';
                            } else {
                                text += '<div class="table-cell col-md-2"><center><a href="" onClick=\''+price.askForOfferFunction+'\' title="" class="button btn-small full-width text-center">Cere oferta</a></center></div>';
                            }
                            text += '</div>';
                            appendedText += text;
                        });
                        $("#pricesOutput").append("<div class='table-wrapper'>"+appendedText+"</div>");
                });
                if(availablePackages){
                	$('#legend').html('<div class="table-row"><div class="table-cell col-md-4"><i style="color:green;" class="fa fa-2x fa-check"></i>&nbsp;Disponibil</div><div class="table-cell col-md-4"><i class="fa fa-2x fa-envelope-o"></i>&nbsp;La cerere</div><div class="table-cell col-md-4"><i class="fa fa-2x fa-times"></i>&nbsp;Indisponibil</div></div>');
                } else {
                	$('#pricesOutput').html('<p style="color:red;font-weight: bold;margin-top:20px;">Nu exista oferte disponibile pentru optiunile selectate. Selectati alte date.</p>');
                	$('#legend').html('');
                }
                
            });

        });
        
        
    });    

})(jQuery);

function askForOffer(hotelId,soapClient,roomCategory,mealPlan,rooms,price,departureDate,returnDate){
    event.preventDefault();
    $ = jQuery;
    var urlAskForOffer = "/ajax_search/askForOffer";
    offerObject = new Object();
    offerObject.offerType = "HOTEL";
    offerObject.hotelId = hotelId;
    offerObject.soapClient = soapClient;
    offerObject.roomCategory = roomCategory;
    offerObject.mealPlan = mealPlan;
    offerObject.rooms = rooms;
    offerObject.price = price;
    departureDateArray = departureDate.split('/');
    departureDate = departureDateArray[2]+"-"+departureDateArray[1]+"-"+departureDateArray[0];
    returnDateArray = returnDate.split('/');
    returnDate = returnDateArray[2]+"-"+returnDateArray[1]+"-"+returnDateArray[0];
    offerObject.departureDate = departureDate;
    offerObject.returnDate = returnDate;
    $.get(urlAskForOffer,{ offer: offerObject},function(response){
        window.location.replace("/cere_oferta/ref"+response);
    });
    event.stopPropagation();
}

function book(hotelId,categoryId,mealPlanLabel,price,event){
	event.preventDefault();    
    $ = jQuery;
    var urlSearchAjaxSingleHotelSearchBeforeBooking = "/ajax_search/singleHotelSearchBeforeBooking";
    $("#loadingPage").parent().parent().removeClass('pace-inactive');
    $("#loadingBar").hide();
    $("#loadingPage").parent().parent().addClass('pace-active');
    $("#loadingText").html("Se verifica disponibilitatea si pretul..");
    $("#pricesOutput").html('');
    var departureDateHotelField = $("#searchDepartureDateHotel");
    var arrivalDateHotelField = $("#searchArrivalDateHotel");
    var hotelSearchObject = new Object();
    hotelSearchObject.check_in = departureDateHotelField.val();
    hotelSearchObject.check_out = arrivalDateHotelField.val();
    var date1Array = departureDateHotelField.val().split("/");
    var date2Array = arrivalDateHotelField.val().split("/");
    var date1 = new Date(parseInt(date1Array[2]),parseInt(date1Array[1]),parseInt(date1Array[0]));
    var date2 = new Date(parseInt(date2Array[2]),parseInt(date2Array[1]),parseInt(date2Array[0]));
    var timeDiff = Math.abs(date2.getTime() - date1.getTime());
    hotelSearchObject.stay = Math.ceil(timeDiff / (1000*3600*24));
    var noRooms = $("#noRooms").val();
    var Rooms = [];
    var roomsOutput = "";
    roomsOutput += noRooms + " (";
    for(var i = 0; i < noRooms; i++){
        Rooms[i] = new Object();
        Rooms[i].adults = $("#noAdults-"+(i+1)).val();
        if(Rooms[i].adults == 1){
            roomsOutput += "1 adult";
        } else  {
            roomsOutput += Rooms[i].adults+" adulti";
        }
        Rooms[i].kids = [];
        var noKids = $("#noKids-"+(i+1)).val();
        for(var j = 0; j < noKids; j++){
            Rooms[i].kids[j] = $("#noKids-"+(i+1)+"-c"+(j+1)).val();
        }
        if(Rooms[i].kids.length > 0){
            if(Rooms[i].kids.length == 1){
                if(i == noRooms - 1){
                    roomsOutput += " si 1 copil)";
                } else {
                    roomsOutput += " si 1 copil )";
                }
                
            } else {
                if(i == noRooms - 1){
                    roomsOutput += " si "+Rooms[i].kids.length+" copii)";
                } else {
                    roomsOutput += " si "+Rooms[i].kids.length+" copii / ";
                }
            }
        } else {
            if(i == noRooms - 1){
                roomsOutput += ")";
            } else {
                roomsOutput += " / ";
            }
        }
    }
    hotelSearchObject.hotel = {{{$hotel->id}}};
    hotelSearchObject.destination = {{{$hotelSearchCached->destination}}};
    hotelSearchObject.rooms = Rooms;
    var oldPriceInfo = new Object();
    oldPriceInfo.hotelId = hotelId;
    oldPriceInfo.categoryId = categoryId;
    oldPriceInfo.mealPlanLabel = mealPlanLabel;
    oldPriceInfo.price = price;
    $.get(urlSearchAjaxSingleHotelSearchBeforeBooking,{ hotelSearch: hotelSearchObject , hotelId: hotelId, oldPriceInfo: oldPriceInfo},function(response){
        var response = $.parseJSON(response);
        if(response.status){
            window.location.replace("/rezerva/hotel/ref"+response.id);
        } else {
            var prices = response.prices;
            var availablePackages = false;
            $("#pricesOutput").html("");
            $.each(prices , function(packageKey,packages) {
                availablePackages = true;
                appendedText = "";
                appendedText = '<div class="table-row">'+
                                    '<div class="table-cell col-md-4"><strong><h3>Tip Camera</h3></strong></div>'+
                                    '<div class="table-cell col-md-3"><strong><h3>Tip Masa</h3></strong></div>'+
                                    '<div class="table-cell col-md-1"><strong><h3><center></center></h3></strong></div>'+
                                    '<div class="table-cell col-md-2"><strong><h3>Pret</h3></strong></div>'+
                                    '<div class="table-cell col-md-2"><strong><h3><center></center></h3></strong></div>'+
                                    '</div>';
                $.each(packages, function(priceKey,price){
                    var text = '<div class="table-row">'+
                                '<div class="table-cell col-md-4"><p><strong>'+price.roomCategory+'</strong></p></div>'+
                                '<div class="table-cell col-md-3"><p>'+price.mealPlan+'</p></div>'+
                                '<div class="table-cell col-md-1">';
                    if(price.isBookable){
                        text += '<i style="color:green;" class="fa fa-check"></i>';
                    } else {
                        text += '<i class="fa fa-envelope-o"></i>';
                    }
                    
                    text += '</div>'+
                            '<div class="table-cell col-md-2"><span class="price" style="float: left !important;">€'+price.price+'</span></div>';
                    if(price.isBookable){
                        text += '<div class="table-cell col-md-2"><center><a href="" onClick="'+price.onClickFunction+'" title="" class="button btn-small full-width text-center">Rezerva</a></center></div>';
                    } else {
                        text += '<div class="table-cell col-md-2"><center><a href="#" onClick=\''+price.askForOfferFunction+'\' class="button btn-small full-width text-center">Cere oferta</a></center></div>';
                    }
                    text += '</div>';
                    appendedText += text;
                });
                $("#pricesOutput").append("<div class='table-wrapper'>"+appendedText+"</div>");
            });
            if(availablePackages){
                $('#legend').html('<div class="table-row"><div class="table-cell col-md-4"><i style="color:green;" class="fa fa-2x fa-check"></i>&nbsp;Disponibil</div><div class="table-cell col-md-4"><i class="fa fa-2x fa-envelope-o"></i>&nbsp;La cerere</div><div class="table-cell col-md-4"><i class="fa fa-2x fa-times"></i>&nbsp;Indisponibil</div></div>');
            } else {
                $('#pricesOutput').html('<p style="color:red;font-weight: bold;margin-top:20px;">Nu exista oferte disponibile pentru optiunile selectate. Selectati alte date.</p>');
                $('#legend').html('');
            }
			$("#loadingPage").parent().parent().addClass('pace-inactive');
            $("#loadingPage").parent().parent().removeClass('pace-active');
        }
    });
   event.stopPropagation();
};
</script>



@stop