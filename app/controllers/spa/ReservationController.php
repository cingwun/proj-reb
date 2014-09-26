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
			$writeURL = \URL::route('spa.reservation.form.write');

			return \View::make('spa.reservation.view_form', array(
				'ovewSeaURL' => $ovewSeaURL,
				'formURL' => $formURL,
				'writeURL' => $writeURL
			));
		} catch (Exception $e) {
			echo $e->getMessage();
			exit;
		}
	}

	/*
     * AJAX request for reservation form
     */
	public function postWriteForm(){
		try {
			$reservation = new \SpaReservation;
			
			$name = trim(\Input::get('name', "")," ");
			$sex = \Input::get('sex', "");
			$country = trim(\Input::get('country', "")," ");
			$birthday = \Input::get('birthday', "");
			$email = trim(\Input::get('email', "")," ");
			$stay_start_date = \Input::get('stay_start_date', "");
			$stay_exit_date = \Input::get('stay_exit_date', "");
			$service_date = \Input::get('service_date', "");
			$contact_time = \Input::get('contact_time', "");
			$improve_item = trim(\Input::get('improve_item', "")," ");
			$other_notes = \Input::get('other_notes', "");

			//process contact
			$phone = trim(\Input::get('phone')," ");
			$line = trim(\Input::get('line', "")," ");
			$wechat = trim(\Input::get('wechat', "")," ");
			$qq = trim(\Input::get('qq', "")," ");
			$contact = array();
			if(!empty($phone))
				$contact[] = $phone;
			if(!empty($line))
				$contact[] = $line;
			if(!empty($wechat))
				$contact[] = $wechat;
			if(!empty($qq))
				$contact[] = $qq;

			$reservation->name = $name;
			$reservation->sex = $sex;
			$reservation->country = $country;
			$reservation->contact = json_encode($contact);
			$reservation->contact_time = $contact_time;
			$reservation->birthday = date("Y-m-d H:i:s", strtotime($birthday));
			$reservation->email = $email;
			$reservation->stay_start_date = date("Y-m-d H:i:s", strtotime($stay_start_date));
			$reservation->stay_exit_date = date("Y-m-d H:i:s", strtotime($stay_exit_date));
			$reservation->service_date = date("Y-m-d H:i:s", strtotime($service_date));
			$reservation->improve_item = $improve_item;
			$reservation->other_notes = $other_notes;
			
			$reservation->save();

			return \Response::json(array(
				'status' => 'ok',
				'message' => '預約成功'
			));
		} catch (Exception $e) {
			return \Response::json(array(
				'status' => 'error',
				'message' => $e->getMessage()
			));
		}
	}
}

?>