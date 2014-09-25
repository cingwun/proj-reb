<?php
namespace spa;

class ReservationController extends \BaseController{

	/*
	 * Display over sea
	 */
	public function getOverSea(){
		try {
			$ovewSeaURL = \URL::route('spa.reservation.overSea');
			$formURL = \URL::route('spa.reservation.form');

			return \View::make('spa.reservation.view_over_sea', array(
				'ovewSeaURL' => $ovewSeaURL,
				'formURL' => $formURL
			));
		} catch (Exception $e) {
			echo $e->getMessage();
			exit;
		}
	}

	/*
	 * Display over sea form
	 */
	public function getForm(){
		try {

			$ovewSeaURL = \URL::route('spa.reservation.overSea');
			$formURL = \URL::route('spa.reservation.form');

			return \View::make('spa.reservation.view_form', array(
				'ovewSeaURL' => $ovewSeaURL,
				'formURL' => $formURL
			));
		} catch (Exception $e) {
			echo $e->getMessage();
			exit;
		}
	}
}

?>