<?php
namespace spa;

class ServiceController extends \BaseController{

	/*
	 * Display service
	 */
	public function getService() {
		try {
			$serviceCats = array();
			$serviceCatsCmd = \SpaService::where('_parent', 'N')
									  	 ->get(array('id', 'title'))
									  	 ->toArray();
			$services = array();
			if ($serviceCatsCmd) {
				$row = 0;
				foreach ($serviceCatsCmd as $key => $serviceCatCmd) {
					
					$servCmd = \SpaService::where('_parent', $serviceCatCmd['id'])
										  ->where('display', 'yes')
										  ->get(array('id', 'title', 'image'))
			  	 						  ->toArray();
					$serviceCats[$row][] = array(
						'cat'=>$serviceCatCmd,
						'serv'=>$servCmd
					);
					if ( ($key+1)%4 == 0){
						$row+=1;
					}
				}
			}

			$detailURL = \URL::route('spa.service.detail');

			return \View::make('spa.service.view_service', array(
				"serviceCats" => $serviceCats,
				"services" => $services,
				"detailURL" => $detailURL
			));
		} catch (Exception $e) {
			echo $e->getMessage();
			exit;
		}
	}

	public function getServiceDetail($id = null) {
		try {
			return \View::make('spa.service.view_service_detail');
		} catch (Exception $e) {
			echo $e->getMessage();
			exit;
		}
	}
}

?>