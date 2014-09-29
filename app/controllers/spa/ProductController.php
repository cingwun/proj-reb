<?php
namespace spa;

class ProductController extends \BaseController{

	/*
	 * Display product
	 */
	public function getProduct() {
		try {
			$products = array();

			$productsCmd = \SpaProduct::where('_parent', '<>', 'N')
									  ->where('display', 'yes')
									  ->where('lang', $this->getLocale())
									  ->get(array('id', 'title', 'image'))
									  ->toArray();
			if($productsCmd)
				$products = $productsCmd;
			
			$detailURL = \URL::route('spa.product.detail');

			return \View::make('spa.product.view_product', array(
				"products" => $products,
				"detailURL" => $detailURL
			));
		} catch (Exception $e) {
			echo $e->getMessage();
			exit;
		}
	}

	/*
	 * Display product derail
	 * params (int) $id
	 */
	public function getProductDetail($id = null) {
		try {
			$categorys = array();
			$categorysCmd = \SpaProduct::where('_parent', 'N') 
									   ->where('display', 'yes')
									   ->where('lang', $this->getLocale())
									   ->get(array('id', 'title'))
									   ->toArray();

			if($categorysCmd)
				$categorys = $categorysCmd;

			$product = array();
			$productCmd = \SpaProduct::find($id);
			//set views
			if(\ViewsAdder::views_cookie('product', $id)) {
				$productCmd->views += 1; 
				$productCmd->save();
			}
			
			if($productCmd)
				$product = $productCmd->toArray();

			$productCat = \SpaProduct::find($product['_parent'])
									 ->toArray();
			
			$productListURL = \URL::route('spa.product.list');
			$productURL = \URL::route('spa.product');

			return \View::make('spa.product.view_product_detail', array(
				'categorys' => $categorys,
				'product' => $product,
				'productCat' => $productCat,
				'productListURL' => $productListURL,
				'productURL' => $productURL
			));
		} catch (Exception $e) {
			echo $e->getMessage();
			exit;
		}
	}

	/*
	 * Display product list
	 * params (int) $cat
	 */
	public function getProductList($cat = null){
		try {
			//setContent categorys
			$categorys = array();
			$categorysCmd = \SpaProduct::where('_parent', 'N')
									   ->where('display', 'yes')
									   ->where('lang', $this->getLocale())
									   ->get(array('id', 'title'))
									   ->toArray();
			if($categorysCmd)
				$categorys = $categorysCmd;
			
			//products
			$page = \Input::get('page', 1);
			$limit = 8;
       		$offset = ($page-1) * $limit;

			$products = array();
			$productsCmd = \SpaProduct::where('_parent', '<>', 'N')
									  ->where('display', 'yes')
									  ->where('_parent', $cat)
									  ->where('lang', $this->getLocale());
			$rowsNum = $productsCmd->count();
			$productsCmd = $productsCmd->skip($offset)
                        			   ->take($limit)
									   ->get()
									   ->toArray();
			if($productsCmd)
				$products = $productsCmd;
			//category
			$productCat = \SpaProduct::where('id', $cat)
									 ->first(array('id', 'title', 'image'))
									 ->toArray();

			$productListURL = \URL::route('spa.product.list');
			$pageURL = \URL::route('spa.product.list', array('cat'=>$cat));
			$productDetailURL = \URL::route('spa.product.detail');
			$productURL = \URL::route('spa.product');

			return \View::make('spa.product.view_product_list',array(
				'categorys' => $categorys,
				'products' => $products,
				'productListURL' => $productListURL,
				'pageURL' => $pageURL,
				'page' => $page,
				'rowsNum' => $rowsNum,
				'productDetailURL' => $productDetailURL,
				'productCat' => $productCat,
				'productURL' => $productURL
			));
		} catch (Exception $e) {
			echo $e->getMessage();
			exit;
		}
	}
}

?>