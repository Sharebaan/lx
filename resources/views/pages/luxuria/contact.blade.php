<div class="container static_page_content">
	<div class="row">
		<div class="col-sm-6 col-xs-6">
			<div class="contact_text">
				<h4>Contacteaza-ne</h4>
				<p><strong>Adresa: </strong>Str. Epocii, nr. 3A, parter, BRAGADIRU - ILFOV, Romania</p>
				<p><strong>Telefon: </strong>0734 489 107</p>
				<p><strong>Telefon urgente: </strong>0734 489 107</p>
				<p><strong>Telefon/Fax: </strong>0311 041 253</p>
			</div>
				<h4>Trimite un mesaj</h4>
				@if(Session::has('message'))
							   {!! Session::get('message') !!}
						@endif

						<ul class="parsley-error-list">
							@foreach($errors->all() as $error)
								<li>{{ $error }}</li>
							@endforeach
						</ul>

	                   {!! Form::open(array('url'=>'home/contact', 'class'=>'form','parsley-validate'=>'','novalidate'=>' ')) !!}
	                        <div class="form-group name">
	                              {!! Form::text('name', null,array('class'=>'form-control', 'placeholder'=>'Nume', 'required'=>'true'  )) !!}
	                        </div><!--//form-group-->
	                        <div class="form-group email">
	                             {!! Form::text('sender', null,array('class'=>'form-control', 'placeholder'=>'Email', 'required'=>'true'  )) !!}
	                        </div><!--//form-group-->

	                        <div class="form-group email">
	                              {!! Form::text('subject', null,array('class'=>'form-control', 'placeholder'=>'Telefon', 'required'=>'true email'   )) !!}
	                        </div><!--//form-group-->

	                        <div class="form-group message">
	                            {!! Form::textarea('message',null,array('class'=>'form-control', 'placeholder'=>'Mesaj', 'required'=>'true'   )) !!}
	                        </div><!--//form-group-->
	                        <button class="blue_yellow" type="submit">Trimite</button>
	                        <input name="redirect" value="contact-us" type="hidden">
	                    {!! Form::close() !!}<!--//form-->
			</div>
			<div class="col-sm-6 col-xs-6">
				<h4>Locatie</h4>
				<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2851.4204251804963!2d26.00833371593906!3d44.38349067910298!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40adfffb3bd5e141%3A0x35c198c93c35eb6a!2sStrada+Epocii%2C+Bragadiru+077025!5e0!3m2!1sro!2sro!4v1483016682459" width="100%" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
			</div>
			<div class="clear"></div>
		</div>
</div>
