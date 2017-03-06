<?php
class ErrorsController extends BaseController {

	public function showError($errorNumber){
		switch($errorNumber){
			case "order_expired":
				$data['errorMessage'] = "Sesiunea a expirat, va rugam sa refaceti rezervarea!";
				$data['errorNumber'] = "404";
				$data['errorUrl'] = Session::get('message');
			break;
			case "ask_for_offer_expired":
				$data['errorMessage'] = "Oferta a expirat, va rugam sa refaceti cererea!";
				$data['errorNumber'] = "404";
				$data['errorUrl'] = Session::get('message');
			break;
			default:
				$data['errorMessage'] = "Aceasta pagina nu exista.";
				$data['errorNumber'] = "404";
				$data['errorUrl'] = URL::previous();
		}
		return View::make('error',$data);
	}

}
