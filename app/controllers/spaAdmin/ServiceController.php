<?php
namespace spaAdmin;
class ServiceController extends \BaseController {

	/*
	 * Display service list.
	 */
	public function getServiceList($category = null) {
		try {
			$parent_equality = "!=";
			$parent_value = 'N';
			if(isset($category)){
				$parent_equality = "=";
				$parent_value = $category;
			}
			
			$service_list = \SpaService::where('_parent', $parent_equality, $parent_value)
									->orderBy('_parent', 'DESC')
									->orderBy('ref_id', 'DESC')
									->get();
			
			
			$category_list = \SpaService::where('_parent', 'N')
									->get(array('id', 'title', '_parent'));

			$category_array = array();
			foreach ($category_list as $category) {
				if(isset($category)){
					$category_array[$category->id] = $category->title;
				}
			}

			$action_url = \URL::route('spa.admin.service.article.action');
			$delete_url = \URL::route('spa.admin.service.article.delete');

			return \View::make('spa_admin.service.view_list', array(
				'service_list'=>&$service_list,
				'category_array'=>&$category_array,
				'acrion_url'=>$action_url,
				'delete_url'=>$delete_url
			));
		} catch (Exception $e) {
			echo $e->getMessage();
			exit;
		}
	}


	/**
	 * Display service create/edit page.
	 * @params (string) $id
	 */
	public function getServiceAction($id=null, $lan=null) {
		$action = "create";
		try {
			$category_list = \SpaService::where('_parent', 'N')
										->get();

			$write_url = \URL::route('spa.admin.service.article.write');

			//edit data
			$service = array();
			$service_mian_img = array();
			$service_imgs = array();
			if (isset($id) && empty($lan)){
				$action = "edit";
				$write_url .= "/".$id;

				$service = \SpaService::find($id);
				if (empty($service))
					return \Redirect::route('spa.admin.service.list');
				$service_mian_img[] = array('id'=>$service->id, 'image'=>$service->image, 'text'=>$service->image_desc);
				
				$service_imgs_list = \SpaServiceImages::where('ser_id',$id)->get(array('id', 'image_path', 'description'));
				if (!empty($service_imgs_list)){
					foreach ($service_imgs_list as $key => $img) {
						$service_imgs[] = array('id'=>$img->id, 'image'=>$img->image_path, 'text'=>$img->description);
					}	
				}
			}
			//lan_create
			$ref_id = 0;
			$ref_id_lan = null;
			if(isset($lan)){
				$ref_id = $id;
				$ref_id_lan = \SpaService::where('id',$id)->first(array('lan'))->lan;
			}
			return \View::make('spa_admin.service.view_action', array(
				'action'=>$action,
				'service'=>&$service, //edit data
				'service_mian_img'=>$service_mian_img,
				'service_imgs'=>$service_imgs,
				'category_list'=>&$category_list,
				'write_url'=>$write_url,
				'ref_id'=>$ref_id,
				'ref_id_lan'=>$ref_id_lan
			));
		} catch (Exception $e) {
			echo $e->getMessage();
			exit;
		}
	}


	/*
	 * Write(create/edit action) service data.
	 * @params (string) $id
	 */
	public function postWriteService($id = null) {
		$action = \Input::get('action');
		try {
			$ref_id = \Input::get('ref_id');
			//service_table 
			if($action == 'create')
				$service = new \SpaService;
			else
				$service = \SpaService::find($id);
			
			$service->title = \Input::get('title');
			$service->image = \Input::get('images')[0];
			$service->image_desc = \Input::get('main_imageDesc')[0];
			$service->content = \Input::get('content');
			$service->_parent = \Input::get('cat');
			$service->display = \Input::get('display');
			$service->lan = \Input::get('lan');
			$service->ref_id = $ref_id;
			$service->save();

			//create service id
			if($action == 'create'){
				$inserted_id = $service->id;
			}else{
				$inserted_id = $id;
			}

			//set ref
			if($ref_id != '0'){
				$ref_service_data = \SpaService::find($ref_id);
				$ref_service_data->ref_id = $inserted_id;
				$ref_service_data->save();
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

			return \Redirect::route("spa.admin.service.article.list");
		} catch (Exception $e) {
			echo $e->getMessage();
			exit;
		}
	}
	
	/*
     * Delete Service.
     * @param (string) $id
     */
	public function postDeleteService($id = null) {
		try {
			if(empty($id))
				return \Redirect::route('spa.admin.service.list');
			$service = \SpaService::find($id); 
			if(empty($service))
				return \Redirect::route('spa.admin.service.list');

			$service->delete();

			return \Redirect::route('spa.admin.service.article.list');
		} catch (Exception $e) {
			echo $e->getMessage();
			exit;
		}
	}

	/*
	 * Display category list page
	 */
	public function getCategoryList() {
		try {
			$category_list = \SpaService::where('_parent','N')
										->get(array('id','title','sort'));
			
			$service_list_url = \URL::route('spa.admin.service.article.list');
			$category_action_url = \URL::route('spa.admin.service.category.action');
			$category_delete_url = \URL::route('spa.admin.service.category.delete');

			return \View::make('spa_admin.service.view_category_list', array(
				"category_list"=>&$category_list,
				"service_list_url"=>$service_list_url,
				"category_action_url"=>$category_action_url,
				"category_delete_url"=>$category_delete_url
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

    		if($category_id == 'null')
    			$service_category = new \SpaService;
    		else
    			$service_category = \SpaService::find($category_id);

    		$service_category->title = \Input::get('title');
    		$service_category->sort = \Input::get('sort');
    		$service_category->save();

	        return \Response::json(array(
	            'status' => 'ok',
	            'message' => '儲存完成!'
	        ));
        } catch (Exception $e) {
    		
    	}
    }

    /*
     * hadnle AJAX request for delete category
     */
    public function postDeleteCategory(){
    	try {
    		$category_id = \Input::get('id');

    		$service_category = \SpaService::find(\Input::get('id'));

    		$service_category->delete();
    			
    		return \Response::json(array(
	            'status' => 'ok',
	            'message' => '刪除完成!'
	        ));
    	} catch (Exception $e) {
    		
    	}
    }
}
