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
									  	 ->get(array('id', 'title', 'image'))
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

	/*
	 * Display service derail
	 * params (int) $id
	 */
	public function getServiceDetail($id = null) {
		try {
			$service = array();
			$serviceCmd = \SpaService::find($id);
			
			//set views
			if(\ViewsAdder::views_cookie('service', $id)) {
				$serviceCmd->views += 1; 
				$serviceCmd->save();
			}

			if($serviceCmd)
				$service = $serviceCmd->toArray();

			$categorysCmd = \SpaService::where('_parent', 'N') 
									->get(array('id', 'title'))
									->toArray();
			$categorys = array();
			if($categorysCmd){
				foreach ($categorysCmd as $category) {
					$servCmd = \SpaService::where('_parent', $category['id'])
										  ->where('display', 'yes')
										  ->get(array('id', 'title'))
			  	 						  ->toArray();
			  	 	$categorys[] = array(
			  	 		'cat' => $category,
			  	 		'serv' => $servCmd
			  	 	);
				}
			}
			
			$hotServices = array();
			$hotServicesCmd = \SpaService::where('_parent', '<>', 'N')
										 ->where('display', 'yes')
										 ->orderBy('views', 'DESC')
										 ->skip(0)
										 ->take(4)
										 ->get(array('id', 'title', 'image', 'image_desc'))
										 ->toArray();
			if($hotServicesCmd)
				$hotServices = $hotServicesCmd;
			
			$serviceDetailURL = \URL::route('spa.service.detail');

			return \View::make('spa.service.view_service_detail', array(
				'service' => $service,
				'categorys' => $categorys,
				'hotServices' => $hotServices,
				'serviceDetailURL' => $serviceDetailURL
			));
		} catch (Exception $e) {
			echo $e->getMessage();
			exit;
		}
	}
}

?>