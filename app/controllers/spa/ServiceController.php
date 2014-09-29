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
										 ->where('display', 'yes')
										 ->where('lang', $this->getLocale())
										 ->orderBy('sort', 'DESC')
									  	 ->get(array('id', 'title', 'image'))
									  	 ->toArray();
			$services = array();
			if ($serviceCatsCmd) {
				$row = 0;
				foreach ($serviceCatsCmd as $key => $serviceCatCmd) {
					
					$servCmd = \SpaService::where('_parent', $serviceCatCmd['id'])
										  ->where('display', 'yes')
										  ->where('lang', $this->getLocale())
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
			$indexURL = \URL::route('spa.index');

			return \View::make('spa.service.view_service', array(
				"serviceCats" => $serviceCats,
				"services" => $services,
				"detailURL" => $detailURL,
				"indexURL" => $indexURL
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
			$serviceCmd = \SpaService::where('id', $id)
									 ->where('lang', $this->getLocale());

			//set views
			if(\ViewsAdder::views_cookie('service', $id)) {
				$serviceCmd = $serviceCmd->first();
            	$serviceCmd->views = $serviceCmd->views + 1;
              	$serviceCmd->save();
            }

			if($serviceCmd->first())
				$service = $serviceCmd->first()
									  ->toArray();


            
			$categorysCmd = \SpaService::where('_parent', 'N') 
									   ->where('display', 'yes')
									   ->where('lang', $this->getLocale())
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
									   	 ->where('lang', $this->getLocale())
									   	 ->where('id', '<>' , $id)
										 ->orderBy('views', 'DESC')
										 ->skip(0)
										 ->take(4)
										 ->get(array('id', 'title', 'image', 'image_desc'))
										 ->toArray();
			if($hotServicesCmd)
				$hotServices = $hotServicesCmd;
			
			$serviceDetailURL = \URL::route('spa.service.detail');
			$indexURL = \URL::route('spa.index');
			$serviceURL = \URL::route('spa.service');

			return \View::make('spa.service.view_service_detail', array(
				'service' => $service,
				'categorys' => $categorys,
				'hotServices' => $hotServices,
				'serviceDetailURL' => $serviceDetailURL,
				'indexURL' => $indexURL,
				'serviceURL' => $serviceURL
			));
		} catch (Exception $e) {
			echo $e->getMessage();
			exit;
		}
	}
}

?>