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
			$listLang = \Input::get('lang', "tw");
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
			
			$cmd = \SpaProduct::where('_parent', $parent_equality, $parent_value)
			->where('lang', $listLang);

			$rowsNum = $cmd->count();

			$products = $cmd->orderBy('_parent', 'DESC')
			->orderBy('sort','DESC')
			->orderBy('updated_at', 'desc')
			->skip($offset)
			->take($limit)
			->get();

			if(!empty($products)) {
				foreach ($products as $key => $service) {
					$products[$key]['ref_display']=\SpaProduct::where('id', $service->ref)
					->first(array('display'))
					->display;
				}
			}
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
			$twListUrl = \URL::route('spa.admin.product.article.list', array('lang'=>'tw', "category"=>$category));
			$cnListUrl = \URL::route('spa.admin.product.article.list', array('lang'=>'cn', "category"=>$category));

			$widgetParam = array(
				'currPage' => $page,
				'total' => $rowsNum,
				'perPage' => $limit,
				'URL' => null,
				'route' => 'spa.admin.product.article.list'
				);
			$langControlGroup = array(
				'tw' => 'cn',
				'cn' => 'tw'
				);
			return \View::make('spa_admin.product.view_list', array(
				'products'=>&$products,
				'category_array'=>&$category_array,
				'actionURL'=>$actionURL,
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
	 * Display product create/edit page.
	 * @params (int) $id
	 * @params (string) $lang
	 */
	public function getProductAction($id=null) {
		$action = "create";
		try {
			$listLang = \Input::get('lang', "tw");
			$articleCat = \Input::get('category', null);

			$writeURL = \URL::route('spa.admin.product.article.write');

			//edit data
			$product = array();
			$productCover = array();
			$productImages = array();
			$items = array();
			if ($id){
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
				
				$serviceImagesList = \SpaProductImages::where('pro_id',$id)
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
			//categorys
			$categorys = array();
			$catCmd = \SpaProduct::where('_parent', 'N');
			if(!empty($listLang))
				$catCmd = $catCmd->where('lang', '=', $listLang);
			if($catCmd->get()){
				if($id) {
					if($product->_parent == "") {
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

			return \View::make('spa_admin.product.view_action', array(
				'action'=>$action,
				'product'=>&$product, //edit data
				'productCover'=>$productCover,
				'productImages'=>$productImages,
				'categorys'=>&$categorys,
				'writeURL'=>$writeURL,
				'tab' => array(
					'elementId' => 'tab-box',
					'formTitle' => 'Tab項目',
					'items' => $items
					),
				'articleCat'=>$articleCat,
				'listLang' => $listLang
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
		$listLang = \Input::get('lang', "tw");
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

			//product_table 
			if($action == 'create') {
				$product = new \SpaProduct;
			}else {
				$product = \SpaProduct::find($id);
				if(!$product)
					$product = new \SpaProduct;
			}

			$title = \Input::get('title');
			$image = \Input::get('main_image')[0];
			$image_desc = \Input::get('main_imageDesc')[0];
			$content = \Input::get('content');
			$capacity = \Input::get('capacity');
			$price = \Input::get('price');

			$product->title = !empty($title) ? $title : "";;
			$product->image = !empty($image) ? $image : "";
			$product->image_desc = !empty($image_desc) ? $image_desc : "";
			$product->content = !empty($content) ? $content : "";;
			$product->capacity = !empty($capacity) ? $capacity : "";
			$product->price = !empty($price) ? $price : "";
			$product->tag = json_encode($tabs);
			$product->_parent = \Input::get('cat');
			$product->display = \Input::get('display');
			$product->lang = \Input::get('lang');
			$product->save();

			//create service id
			if($action == 'create'){
				$inserted_id = $product->id;
			}else{
				$inserted_id = $id;
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
			//system automatically generates another language,and the corresponding
			if($action == 'create') {
				$langControlGroup = array(
					'tw' => 'cn',
					'cn' => 'tw'
					);
				$anotherProductCat = "";
				$anotherProductCatCmd = \SpaProduct::where('ref', \Input::get('cat'))->first(array('id'));
				if($anotherProductCatCmd)
					$anotherProductCat = $anotherProductCatCmd->id;

				$anotherProduct = new \SpaProduct;
				$anotherProduct->title = !empty($title) ? $title : "";
				$anotherProduct->image = "";
				$anotherProduct->image_desc = "";
				$anotherProduct->content = !empty($content) ? $content : "";;
				$anotherProduct->capacity = !empty($capacity) ? $capacity : "";
				$anotherProduct->price = !empty($price) ? $price : "";
				$anotherProduct->tag = json_encode($tabs);
				$anotherProduct->_parent = $anotherProductCat;
				$anotherProduct->display = "no";
				$anotherProduct->lang = $langControlGroup[\Input::get('lang')];
				$anotherProduct->ref = $inserted_id;
				$anotherProduct->save();

				$anotherId = $anotherProduct->id;

				$createProduct = \SpaProduct::find($inserted_id);
				$createProduct->ref = $anotherId;
				$createProduct->save();
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
			
			//delete service
			\SpaProduct::find($id)->delete();;

			//delete service image
			\SpaProductImages::where('pro_id', $id)
			->delete();
			//delete ref service
			\SpaProduct::where('ref', $id)
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

			$catsTWCmd = \SpaProduct::where('_parent', 'N')
									->where('lang', 'tw')
									->orderBy('sort', 'DESC')
									->get(array('id', 'title', 'sort', 'lang', 'display', 'ref'));
			$catsCNCmd = \SpaProduct::where('_parent', 'N')
									->where('lang', 'cn')
									->orderBy('sort', 'DESC')
									->get(array('id', 'title', 'sort', 'lang', 'display', 'ref'));
			if($catsTWCmd)
				$catsTW = $catsTWCmd;
			if($catsCNCmd)
				$catsCN = $catsCNCmd;

			$catsTWactionURL = array();
			$catsTWservListURL = array();
			foreach ($catsTW as $cat) {
				$catsTWactionURL[$cat->id] = \URL::route('spa.admin.product.category.action', array('id'=>$cat->id));
				$catsTWservListURL[$cat->id] = \URL::route('spa.admin.product.article.list', array('lang'=>$cat->lang, 'category'=>$cat->id));
			}
			$catsCNactionURL = array();
			$catsCNservListURL = array();
			foreach ($catsCN as $cat) {
				$catsCNactionURL[$cat->id] = \URL::route('spa.admin.product.category.action', array('id'=>$cat->id));
				$catsCNservListURL[$cat->id] = \URL::route('spa.admin.product.article.list', array('lang'=>$cat->lang, 'category'=>$cat->id));
			}

			$categorys = array(
				'tw'=>array(
					'item' => $catsTW,
					'actionURL' => $catsTWactionURL,
					'servListURL' => $catsTWservListURL
				),
				'cn'=>array(
					'item' => $catsCN,
					'actionURL' => $catsCNactionURL,
					'servListURL' => $catsCNservListURL
				)
			);

			$actionURL = \URL::route('spa.admin.product.category.action');
			$categoryDeleteURL = \URL::route('spa.admin.product.category.delete');
			$updateSortURL = \URL::route('spa.admin.product.sort.update');

			return \View::make('spa_admin.product.view_category_list', array(
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
    public function postDeleteCategory(){
    	try {
    		$category_id = \Input::get('id');

    		$cateCmd = \SpaProduct::find($category_id);
    		$cateRefCmd = \SpaProduct::find($cateCmd->ref);
    		
    		//delete categroy service article
    		$servCmd = \SpaProduct::where('_parent', $category_id);
    		if(!$servCmd->get()){
    			$servCmd->delete();
    		}
    		$servRefCmd = \SpaProduct::where('_parent', $cateRefCmd->id);
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


    /*
     * Display category action page
     * params (int) $id
     */
    public function getCategoryAction($id = null){
    	try {
    		$action = 'create';

    		$writeURL = \URL::route('spa.admin.product.category.write');

    		$category = array();
    		$cateCover = array();
    		if(!empty($id)) {
    			$action = 'edit';
    			$writeURL .= "/".$id;
    			$category = \SpaProduct::where('id', $id)
    								   ->first(array('id', 'title', 'image', 'image_desc', 'sort', 'display'));
    			if($category->image != '')
    				$cateCover[] = array(
    					'id' => $category->id,
    					'image' => $category->image,
    					'text' => $category->image_desc
    				);
    		}

    		return \View::make('spa_admin.product.view_category_action', array(
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
	public function postWriteCategory($id = null){
		try {
			$action = \Input::get('action');
    		if ($action == 'create')
    			$categoryCmd = new \SpaProduct;
    		else
    			$categoryCmd = \SpaProduct::find($id);

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
	    		$refCategoryCmd = new \SpaProduct;

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
	    		$setCategoryCmd = \SpaProduct::find($inserted_id);
	    		$setCategoryCmd->ref = $ref_inserted_id;

	    		$setCategoryCmd->save();
    		}
    		return \Redirect::route("spa.admin.product.category.list");
		} catch (Exception $e) {
			echo $e->getMessage();
    		exit;
		}
	}
}
