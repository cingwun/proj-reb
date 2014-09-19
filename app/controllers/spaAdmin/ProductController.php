<?php
namespace spaAdmin;
class ProductController extends \BaseController {

	/*
	 * Display product list.
	 */
	public function getProductList() {
		try {
			$category = \Input::get('category', null);
			$page = \Input::get('page', 1);
			$limit = 10;
       		$offset = ($page-1) * $limit;

			$parent_equality = "!=";
			$parent_value = 'N';
			if(isset($category)){
				$parent_equality = "=";
				$parent_value = $category;
			}
			
			$cmd = \SpaProduct::where('_parent', $parent_equality, $parent_value);

			$rowsNum = $cmd->count();

			$product_list = $cmd->orderBy('_parent', 'DESC')
								->orderBy('ref', 'DESC')
								->orderBy('sort','DESC')
								->orderBy('updated_at', 'desc')
								->skip($offset)
                    			->take($limit)
								->get();

			$category_list = \SpaProduct::where('_parent', 'N')
									->get(array('id', 'title', '_parent'));

			$category_array = array();
			foreach ($category_list as $row) {
				if(isset($row)){
					$category_array[$row->id] = $row->title;
				}
			}

			$action_url = \URL::route('spa.admin.product.article.action');
			$delete_url = \URL::route('spa.admin.product.article.delete');
			$update_sort_url = \URL::route('spa.admin.product.sort.update');

			$widgetParam = array(
			    'currPage' => $page,
			    'total' => $rowsNum,
			    'perPage' => $limit,
			    'URL' => null,
			    'route' => 'spa.admin.product.article.list'
			);

			return \View::make('spa_admin.product.view_list', array(
				'product_list'=>&$product_list,
				'category_array'=>&$category_array,
				'acrion_url'=>$action_url,
				'delete_url'=>$delete_url,
				'category'=>$category,
				'update_sort_url'=>$update_sort_url,
				'pagerParam' => &$widgetParam
			));
		} catch (Exception $e) {
			echo $e->getMessage();
			exit;
		}
	}


	/*
	 * Display product create/edit page.
	 * @params (string) $id
	 * @params (string) $lang
	 */
	public function getProductAction($id=null, $lang=null) {
		$action = "create";
		try {
			$list_category = \Input::get('category', null);
			//lang_create
			$ref = 0;
			$ref_lang = null;
			if(isset($lang)){
				$ref = $id;
				$ref_lang = \SpaProduct::where('id',$id)->first(array('lang'))->lang;
			}

			$category_list = array();
			$category = \SpaProduct::where('_parent', 'N');
			if(!empty($lang))
				$category = $category->where('lang', '!=', $ref_lang);
			$category_list = $category->get();

			$write_url = \URL::route('spa.admin.product.article.write');

			//edit data
			$product = array();
			$product_mian_img = array();
			$product_imgs = array();
			$items = array();
			if (isset($id) && empty($lang)){
				$action = "edit";
				$write_url .= "/".$id;

				$product = \SpaProduct::find($id);
				if (empty($product))
					return \Redirect::route('spa.admin.service.list');
				if($product->image != '')
					$product_mian_img[] = array('id'=>$product->id, 'image'=>$product->image, 'text'=>$product->image_desc);
				
				$service_imgs_list = \SpaServiceImages::where('ser_id',$id)->get(array('id', 'image_path', 'description'));
				if (!empty($service_imgs_list)){
					foreach ($service_imgs_list as $key => $img) {
						$product_imgs[] = array('id'=>$img->id, 'image'=>$img->image_path, 'text'=>$img->description);
					}	
				}
				
				$tags = json_decode($product->tag);
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
			
			return \View::make('spa_admin.product.view_action', array(
				'action'=>$action,
				'product'=>&$product, //edit data
				'product_mian_img'=>$product_mian_img,
				'product_imgs'=>$product_imgs,
				'category_list'=>&$category_list,
				'write_url'=>$write_url,
				'ref'=>$ref,
				'ref_lang'=>$ref_lang,
				'tab' => array(
	                'elementId' => 'tab-box',
	                'formTitle' => 'Tab項目',
	                'items' => $items
            	),
            	'list_category'=>$list_category
			));
		} catch (Exception $e) {
			echo $e->getMessage();
			exit;
		}
	}


	/*
	 * Write(create/edit action) product data.
	 * @params (string) $id
	 */
	public function postWriteProduct($id = null) {
		$list_category = \Input::get('category', null);
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
			if($action == 'create')
				$product = new \SpaProduct;
			else
				$product = \SpaProduct::find($id);

			$title = \Input::get('title');
			$image = \Input::get('images')[0];
			$image_desc = \Input::get('main_imageDesc')[0];
			$capacity = \Input::get('capacity');
			$price = \Input::get('price');

			$product->title = !empty($title) ? $title : "";;
			$product->image = !empty($image) ? $image : "";
			$product->image_desc = !empty($image_desc) ? $image_desc : "";
			$product->capacity = !empty($capacity) ? $capacity : "";
			$product->price = !empty($price) ? $price : "";
			$product->tag = json_encode($tabs);
			$product->_parent = \Input::get('cat');
			$product->display = \Input::get('display');
			$product->lang = \Input::get('lang');
			$product->ref = $ref;
			$product->save();

			//create service id
			if($action == 'create'){
				$inserted_id = $product->id;
			}else{
				$inserted_id = $id;
			}

			//set ref
			if($ref != '0'){
				$ref_service_data = \SpaProduct::find($ref);
				$ref_service_data->ref = $inserted_id;
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

			return \Redirect::route("spa.admin.product.article.list", array('category'=>$list_category));
		} catch (Exception $e) {
			echo $e->getMessage();
			exit;
		}
	}
	
	/*
     * AJAX request for Delete Service.
     * @param (string) $id
     */
	public function postDeleteProduct() {
		try {
			$id = \Input::get('id');

			$service = \SpaProduct::find($id); 

			$service->delete();

			if($service->ref != '0'){
				$ref_service = \SpaProduct::find($service->ref);
				$ref_service->ref = 0;
				$ref_service->save();
			}

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
			$category_list = \SpaProduct::where('_parent','N')
										->orderBy('sort','DESC')
										->get(array('id','title','sort', 'lang', 'ref'));

			$service_list_url = \URL::route('spa.admin.product.article.list');
			$category_action_url = \URL::route('spa.admin.product.category.action');
			$category_delete_url = \URL::route('spa.admin.product.category.delete');
			$update_sort_url = \URL::route('spa.admin.product.sort.update');

			return \View::make('spa_admin.product.view_category_list', array(
				"category_list"=>&$category_list,
				"service_list_url"=>$service_list_url,
				"category_action_url"=>$category_action_url,
				"category_delete_url"=>$category_delete_url,
				"update_sort_url"=>$update_sort_url
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
    			$product_category = new \SpaProduct;
    		}else{
    			$product_category = \SpaProduct::find($category_id);
    		}	
    		$product_category->title = \Input::get('title');
    		$product_category->sort = \Input::get('sort');
    		$product_category->lang = \Input::get('lang');
    		if($ref_id != "null"){
	    		$product_category->ref = $ref_id;
	    	}
    		$product_category->save();

			//set ref
			if($ref_id != "null"){
				$inserted_id = $product_category->id;
				$ref_category = \SpaProduct::find($ref_id);
				$ref_category->ref = $inserted_id;
				$ref_category->save();
			}

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

    		$product_category = \SpaProduct::find(\Input::get('id'));

    		$product_category->delete();
    			
    		return \Response::json(array(
	            'status' => 'ok',
	            'message' => '刪除完成!'
	        ));
    	} catch (Exception $e) {
    		
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
            
            
            $model = \SpaProduct::find($id);
            if (empty($model))
                throw new Exception("Error request [11]");

            $model->sort = $sort;

            if (!$model->save())
                throw new Exception("更新排序失敗，請通知工程師");

            if ($isUpdatedTime){
                $cmd = \SpaProduct::where('id', '!=', $id)
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
