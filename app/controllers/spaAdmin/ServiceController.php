<?php
namespace spaAdmin;

/*
 * this controller is used to handle all request of spa serivce
 */
class ServiceController extends \BaseController {

	/*
	 * Display service list.
	 */
	public function getServiceList() {
		try {
			$category = \Input::get('category', null);
			$page = \Input::get('page', 1);
			$limit = 10;
       		$offset = ($page-1) * $limit;

       		//set service list by category
			$parent_equality = "!=";
			$parent_value = 'N';
			if(isset($category)){
				$parent_equality = "=";
				$parent_value = $category;
			}
			
			$cmd = \SpaService::where('_parent', $parent_equality, $parent_value);

			$rowsNum = $cmd->count();
			
			$services = $cmd->orderBy('_parent', 'DESC')
								->orderBy('ref', 'DESC')
								->orderBy('sort','DESC')
								->orderBy('updated_at', 'desc')
								->skip($offset)
                        		->take($limit)
								->get();

			//get category list
			$categorys = \SpaService::where('_parent', 'N')
									->get(array('id', 'title', '_parent'));

			$category_array = array();
			if (!empty($categorys)) {
				foreach ($categorys as $row) {
					if(isset($row)){
						$category_array[$row->id] = $row->title;
					}
				}
			}

			$actionURL = \URL::route('spa.admin.service.article.action');
			$deleteURL = \URL::route('spa.admin.service.article.delete');
			$updateSortURL = \URL::route('spa.admin.service.sort.update');

			$widgetParam = array(
			    'currPage' => $page,
			    'total' => $rowsNum,
			    'perPage' => $limit,
			    'URL' => null,
			    'route' => 'spa.admin.service.article.list'
			);

			return \View::make('spa_admin.service.view_list', array(
				'services'=>&$services,
				'category_array'=>&$category_array,
				'acrionURL'=>$actionURL,
				'deleteURL'=>$deleteURL,
				'category'=>$category,
				'updateSortURL'=>$updateSortURL,
				'pagerParam' => &$widgetParam
			));
		} catch (Exception $e) {
			echo $e->getMessage();
			exit;
		}
	}


	/*
	 * Display service create/edit page.
	 * @params (int) $id
	 * @params (string) $lang
	 */
	public function getServiceAction($id=null, $lang=null) {
		$action = "create";
		try {
			$articleCat = \Input::get('category', null);

			//create lang 
			$ref = 0;
			$refLang = null;
			$createLangCat = null;
			if($id && $lang){
				$ref = $id;
				$refCat = null;
				$servCmd = \SpaService::where('id',$id)
									  ->first(array('lang','_parent'));
				
				if($servCmd){
					$refLang = $servCmd->lang;
					$refCat = $servCmd->_parent;
				}else
					return \Redirect::route('spa.admin.service.article.list', array('category', $articleCat));

				$createLangCat = \SpaService::where('ref', $refCat)->first(array('id'))->id;
			}
			
			$categorys = array();
			$catCmd = \SpaService::where('_parent', 'N');
			if(!empty($lang))
				$catCmd = $catCmd->where('lang', '!=', $refLang);
			if($catCmd->get())
				$categorys = $catCmd->get();

			$writeURL = \URL::route('spa.admin.service.article.write');
			
			//edit data
			$service = array();
			$serviceCover = array();
			$serviceImages = array();
			$items = array();
			if ($id && !$lang){
				$action = "edit";
				$writeURL .=  "/".$id;

				$service = \SpaService::find($id);
				if (empty($service))
					return \Redirect::route('spa.admin.service.article.list');

				if($service->image != '')
					$serviceCover[] = array(
						'id' => $service->id,
						'image' => $service->image,
						'text' => $service->image_desc
					);
				
				$serviceImagesList = \SpaServiceImages::where('ser_id',$id)
													   ->get(array('id', 'image_path', 'description'));
				if (!empty($serviceImagesList)){
					foreach ($serviceImagesList as $key => $img) {
						$serviceImages[] = array(
							'id' => $img->id,
							'image' => $img->image_path,
							'text' => $img->description
						);
					}	
				}
				
				$tags = json_decode($service->tag);
				if (!empty($tags)) {
					foreach ($tags as $tag) {
						$items[] = array(
		                    'title' => $tag->title,
		                    'content' => $tag->content,
		                    'sort' => $tag->sort
		                );
					}
				}
			}
			
			return \View::make('spa_admin.service.view_action', array(
				'action' => $action,
				'service' => &$service, //edit data
				'serviceCover' => &$serviceCover,
				'serviceImages' => &$serviceImages,
				'categorys' => &$categorys,
				'writeURL' => $writeURL,
				'ref' => $ref,
				'refLang' => $refLang,
				'tab' => array(
	                'elementId' => 'tab-box',
	                'formTitle' => 'Tab項目',
	                'items' => &$items
            	),
            	'articleCat' => $articleCat,
            	'createLangCat' => $createLangCat
			));
		} catch (Exception $e) {
			echo $e->getMessage();
			exit;
		}
	}


	/*
	 * Write(create/edit action) service data.
	 * @params (int) $id
	 */
	public function postWriteService($id = null) {
		$articlecat = \Input::get('category', null);
		$action = \Input::get('action');
		try {
			//tags
			$order = 0;
            $tabContents = \Input::get('tabContents', array());
            $tabs = null;
            $tabName = \Input::get('tabName', array());
            if(!empty($tabName)){
	            foreach ($tabName as $key => $tab) {
	                if (!isset($tabContents[$key]))
	                    continue;
	                $tabs[] = array(
	                    'title' => $tab,
	                    'content' => $tabContents[$key],
	                    'sort' => $order
	                );
	                $order++;
	            }
			}
			$ref = \Input::get('ref');

			//service_table 
			if($action == 'create') {
				$service = new \SpaService;
			}else {
				$service = \SpaService::find($id);
				if (!$service)
					$service = new \SpaService;
			}
			
			//check ref service data
			$refServCmd = \SpaService::find($ref);
			if (!$refServCmd)
				$ref = 0;
			
			$title = \Input::get('title');
			$image = \Input::get('images')[0];
			$image_desc = \Input::get('main_imageDesc')[0];

			$service->title = !empty($title) ? $title : "";
			$service->image = !empty($image) ? $image : "";
			$service->image_desc = !empty($image_desc) ? $image_desc : "";
			$service->tag = json_encode($tabs);
			$service->_parent = \Input::get('cat');
			$service->display = \Input::get('display');
			$service->lang = \Input::get('lang');
			$service->ref = $ref;
			$service->save();

			//create service id
			if($action == 'create'){
				$inserted_id = $service->id;
			}else{
				$inserted_id = $id;
			}

			//set ref
			if($ref != '0'){
				$refServCmd->ref = $inserted_id;
				$refServCmd->save();
			}

			//service_image_table
			$images = \Input::get('images');
			$images_desc = \Input::get('imageDesc');
			
			if($action == 'edit'){
				\SpaServiceImages::where('ser_id',$id)->delete();
			}
			if(!empty($images)){
				foreach ($images as $key => $image) {
					$service_img = new \SpaServiceImages;

					$service_img->ser_id = $inserted_id;
					$service_img->image_path = $image;
					$service_img->description = $images_desc[$key];
					$service_img->sort = $key;
					$service_img->save();
				}
			}

			return \Redirect::route("spa.admin.service.article.list", array('category'=>$articlecat));
		} catch (Exception $e) {
			echo $e->getMessage();
			exit;
		}
	}
	
	/*
     * AJAX request for Delete service by specific id.
     */
	public function postDeleteService() {
		try {
			$id = \Input::get('id');

			$servCmd = \SpaService::find($id);

			//delete service image
			$serviceImages = \SpaServiceImages::where('ser_id', $id)
											  ->delete();
			//edit ref service
			if($servCmd->ref != '0'){
				$ref_service = \SpaService::find($servCmd->ref);
				$ref_service->ref = 0;
				$ref_service->save();
			}

			$servCmd->delete();

			return \Response::json(array(
	            'status' => 'ok',
	            'message' => '刪除完成!'
	        ));
		} catch (Exception $e) {
			return Response::json(array(
                'status' => 'error',
                'message' => $e->getMessage()
            ));
		}
	}

	/*
	 * Display category list page
	 */
	public function getCategoryList() {
		try {
			$categorys = \SpaService::where('_parent', 'N')
										->orderBy('sort', 'DESC')
										->get(array('id', 'title', 'sort', 'lang', 'ref'));
			
			$serviceListURL = \URL::route('spa.admin.service.article.list');
			$categoryActionURL = \URL::route('spa.admin.service.category.action');
			$categoryDeleteURL = \URL::route('spa.admin.service.category.delete');
			$updateSortURL = \URL::route('spa.admin.service.sort.update');

			return \View::make('spa_admin.service.view_category_list', array(
				"categorys" => &$categorys,
				"serviceListURL" => $serviceListURL,
				"categoryActionURL" => $categoryActionURL,
				"categoryDeleteURL" => $categoryDeleteURL,
				"updateSortURL" => $updateSortURL
			));
		} catch (Exception $e) {
			echo $e->getMessage();
			exit;
		}
	}

	/*
     * AJAX request for action(create/edit) category
     */
    public function postCategoryAction(){
    	try {
    		$category_id = \Input::get('id');
    		$ref_id = \Input::get('ref');
    		if($category_id == "null"){
    			$service_category = new \SpaService;
    		}else{
    			$service_category = \SpaService::find($category_id);
    			if(!$service_category)
    				$service_category = new \SpaService;
    		}	
    		$service_category->title = \Input::get('title');
    		$service_category->sort = \Input::get('sort');
    		$service_category->lang = \Input::get('lang');
    		if($ref_id != "null"){
	    		$service_category->ref = $ref_id;
	    	}
    		$service_category->save();

			//set ref
			if($ref_id != "null"){
				$inserted_id = $service_category->id;
				$ref_category = \SpaService::find($ref_id);
				$ref_category->ref = $inserted_id;
				$ref_category->save();
			}

	        return \Response::json(array(
	            'status' => 'ok',
	            'message' => '儲存完成!'
	        ));
        } catch (Exception $e) {
    		return Response::json(array(
	            'status' => 'error',
	            'message' => $e->getMessage()
	        ));
    	}
    }

    /*
     * hadnle AJAX request for delete category
     */
    public function postDeleteCategory(){
    	try {
    		$category_id = \Input::get('id');

    		\SpaService::find($category_id)->delete();

    		//delete serive article images
    		$services = \SpaService::where('_parent', $category_id)
    								 ->get(array('id'));
    		$servArry = array();
			if (!empty($services)) {
	    		foreach ($services as  $service) {
	    			$servArry[] = $service->id;
	    		}
	    		\SpaServiceImages::whereIn('ser_id', $servArry)
	    					  ->delete();
    		}
    		
    		//delete categroy service article
    		\SpaService::where('_parent',$category_id)->delete();
    								 
    		return \Response::json(array(
	            'status' => 'ok',
	            'message' => '刪除完成!'
	        ));
    	} catch (Exception $e) {
    		return Response::json(array(
	            'status' => 'error',
	            'message' => $e->getMessage()
	        ));
    	}
    }

	/*
     * handle AJAX request of change sort
     */
    public function postUpdateSort(){
    	try {
    		if (!isset($_POST) || !isset($_POST['id']) || !isset($_POST['sort']) || !isset($_POST['role']))
                throw new Exception('Error request [10]');

            $id = \Input::get('id');
            $role = \Input::get('role');
            $sort = \Input::get('sort');
            $isUpdatedTime = \Input::get('isUpdatedTime', false);
            $lastUpdatedId = \Input::get('lastUpdatedId', false);
            
            
            $model = \SpaService::find($id);
            if (empty($model))
                throw new Exception("Error request [11]");

            $model->sort = $sort;

            if (!$model->save())
                throw new Exception("更新排序失敗，請通知工程師");

            if ($isUpdatedTime){
                $cmd = \SpaService::where('id', '!=', $id)
                                 ->where('sort', '=', $sort)
                                 ->where('id', '>=', $lastUpdatedId)
                                 ->orderBy('sort', 'desc')
                                 ->orderBy('updated_at', 'desc');
                if ($role=='category')
                    $cmd->where('_parent', '=', 'N');
                else
                    $cmd->where('_parent', '=', $model->_parent);

                $items = $cmd->get();
                if (sizeof($items)>0){
                    $t = time();
                    foreach($items as $key=>$item){
                        $t = $t+$key;
                        $item->updated_at = $t;
                        $item->save();
                    }
                }
            }
	
		    return \Response::json(array(
	            'status' => 'ok',
	            'message' => '更新排序完成'
	        ));
		} catch (Exception $e) {
    		return Response::json(array(
                'status' => 'error',
                'message' => $e->getMessage()
            ));
    	}
    }
}
