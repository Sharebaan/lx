<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
      <title>Underscore-Responsive Email Template</title>
      <style type="text/css">
         /* Client-specific Styles */
         #outlook a {padding:0;} /* Force Outlook to provide a "view in browser" menu link. */
         body{width:100% !important; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; margin:0; padding:0;}
         /* Prevent Webkit and Windows Mobile platforms from changing default font sizes, while not breaking desktop design. */
         .ExternalClass {width:100%;} /* Force Hotmail to display emails at full width */
         .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;} /* Force Hotmail to display normal line spacing. */
         #backgroundTable {margin:0; padding:0; width:100% !important; line-height: 100% !important;}
         img {outline:none; text-decoration:none;border:none; -ms-interpolation-mode: bicubic;}
         a img {border:none;}
         .image_fix {display:block;}
         p {margin: 0px 0px !important;}
         table td {border-collapse: collapse;}
         table { border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; }
         a {color: #33b9ff;text-decoration: none;text-decoration:none!important;}
         /*STYLES*/
         table[class=full] { width: 100%; clear: both; }
         /*IPAD STYLES*/
         @media only screen and (max-width: 640px) {
         a[href^="tel"], a[href^="sms"] {
         text-decoration: none;
         color: #0a8cce; /* or whatever your want */
         pointer-events: none;
         cursor: default;
         }
         .mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
         text-decoration: default;
         color: #0a8cce !important;
         pointer-events: auto;
         cursor: default;
         }
         table[class=devicewidth] {width: 440px!important;text-align:center!important;}
         table[class=devicewidthmob] {width: 420px!important;text-align:center!important;}
         table[class=devicewidthinner] {width: 420px!important;text-align:center!important;}
         img[class=banner] {width: 440px!important;height:157px!important;}
         img[class=col2img] {width: 440px!important;height:330px!important;}
         table[class="cols3inner"] {width: 100px!important;}
         table[class="col3img"] {width: 131px!important;}
         img[class="col3img"] {width: 131px!important;height: 82px!important;}
         table[class='removeMobile']{width:10px!important;}
         img[class="blog"] {width: 420px!important;height: 162px!important;}
         }

         /*IPHONE STYLES*/
         @media only screen and (max-width: 480px) {
         a[href^="tel"], a[href^="sms"] {
         text-decoration: none;
         color: #0a8cce; /* or whatever your want */
         pointer-events: none;
         cursor: default;
         }
         .mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
         text-decoration: default;
         color: #0a8cce !important;
         pointer-events: auto;
         cursor: default;
         }
         table[class=devicewidth] {width: 280px!important;text-align:center!important;}
         table[class=devicewidthmob] {width: 260px!important;text-align:center!important;}
         table[class=devicewidthinner] {width: 260px!important;text-align:center!important;}
         img[class=banner] {width: 280px!important;height:100px!important;}
         img[class=col2img] {width: 280px!important;height:210px!important;}
         table[class="cols3inner"] {width: 260px!important;}
         img[class="col3img"] {width: 280px!important;height: 175px!important;}
         table[class="col3img"] {width: 280px!important;}
         img[class="blog"] {width: 260px!important;height: 100px!important;}
         td[class="padding-top-right15"]{padding:15px 15px 0 0 !important;}
         td[class="padding-right15"]{padding-right:15px !important;}
         }
		table.gridtable {
			color:#000000;
			border-width: 1px;
			border-color: #666666;
			border-collapse: collapse;
		}
		table.gridtable th {
			border-width: 1px;
			padding: 8px;
			border-style: solid;
			border-color: #666666;
			background-color: #ffffff;
		}
		table.gridtable td {
			border-width: 1px;
			padding: 8px;
			border-style: solid;
			border-color: #666666;
			background-color: #ffffff;
		}
      </style>
   </head>
   <body bgcolor="#ffffff">
<!-- Start of header -->
<table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="header">
   <tbody>
      <tr>
         <td>
            <table width="560" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
               <tbody>
                  <tr>
                     <td width="100%">
                        <table bgcolor="#FFF" width="560" cellpadding="0" cellspacing="0" border="0" align="center" style="border-top-left-radius:5px;border-top-right-radius:5px;" class="devicewidth">
                           <tbody>
                              <!-- Spacing -->
                              <tr>
                                 <td height="10" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                              </tr>
                              <!-- Spacing -->
                              <tr>
                                 <td>
                                    <!-- logo -->
                                    <table align="center" border="0" cellpadding="0" cellspacing="0">
                                       <tbody>
                                          <tr>
                                             <td width="20"></td>
                                             <td align="center">
                                                <div class="imgpop">
                                                   <a target="" href="#">
                                                   <center><img src="/images/logo_mini.jpg" alt="" border="0"  style="display:block; border:none; outline:none; text-decoration:none;"></center>
                                                   </a>
                                                </div>
                                             </td>
                                          </tr>
                                       </tbody>
                                    </table>
                                    <!-- end of logo -->
                                 </td>
                              </tr>
                              <!-- Spacing -->
                              <tr>
                                 <td height="10" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                              </tr>
                              <!-- Spacing -->
                           </tbody>
                        </table>
                     </td>
                  </tr>
               </tbody>
            </table>
         </td>
      </tr>
   </tbody>
</table>
<!-- End of Header -->
<!-- fulltext -->
<table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="left-image">
   <tbody>
      <tr>
         <td>
            <table width="560" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
               <tbody>
                  <tr>
                     <td width="100%">
                        <table bgcolor="#ffffff" width="560" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                           <tbody>
                              <!-- Spacing -->
                              <tr>
                                 <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                              </tr>
                              <!-- Spacing -->
                              <tr>
                                 <td>
                                    <table width="520" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidthinner">
                                       <tbody>
                                          <tr>
                                             <td style="font-family: Helvetica, arial, sans-serif; font-size: 18px; color: #2d2a26; text-align:left; line-height: 24px;">
                                                Rezervarea #{{{$booking->Reference}}}
                                             </td>
                                          </tr>
                                          <!-- Spacing -->
                                          <tr>
                                             <td width="100%" height="15" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                          </tr>
                                          <!-- /Spacing -->
                                          <!-- content -->
                                          <tr>
                                             <td style="font-family: Helvetica, arial, sans-serif; font-size: 14px; color: #7a6e67; text-align:left; line-height: 24px;">
                                                @if($type == 2)
                                                O noua rezervare a fost facuta. Se asteapta confirmarea platii online. Mai jos aveti detaliile rezervarii :
                                                @else
                                                O noua rezervare a fost facuta. Rezervarea este valabila pentru 24 de ore, plata realizandu-se la sediu. Mai jos aveti detaliile rezervarii :
                                                @endif
                                                <br/>
                                                <br/>
                                                <table class="gridtable" width="100%">
												<tr>
													<th width="35%">Numar rezervare</th>
													<td>{{{$booking->Reference}}}</td>
												</tr>
												<tr>
													<th width="35%">Nume</th>
													<td>{{{$bookingInput['payment-lname']}}}</td>
												</tr>
												<tr>
													<th width="35%">Prenume</th>
													<td>{{{$bookingInput['payment-fname']}}}</td>
												</tr>
												<tr>
													<th width="35%">Adresa de email</th>
													<td>{{{$bookingInput['payment-email']}}}</td>
												</tr>
												<tr>
													<th width="35%">Telefon</th>
													<td>{{{$bookingInput['payment-phone']}}}</td>
												</tr>
												<tr>
													<th width="35%">Adresa</th>
													<td>{{{$bookingInput['payment-address']}}}</td>
												</tr>
												<tr>
													<th width="35%">Locatie</th>
													<td>{{{$bookingInput['payment-city']}}}, {{{$bookingInput['payment-zone']}}}, {{{$bookingInput['payment-country']}}}</td>
												</tr>
												@if(isset($bookingInput['options-ff']))
												<tr>
													<th width="35%">Nume firma</th>
													<td>{{{$bookingInput['company-name']}}}</td>
												</tr>
												<tr>
													<th width="35%">Adresa firma</th>
													<td>{{{$bookingInput['company-address']}}}</td>
												</tr>
												<tr>
													<th width="35%">Locatie firma</th>
													<td>{{{$bookingInput['company-city']}}}, {{{$bookingInput['company-zone']}}}, {{{$bookingInput['company-country']}}}</td>
												</tr>
												<tr>
													<th width="35%">Numar registrul comertului</th>
													<td>{{{$bookingInput['company-nrc']}}}</td>
												</tr>
												<tr>
													<th width="35%">CUI</th>
													<td>{{{$bookingInput['company-cui']}}}</td>
												</tr>
												<tr>
													<th width="35%">Cont bancar</th>
													<td>{{{$bookingInput['company-bank-account']}}}</td>
												</tr>
												<tr>
													<th width="35%">Banca</th>
													<td>{{{$bookingInput['company-bank']}}}</td>
												</tr>
												@endif
												<tr>
													<th width="35%">Tipul platii</th>
													<td style="color:red !important;">
														@if($type == 2)
															Online
														@else
															La sediu
														@endif
													</td>
												</tr>
												@if($type == 2)
												<tr>
													<th width="35%">Numarul tranzactiei in Mobilpay</th>
													<td style="color:red !important;">{{{$mobilpayOrderId}}}</td>
												</tr>
												@endif
												<tr>
													<th width="35%">Total de plata</th>
													<td style="color:red !important;">&euro; {{{$booking->Price->Gross + $booking->Price->Tax}}}</td>
												</tr>
												</table>
                                             </td>
                                          </tr>
                                          <!-- end of content -->
                                       </tbody>
                                    </table>
                                 </td>
                              </tr>
                              <!-- Spacing -->
                              <tr>
                                 <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                              </tr>
                              <!-- Spacing -->
                              <!-- Spacing -->
                              <tr>
                                 <td height="5" bgcolor="#fff" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                              </tr>
                              <!-- Spacing -->
                           </tbody>
                        </table>
                     </td>
                  </tr>
               </tbody>
            </table>
         </td>
      </tr>
   </tbody>
</table>
<!-- end of fulltext -->
<!-- Start of footer -->
<table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="footer">
   <tbody>
      <tr>
         <td>
            <table width="560" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
               <tbody>
                  <tr>
                     <td width="100%">
                        <table bgcolor="#fff" width="560" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                           <tbody>
                              <!-- Spacing -->
                              <tr>
                                 <td height="10" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                              </tr>
                              <!-- Spacing -->
                              <tr>
                                 <td>
                                    <!-- logo -->
                                    <table width="194" align="left" border="0" cellpadding="0" cellspacing="0">
                                       <tbody>
                                          <tr>
                                             <td width="20"></td>
                                             <td width="174" height="40" align="left">
                                                <div class="imgpop">
                                                   <a target="_blank" href="">
                                                   <img src="/images/logo_mini.jpg" alt="" border="0"  height="21" style="display:block; border:none; outline:none; text-decoration:none;">
                                                   </a>
                                                </div>
                                             </td>
                                          </tr>
                                       </tbody>
                                    </table>
                                    <!-- end of logo -->
                                    <!-- start of social icons -->
                                    <table width="60" height="40" align="right" vaalign="middle"  border="0" cellpadding="0" cellspacing="0">
                                       <tbody>
                                          <tr>

                                             <td align="left" width="10" style="font-size:1px; line-height:1px;">&nbsp;</td>
                                             <td width="22" height="22" align="right">
                                                <div class="imgpop">
                                                   <a target="_blank" href="">
                                                   <img src="http://holidayoffice.ro/images/fb.png" alt="" border="0" width="22" height="22" style="display:block; border:none; outline:none; text-decoration:none;">
                                                   </a>
                                                </div>
                                             </td>
                                             <td align="left" width="20" style="font-size:1px; line-height:1px;">&nbsp;</td>
                                          </tr>
                                       </tbody>
                                    </table>
                                    <!-- end of social icons -->
                                 </td>
                              </tr>
                              <!-- Spacing -->
                              <tr>
                                 <td height="10" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                              </tr>
                              <!-- Spacing -->
                           </tbody>
                        </table>
                     </td>
                  </tr>
               </tbody>
            </table>
         </td>
      </tr>
   </tbody>
</table>
<!-- End of footer -->

   </body>
   </html>
