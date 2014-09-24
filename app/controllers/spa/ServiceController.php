<?php
namespace spa;

class ServiceController extends \BaseController{

	public function getService(){
		try {
			$serviceCats = array();
			$serviceCatsCmd = \SpaService::where('_parent', 'N')
									  	 ->get()
									  	 ->toArray();
			$services = array();
			if ($serviceCatsCmd) {
				$row = 0;
				foreach ($serviceCatsCmd as $key => $serviceCatCmd) {
					$serviceCats[$row][] = $serviceCatCmd;
					if ( ($key+1)%4 == 0){
						$row+=1;
					}
					$services[$serviceCatCmd['id']] = \SpaService::where('_parent', $serviceCatCmd['id'])
																 ->get(array('id', 'title'))
																 ->toArray();;
				}
			}
			var_dump($services);
			exit;
			return \View::make('spa.service.view_service', array(
				"serviceCats" => $serviceCats
			));
		} catch (Exception $e) {
			echo $e->getMessage();
			exit;
		}
	}
}

?>