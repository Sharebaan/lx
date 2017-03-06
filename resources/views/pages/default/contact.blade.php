<div class="wrapper-header ">
    <div class=" container">
		<div class="col-sm-6 col-xs-6">
		  <div class="page-title">
			<h3>Contact</h3>
		  </div>
		</div>
		<div class="col-sm-6 col-xs-6 ">
		  <ul class="breadcrumb pull-right">
			<li><a href="{{ URL::to('') }}">Home</a></li>
			<li class="active">Contact</li>
		  </ul>
		</div>

    </div>
</div>
<div class="container static_page_content">
	<div class="row">
		<div class="col-sm-6 col-xs-6">
			<div class="contact_text">
				<h4>Contacteaza-ne</h4>
				<p><strong>Adresa: </strong>Str. Matei Basarab, nr. 100, Bl. 85, Ap. 48, Sector 3 Bucuresti</p>
				<p><strong>Telefon: </strong>031 801 90 10 / 031 804 58 07</p>
				<p><strong>Fax: </strong>031 801 90 10</p>
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
				<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2849.088633224716!2d26.12957251518125!3d44.43134407910228!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40b1fed822d84a49%3A0x2acebc812484214e!2sStrada+Matei+Basarab+100%2C+Bucure%C8%99ti!5e0!3m2!1sro!2sro!4v1459242379225" width="100%" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
			</div>
			<div class="clear"></div>
		</div>
</div>
