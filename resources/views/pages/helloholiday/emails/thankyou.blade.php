<!DOCTYPE html>
<html lang="en-US">
     <head>
          <meta charset="utf-8">
     </head>
     <body>
          <dl class="term-description">
               <dt>Numarul rezervarii:</dt><dd>{{{$booking->Reference}}}</dd>
               <dt>Nume:</dt><dd>{{{$bookingInput['payment-lname']}}}</dd>
               <dt>Prenume:</dt><dd>{{{$bookingInput['payment-fname']}}}</dd>
               <dt>Adresa de email:</dt><dd>{{{$bookingInput['payment-email']}}}</dd>
               <dt>Telefon:</dt><dd>{{{$bookingInput['payment-phone']}}}</dd>
               <dt>Adresa:</dt><dd>{{{$bookingInput['payment-address']}}}</dd>
               <dt>Locatie:</dt><dd>{{{$bookingInput['payment-city']}}}, {{{$bookingInput['payment-zone']}}}, {{{$bookingInput['payment-country']}}}</dd>
               @if(isset($bookingInput['options-ff']))
               <dt>Nume firma:</dt><dd>{{{$bookingInput['company-name']}}}</dd>
               <dt>Adresa firma:</dt><dd>{{{$bookingInput['company-address']}}}</dd>
               <dt>Locatie firma:</dt><dd>{{{$bookingInput['company-city']}}}, {{{$bookingInput['company-zone']}}}, {{{$bookingInput['company-country']}}}</dd>
               <dt>Numar registrul comertului:</dt><dd>{{{$bookingInput['company-nrc']}}}</dd>
               <dt>CUI:</dt><dd>{{{$bookingInput['company-cui']}}}</dd>
               <dt>Cont bancar:</dt><dd>{{{$bookingInput['company-bank-account']}}}</dd>
               <dt>Banca:</dt><dd>{{{$bookingInput['company-bank']}}}</dd>
               @endif
               <dt style="font-size: 16px;">Total:</dt><dd style="color:#da2037;font-size: 16px;font-weight: bold;">&euro; {{{$booking->Price->Gross + $booking->Price->Tax}}}</dd>
          </dl>
     </body>
</html>