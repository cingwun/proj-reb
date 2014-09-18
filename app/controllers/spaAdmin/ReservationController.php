<?php
namespace spaAdmin;
class ReservationController extends \BaseController{

	/*
	 * Display reservation list page
	 */
	public function getReservationList(){

		$page = \Input::get( 'page',1);
		$limit = 10;
        $offset = ($page-1) * $limit;

		$cmd = \SpaReservation::orderBy('id');

		$rowsNum = $cmd->count();

		$reservation_list = $cmd->skip($offset)
								->take($limit)
								->get();

		$contact_array = array(
			'phone'=>'電話',
			'line'=>'Line',
			'wechat'=>'WeChat',
			'qq'=>'QQ',
			);
		$contact_time_array = array(
			'morning'=>'早上',
			'noon'=>'中午',
			'afternoon'=>'下午',
			'night'=>'晚上',
			);

		$details_url = \URL::route('spa.admin.reservation.details');
		
		$widgetParam = array(
			'currPage' => $page,
			'total' => $rowsNum,
			'perPage' => $limit,
			'URL' => null,
			'route' => 'spa.admin.reservation.list'
			);

		return \View::make('spa_admin.reservation.view_list', array(
			'reservation_list'=>&$reservation_list,
			'contact_array'=>&$contact_array,
			'contact_time_array'=>&$contact_time_array,
			'details_url'=>$details_url,
			'pagerParam' => &$widgetParam
			));
	}

	/*
	 * AJAX request for reservation details
	 */
	public function postReservationDetails(){
		$id = \Input::get('id');

		$reservation = \SpaReservation::find($id);

		$contact_array = array(
			'phone'=>'電話',
			'line'=>'Line',
			'wechat'=>'WeChat',
			'qq'=>'QQ',
			);
		$contact_time_array = array(
			'morning'=>'早上',
			'noon'=>'中午',
			'afternoon'=>'下午',
			'night'=>'晚上',
			);

		$reservation_array = array(
			"data"=>array(
				"name"=>$reservation->name,
				"country"=>$reservation->country,
				"contact_style"=>$contact_array[$reservation->contact_style],
				"contact_content"=>$reservation->contact_content,
				"contact_time"=>$contact_time_array[$reservation->contact_time],
				"birthday"=>$reservation->birthday,
				"email"=>$reservation->email,
				"stay_start_date"=>$reservation->stay_start_date,
				"stay_exit_date"=>$reservation->stay_exit_date,
				"service_date"=>$reservation->service_date,
				"improve_item"=>$reservation->improve_item,
				"other_notes"=>$reservation->other_notes,
				"created_at"=>$reservation->created_at,
				)
			);

		return \Response::json(array(
			'status' => 'ok',
			'data_str'=>&$reservation_array
			));
	}
}

?>
