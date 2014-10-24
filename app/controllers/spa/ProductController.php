<?php
namespace spa;

class ProductController extends \BaseController{

	/*
	 * Display product
	 */
	public function getProduct() {
		try {
			$prodCats = array();

			$prodCatsCmd = \SpaProduct::where('_parent', 'N')
									  ->where('display', 'yes')
									  ->where('lang', $this->getLocale())
									  ->orderBy('sort', 'DESC')
									  ->get(array('id', 'title', 'image'))
									  ->toArray();
			if($prodCatsCmd)
				$prodCats = $prodCatsCmd;
			
			$indexURL = \URL::route('spa.index');

			return \View::make('spa.product.view_product', array(
				"prodCats" => $prodCats,
				"indexURL" => $indexURL
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
			$productCmd = \SpaProduct::where('id', $id)
									 ->where('lang', $this->getLocale());
			
			if($productCmd->first()) {
				$product = $productCmd->first()
									  ->toArray();
			}else {
				throw new \Exception("the product $id data is not exist");
				exit;
			}
			
			//set views
			if(\ViewsAdder::views_cookie('product', $id)) {
				$productCmd = $productCmd->first();
            	$productCmd->views = $productCmd->views + 1;
              	$productCmd->save();
			}

			$productCat = \SpaProduct::find($product['_parent'])
									 ->toArray();
			
			$productListURL = \URL::route('spa.product.list', array('cat'=>$productCat['id']));
			$productURL = \URL::route('spa.product');
			$indexURL = \URL::route('spa.index');

			return \View::make('spa.product.view_product_detail', array(
				'categorys' => $categorys,
				'product' => $product,
				'productCat' => $productCat,
				'productListURL' => $productListURL,
				'productURL' => $productURL,
				'indexURL' => $indexURL
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
									   ->orderBy('sort', 'DESC')
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
			$productsCmd = $productsCmd->orderBy('sort', 'DESC')
									   ->skip($offset)
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
			$indexURL = \URL::route('spa.index');

			return \View::make('spa.product.view_product_list',array(
				'categorys' => $categorys,
				'products' => $products,
				'productListURL' => $productListURL,
				'pageURL' => $pageURL,
				'page' => $page,
				'rowsNum' => $rowsNum,
				'productDetailURL' => $productDetailURL,
				'productCat' => $productCat,
				'productURL' => $productURL,
				'indexURL' => $indexURL
			));
		} catch (Exception $e) {
			echo $e->getMessage();
			exit;
		}
	}
}

?>