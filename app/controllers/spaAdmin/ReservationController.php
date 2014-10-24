<?php
namespace spaAdmin;

/*
 * this controller is used to handle all request of spa reservation
 */
class ReservationController extends \BaseController{

	/*
	 * Display reservation list page
	 */
	public function getReservationList(){
		try {
			$page = \Input::get( 'page',1);
			$limit = 10;
			$offset = ($page-1) * $limit;

			$reseCmd = \SpaReservation::orderBy('updated_at','DESC');

			$rowsNum = $reseCmd->count();

			$reservations = $reseCmd->skip($offset)
									->take($limit)
									->get();

			$styleArray = array('電話 : ', 'Line ID : ', 'WeChat : ', 'QQ : ');
			$sexArray = array(
				'male'=>'男',
				'women'=>'女'
			);
			$contactTimeArray = array(
				'morning'=>'早上',
				'noon'=>'中午',
				'afternoon'=>'下午',
				'night'=>'晚上',
			);

			//process contact
			$contactArray = array();
			if (!empty($reservations)) {
				foreach ($reservations as $key => $reservation) {
					$data = json_decode($reservation->contact);
					$contactArray[$key] = array(
						'data'=>$data,
						'count'=>count($data)
					);
				}
			}
			
			$detailsURL = \URL::route('spa.admin.reservation.details');
			$deleteURL = \URL::route('spa.admin.reservation.delete');
			$actionURL = \URL::route('spa.admin.reservation.action');
			$manyDeleteURL = \URL::route('spa.admin.reservation.manyDelete');

			$widgetParam = array(
				'currPage' => $page,
				'total' => $rowsNum,
				'perPage' => $limit,
				'URL' => null,
				'route' => 'spa.admin.reservation.list'
			);

			return \View::make('spa_admin.reservation.view_list', array(
				'reservations'=>&$reservations,
				'styleArray'=>&$styleArray,
				'sexArray'=>&$sexArray,
				'contactTimeArray'=>&$contactTimeArray,
				'contactArray'=>&$contactArray,
				'detailsURL'=>$detailsURL,
				'pagerParam' => &$widgetParam,
				'deleteURL'=>$deleteURL,
				'actionURL'=>$actionURL,
				'manyDeleteURL' => $manyDeleteURL
			));
		} catch (Exception $e) {
			echo $e->getMessage();
			return;
		}
	}

	/*
	 * AJAX request for reservation details
	 */
	public function postReservationDetails(){
		try {
			$id = \Input::get('id');

			$reservation = \SpaReservation::find($id);

			$styleArray = array('電話 : ', 'Line ID : ', 'WeChat : ', 'QQ : ');
			$contactArray = array(
				'phone'=>'電話',
				'line'=>'Line',
				'wechat'=>'WeChat',
				'qq'=>'QQ',
				);
			$contactTimeArray = array(
				'morning'=>'早上',
				'noon'=>'中午',
				'afternoon'=>'下午',
				'night'=>'晚上',
				);

			//process contact
			$contact = array();
			if (!empty($reservation)) {
				$data = json_decode($reservation->contact);
				$contact = array('data'=>$data, 'count'=>count($data));
			}
			$contact_time = "";
			if(!empty($reservation->contact_time))
				$contact_time = $contactTimeArray[$reservation->contact_time];
			$reservationArray = array(
				"data"=>array(
					"name" => $reservation->name,
					"country" => $reservation->country,
					"styleArray" => $styleArray,
					"contact" => $contact,
					"contact_time" => $contact_time,
					"birthday" => $reservation->birthday,
					"email" => $reservation->email,
					"stay_start_date" => $reservation->stay_start_date,
					"stay_exit_date" => $reservation->stay_exit_date,
					"service_date" => $reservation->service_date,
					"improve_item" => $reservation->improve_item,
					"other_notes" => $reservation->other_notes,
					"created_at" => $reservation->created_at,
				)
			);

			return \Response::json(array(
				'status' => 'ok',
				'dataStr'=>&$reservationArray
			));
		} catch (Exception $e) {
			echo $e->getMessage();
			return;
		}
	}

	/*
	 * AJAX request for delete reservation
	 */
	public function postDeleteReservation(){
		try {
			$id = \Input::get('id');

			$reservation = \SpaReservation::find($id);
			$reservation->delete();

			return \Response::json(array(
				'status' => 'ok',
				'message'=>"刪除完成"
			));
		} catch (Exception $e) {
			return \Response::json(array(
				'status' => 'error',
				'message' => $e->getMessage()
			));
		}
	}

	/*
	 * Display reservation action page
	 * @params (string) $id
	 */
	public function getReservationAction($id=null){
		$action = "create";
		try {
			$writeURL = \URL::route('spa.admin.reservation.write');

			//edit date
			$reservation = array();
			$contact = array();
			$dateTimeArray = array();
			if(!empty($id)){
				$action = 'edit';
				$reservation = \SpaReservation::find($id);
				//process contact
				$contact = array();
				if (!empty($reservation)) {
					$contact = json_decode($reservation->contact);
				}
				//process date
				$dateTimeArray['stay_start_date'] = date("m/d/Y h:i:s A", strtotime($reservation->stay_start_date));
				$dateTimeArray['stay_exit_date'] = date("m/d/Y h:i:s A", strtotime($reservation->stay_exit_date));
				$dateTimeArray['service_date'] = date("m/d/Y h:i:s A", strtotime($reservation->service_date));

				$writeURL .= "/".$id;
			}

			return \View::make('spa_admin.reservation.view_action',array(
				'writeURL'=>$writeURL,
				'action'=>$action,
				'reservation'=>&$reservation,
				'contact'=>&$contact,
				'dateTimeArray'=>&$dateTimeArray
				));
			
		} catch (Exception $e) {
			echo $e->getMessage();
			return;
		}
	}

	/*
     * Write(create/edit action) reservation data.
     * @params (string) $id
     */
	public function postReservationWrite($id=null){
		try {
			$action = \Input::get('action');
			if($action == 'create')
				$reservation = new \SpaReservation;
			else
				$reservation = \SpaReservation::find($id);

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

			return \Redirect::route("spa.admin.reservation.list");
		} catch (Exception $e) {
			echo $e->getMessage();
			return;
		}
	}

	/*
     * AJAX mony delete reservation
     */
	public function postManyDelete() {
		try {
			$deleteRes = \Input::get('deleteRes');

			\SpaReservation::whereIn('id', $deleteRes)
						   ->delete();

			return \Response::json(array(
				'status' => 'ok',
				'message' => '刪除完成'
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
