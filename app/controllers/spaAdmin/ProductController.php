<?php
namespace spaAdmin;

/*
 * this controller is used to handle all request of spa product
 */
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

       		//set product list by category
			$parent_equality = "!=";
			$parent_value = 'N';
			if(isset($category)){
				$parent_equality = "=";
				$parent_value = $category;
			}
			
			$cmd = \SpaProduct::where('_parent', $parent_equality, $parent_value);

			$rowsNum = $cmd->count();

			$products = $cmd->orderBy('_parent', 'DESC')
								->orderBy('ref', 'DESC')
								->orderBy('sort','DESC')
								->orderBy('updated_at', 'desc')
								->skip($offset)
                    			->take($limit)
								->get();

			$categorys = \SpaProduct::where('_parent', 'N')
									->get(array('id', 'title', '_parent'));

			$category_array = array();
			if (!empty($categorys)) {
				foreach ($categorys as $row) {
					if(isset($row)){
						$category_array[$row->id] = $row->title;
					}
				}
			}
			$actionURL = \URL::route('spa.admin.product.article.action');
			$deleteURL = \URL::route('spa.admin.product.article.delete');
			$updateSortURL = \URL::route('spa.admin.product.sort.update');

			$widgetParam = array(
			    'currPage' => $page,
			    'total' => $rowsNum,
			    'perPage' => $limit,
			    'URL' => null,
			    'route' => 'spa.admin.product.article.list'
			);

			return \View::make('spa_admin.product.view_list', array(
				'products'=>&$products,
				'category_array'=>&$category_array,
				'actionURL'=>$actionURL,
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
	 * Display product create/edit page.
	 * @params (int) $id
	 * @params (string) $lang
	 */
	public function getProductAction($id=null, $lang=null) {
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
				$prodCmd = \SpaProduct::where('id',$id)
									  ->first(array('lang','_parent'));
				if($prodCmd) {
					$refLang = $prodCmd->lang;
					$refCat = $prodCmd->_parent;
				}else
					return \SpaProduct::route('spa.admin.product.article.list', array('category', $articleCat));
				$createLangCat = \SpaProduct::where('ref', $refCat)->first(array('id'))->id;
			}
			
			$category_list = array();
			$category = \SpaProduct::where('_parent', 'N');
			if(!empty($lang))
				$category = $category->where('lang', '!=', $refLang);
			$category_list = $category->get();

			$writeURL = \URL::route('spa.admin.product.article.write');

			//edit data
			$product = array();
			$productCover = array();
			$productImages = array();
			$items = array();
			if (isset($id) && empty($lang)){
				$action = "edit";
				$writeURL .= "/".$id;

				$product = \SpaProduct::find($id);
				if (empty($product))
					return \Redirect::route('spa.admin.service.list');
				if($product->image != '')
					$productCover[] = array(
						'id' => $product->id,
						'image' => $product->image,
						'text' => $product->image_desc
					);
				
				$serviceImagesList = \SpaServiceImages::where('ser_id',$id)
													  ->get(array('id', 'image_path', 'description'));
				if (!empty($serviceImagesList)){
					foreach ($serviceImagesList as $key => $img) {
						$productImages[] = array(
							'id' => $img->id,
							'image' => $img->image_path,
							'text' => $img->description
						);
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
				'productCover'=>$productCover,
				'productImages'=>$productImages,
				'category_list'=>&$category_list,
				'writeURL'=>$writeURL,
				'ref'=>$ref,
				'refLang'=>$refLang,
				'tab' => array(
	                'elementId' => 'tab-box',
	                'formTitle' => 'Tab項目',
	                'items' => $items
            	),
            	'articleCat'=>$articleCat,
            	'createLangCat' => $createLangCat
			));
		} catch (Exception $e) {
			echo $e->getMessage();
			exit;
		}
	}


	/*
	 * Write(create/edit action) product data.
	 * @params (int) $id
	 */
	public function postWriteProduct($id = null) {
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
				$product = new \SpaProduct;
			}else {
				$product = \SpaProduct::find($id);
				if(!$product)
					$product = new \SpaProduct;
			}

			//check ref service data
			$refprodCmd = \SpaProduct::find($ref);
			if (!$refprodCmd)
				$ref = 0;

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
				$refprodCmd->ref = $inserted_id;
				$refprodCmd->save();
			}

			//service_image_table
			$images = \Input::get('images');
			$images_desc = \Input::get('imageDesc');
			
			if($action == 'edit'){
				\SpaProductImages::where('pro_id',$id)->delete();
			}
			if(!empty($images)){
				foreach ($images as $key => $image) {
					$service_img = new \SpaProductImages;

					$service_img->pro_id = $inserted_id;
					$service_img->image_path = $image;
					$service_img->description = $images_desc[$key];
					$service_img->sort = $key;
					$service_img->save();
				}
			}

			return \Redirect::route("spa.admin.product.article.list", array('category'=>$articlecat));
		} catch (Exception $e) {
			echo $e->getMessage();
			exit;
		}
	}
	
	/*
     * AJAX request for Delete product by specific id.
     */
	public function postDeleteProduct() {
		try {
			$id = \Input::get('id');

			$prodCmd = \SpaProduct::find($id);

			//delete service image
			$serviceImages = \SpaProductImages::where('pro_id', $id)
											  ->delete();
			//edit ref service
			if($prodCmd->ref != '0'){
				$ref_product = \SpaProduct::find($prodCmd->ref);
				$ref_product->ref = 0;
				$ref_product->save();
			}

			$prodCmd->delete();
			
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
			$categorys = \SpaProduct::where('_parent','N')
										->orderBy('sort','DESC')
										->get(array('id','title','sort', 'lang', 'ref'));

			$serviceListURL = \URL::route('spa.admin.product.article.list');
			$categoryActionURL = \URL::route('spa.admin.product.category.action');
			$categoryDeleteURL = \URL::route('spa.admin.product.category.delete');
			$updateSortURL = \URL::route('spa.admin.product.sort.update');

			return \View::make('spa_admin.product.view_category_list', array(
				"categorys"=>&$categorys,
				"serviceListURL"=>$serviceListURL,
				"categoryActionURL"=>$categoryActionURL,
				"categoryDeleteURL"=>$categoryDeleteURL,
				"updateSortURL"=>$updateSortURL
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

			\SpaProduct::find($category_id)->delete();

    		//delete product article images
    		$products = \SpaProduct::where('_parent', $category_id)
    							   ->get(array('id'));
    		$prodArry = array();
			if (!empty($products)) {
	    		foreach ($products as  $product) {
	    			$prodArry[] = $product->id;
	    		}
	    		\SpaProductImages::whereIn('pro_id', $servArry)
	    					  ->delete();
    		}
    		
    		//delete categroy product article
    		\SpaProduct::where('_parent',$category_id)->delete();
    			
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
