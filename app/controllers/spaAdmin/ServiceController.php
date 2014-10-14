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
			$listLang = \Input::get('lang', "tw");
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
			
			$cmd = \SpaService::where('_parent', $parent_equality, $parent_value)
							  ->where('lang', $listLang);

			$rowsNum = $cmd->count();
			
			$services = $cmd->orderBy('_parent', 'DESC')
								->orderBy('sort','DESC')
								->orderBy('updated_at', 'desc')
								->skip($offset)
                        		->take($limit)
								->get();

			if(!empty($services)) {
				foreach ($services as $key => $service) {
					$services[$key]['ref_display']=\SpaService::where('id', $service->ref)
					 						       			  ->first(array('display'))
					 						       			  ->display;
				}
			}
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
			$twListUrl = \URL::route('spa.admin.service.article.list', array('lang'=>'tw', "category"=>$category));
			$cnListUrl = \URL::route('spa.admin.service.article.list', array('lang'=>'cn', "category"=>$category));

			$widgetParam = array(
			    'currPage' => $page,
			    'total' => $rowsNum,
			    'perPage' => $limit,
			    'URL' => null,
			    'route' => 'spa.admin.service.article.list'
			);
			$langControlGroup = array(
				'tw' => 'cn',
				'cn' => 'tw'
			);
			return \View::make('spa_admin.service.view_list', array(
				'services'=>&$services,
				'category_array'=>&$category_array,
				'acrionURL'=>$actionURL,
				'deleteURL'=>$deleteURL,
				'category'=>$category,
				'updateSortURL'=>$updateSortURL,
				'pagerParam' => &$widgetParam,
				'twListUrl' => $twListUrl,
				'cnListUrl' => $cnListUrl,
				'listLang' => $listLang,
				'langControlGroup'=>$langControlGroup
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
	public function getServiceAction($id=null) {
		$action = "create";
		try {
			$listLang = \Input::get('lang', "tw");
			$articleCat = \Input::get('category', null);

			$writeURL = \URL::route('spa.admin.service.article.write');
			
			//edit data
			$service = array();
			$serviceCover = array();
			$serviceImages = array();
			$items = array();
			if ($id){
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
			//categorys
			$categorys = array();
			$catCmd = \SpaService::where('_parent', 'N');
			if(!empty($listLang))
				$catCmd = $catCmd->where('lang', '=', $listLang);
			if($catCmd->get()){
				if($id) {
					if($service->_parent == "") {
						$categorys[] = array(
							"id"=>'',
							'title'=>''
						);
					}
				}
				foreach ($catCmd->get(array('id', 'title'))->toArray() as $category) {
					$categorys[] = $category;
				}
			}
			
			return \View::make('spa_admin.service.view_action', array(
				'action' => $action,
				'service' => &$service, //edit data
				'serviceCover' => &$serviceCover,
				'serviceImages' => &$serviceImages,
				'categorys' => &$categorys,
				'writeURL' => $writeURL,
				'tab' => array(
	                'elementId' => 'tab-box',
	                'formTitle' => 'Tab項目',
	                'items' => &$items
            	),
            	'articleCat' => $articleCat,
            	'listLang' => $listLang
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
		$listLang = \Input::get('listLang', "tw");
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

			//service_table 
			if($action == 'create') {
				$service = new \SpaService;
			}else {
				$service = \SpaService::find($id);
				if (!$service)
					$service = new \SpaService;
			}
			
			$title = \Input::get('title');
			$image = \Input::get('main_image')[0];
			$image_desc = \Input::get('main_imageDesc')[0];
			$content = \Input::get('content');
			$meta_name = \Input::get('meta_name');
			$meta_content = \Input::get('meta_content');

			$service->title = !empty($title) ? $title : "";
			$service->image = !empty($image) ? $image : "";
			$service->image_desc = !empty($image_desc) ? $image_desc : "";
			$service->content = !empty($title) ? $content : "";
			$service->tag = json_encode($tabs);
			$service->_parent = \Input::get('cat');
			$service->display = \Input::get('display');
			$service->lang = $listLang;
			$service->meta_name = !empty($meta_name) ? $meta_name : "";
			$service->meta_content = !empty($meta_content) ? $meta_content : "";
			$service->save();

			//create service id
			if($action == 'create'){
				$inserted_id = $service->id;
			}else{
				$inserted_id = $id;
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

			//system automatically generates another language,and the corresponding
			if($action == 'create') {
				$langControlGroup = array(
					'tw' => 'cn',
					'cn' => 'tw'
				);
				$anotherServiceCat = "";
				$anotherServiceCatCmd = \SpaService::where('ref', \Input::get('cat'))->first(array('id'));
				if($anotherServiceCatCmd)
					$anotherServiceCat = $anotherServiceCatCmd->id;

				$anotherService = new \SpaService;
				$anotherService->title = !empty($title) ? $title : "";
				$anotherService->image = "";
				$anotherService->image_desc = "";
				$anotherService->content = !empty($title) ? $content : "";
				$anotherService->tag = json_encode($tabs);
				$anotherService->_parent = $anotherServiceCat;
				$anotherService->display = "no";
				$anotherService->lang = $langControlGroup[$listLang];
				$anotherService->ref = $inserted_id;
				$anotherService->meta_name = !empty($meta_name) ? $meta_name : "";
				$anotherService->meta_content = !empty($meta_content) ? $meta_content : "";
				$anotherService->save();

				$anotherId = $anotherService->id;

				$createService = \SpaService::find($inserted_id);
				$createService->ref = $anotherId;
				$createService->save();
			}
			return \Redirect::route("spa.admin.service.article.list", array('category'=>$articlecat, 'lang'=>$listLang));
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

			//delete service
			\SpaService::find($id)->delete();;

			//delete service image
			\SpaServiceImages::where('ser_id', $id)
							 ->delete();
			//delete ref service
			\SpaService::where('ref', $id)
					   ->delete();

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
			$categorys = array();
			$catsTW = array();
			$catsCN = array();

			$catsTWCmd = \SpaService::where('_parent', 'N')
									->where('lang', 'tw')
									->orderBy('sort', 'DESC')
									->get(array('id', 'title', 'sort', 'lang', 'display', 'ref', 'image'));
			$catsCNCmd = \SpaService::where('_parent', 'N')
									->where('lang', 'cn')
									->orderBy('sort', 'DESC')
									->get(array('id', 'title', 'sort', 'lang', 'display', 'ref', 'image'));
			if($catsTWCmd)
				$catsTW = $catsTWCmd;
			if($catsCNCmd)
				$catsCN = $catsCNCmd;

			$categorys = array(
				'tw'=>array(
					'item' => $catsTW
				),
				'cn'=>array(
					'item' => $catsCN
				)
			);

			$actionURL = \URL::route('spa.admin.service.category.action');
			$categoryDeleteURL = \URL::route('spa.admin.service.category.delete');
			$updateSortURL = \URL::route('spa.admin.service.sort.update');

			return \View::make('spa_admin.service.view_category_list', array(
				"categorys" => &$categorys,
				"categoryDeleteURL" => $categoryDeleteURL,
				"updateSortURL" => $updateSortURL,
				"actionURL" => $actionURL
			));
		} catch (Exception $e) {
			echo $e->getMessage();
			exit;
		}
	}

    /*
     * hadnle AJAX request for delete category
     */
    public function postDeleteCategory() {
    	try {
    		$category_id = \Input::get('id');

    		$cateCmd = \SpaService::find($category_id);
    		$cateRefCmd = \SpaService::find($cateCmd->ref);
    		
    		//delete categroy service article
    		$servCmd = \SpaService::where('_parent', $category_id);
    		if(!$servCmd->get()){
    			$servCmd->delete();
    		}
    		$servRefCmd = \SpaService::where('_parent', $cateRefCmd->id);
    		if(!$servRefCmd->get())
    			$servRefCmd->delete();

    		//delete category
			$cateCmd->delete();
			$cateRefCmd->delete();

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
    public function postUpdateSort() {
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

    /*
     * Display category action page
     * params (int) $id
     */
    public function getCategoryAction($id = null){
    	try {
    		$action = 'create';
 			
    		$writeURL = \URL::route('spa.admin.service.category.write');

    		$category = array();
    		$cateCover = array();
    		if(!empty($id)) {
    			$action = 'edit';
    			$writeURL .= "/".$id;
    			$category = \SpaService::where('id', $id)
    								   ->first(array('id', 'title', 'image', 'image_desc', 'sort', 'display'));
    			if($category->image != '')
					$cateCover[] = array(
						'id' => $category->id,
						'image' => $category->image,
						'text' => $category->image_desc
					);
    		}

    		return \View::make('spa_admin.service.view_category_action', array(
    			'writeURL' => $writeURL,
    			'cateId' => $id,
    			'action' => $action,
    			'category' => $category,
    			'cateCover' => $cateCover
    		));
    	} catch (Exception $e) {
    		echo $e->getMessage();
			exit;
    	}
    }
    
    /*
	 * Write(create/edit action) category data.
	 * @params (int) $id
	 */
    public function postWriteCategory($id = null) {
    	try {
    		$action = \Input::get('action');
    		if ($action == 'create')
    			$categoryCmd = new \SpaService;
    		else
    			$categoryCmd = \SpaService::find($id);

    		$categoryCmd->title = \Input::get('title');
    		$categoryCmd->image = \Input::get('image')[0];
    		$categoryCmd->image_desc = \Input::get('imageDesc')[0];
    		$categoryCmd->sort = \Input::get('sort');
    		$categoryCmd->display = \Input::get('display');
    		if($action == 'create')
    			$categoryCmd->lang = 'tw';
				

    		$categoryCmd->save();
			$inserted_id = $categoryCmd->id;

    		//system automatically generates another language,and the corresponding
    		if($action == 'create'){
	    		$refCategoryCmd = new \SpaService;

	    		$refCategoryCmd->title = \Input::get('title');
	    		$refCategoryCmd->image = "";
    			$refCategoryCmd->image_desc = "";
    			$refCategoryCmd->sort = \Input::get('sort');
				$refCategoryCmd->lang = 'cn';
				$refCategoryCmd->ref = $inserted_id;
				$refCategoryCmd->display = 'no';

	    		$refCategoryCmd->save();
	    		$ref_inserted_id = $refCategoryCmd->id;

	    		//set category ref
	    		$setCategoryCmd = \SpaService::find($inserted_id);
	    		$setCategoryCmd->ref = $ref_inserted_id;

	    		$setCategoryCmd->save();
    		}

	        return \Redirect::route("spa.admin.service.category.list");
        } catch (Exception $e) {
    		echo $e->getMessage();
    		exit;
    	}
    }
}
