<?php 
namespace spa;

class ShareController extends \BaseController {

	public function getShareList() {

		try {
			$shareArticle = \SpaShares::where('language', $this->getLocale())
									  ->where('status', '1')
							   		  ->orderBy('sort', 'desc')
							   		  ->get(array('id', 'title', 'cover', 'description', 'background_color'))
							   		  ->toArray();
			$shares = array();
			if($shareArticle) {
				foreach($shareArticle as $sh) {
					$tabs = \SpaSharesTabs::where('item_id', $sh['id'])
										  ->get(array('item_id', 'title', 'content'))
										  ->toArray();
					$labels = \SpaSharesLabels::where('share_id', $sh['id'])
											  ->get(array('share_id', 'label_id'))
											  ->toArray();
					$labelService = array();
			        $labelProduct = array();
					foreach($labels as $la) {
			            $items = \SpaService::where('id', $la['label_id'])
			                    			->orderBy('_parent', 'desc')
			                    			->orderBy('sort', 'desc')
			                    			->orderBy('updated_at', 'desc')
			                    			->get(array('id', 'title'));
			            foreach($items as $index=>$item) {
			            	$labelService[] = array(
			                	"id"=>$item->id,
			                	"title"=>$item->title
			                	);
			            }
			            $items = \SpaProduct::where('id', $la['label_id'])
			                    			->orderBy('_parent', 'desc')
			                    			->orderBy('sort', 'desc')
			                    			->orderBy('updated_at', 'desc')
			                    			->get(array('id', 'title'));
			            foreach($items as $item) {
			            	$labelProduct[] = array(
			                	"id"=>$item->id,
			                	"title"=>$item->title
			                	);
						}
		            }
		            $shares[] = array(
		            	'share'=>$sh,
		            	'tab'=>$tabs,
		            	'labelService'=>$labelService,
		            	'labelProduct'=>$labelProduct
		            	);
				}
			}

			return \View::make('spa.share.view_share', array('shares'=>$shares));
		}catch(Exception $e) {
			return Redirect::route('spa.index');
		}
	}

	public function getArticle($id = null) {

		try{

			$article = \SpaShares::where('status', '1')
								 ->find($id);
			if(empty($article))
				throw new \Exception('Error request [11]');
			if(\ViewsAdder::views_cookie('share', $id)) {
              $article->views = $article->views + 1;
              $article->save();
            }
			$image = json_decode($article->image);
			$gallery = json_decode($article->gallery);

			$prevArticle = \SpaShares::where('status', '1')
									 ->where('sort', '>=', $article->sort)
									 ->where('updated_at', '>', $article->updated_at)
									 ->first(array('id', 'title'));
			$nextArticle = \SpaShares::where('status', '1')
									 ->where('sort', '<=', $article->sort)
									 ->where('updated_at', '<', $article->updated_at)
									 ->first(array('id', 'title'));

			$tabs = \SpaSharesTabs::where('item_id', $id)
								  ->get(array('title', 'content'))
								  ->toArray();

			$labels = \SpaSharesLabels::where('share_id', $id)
									  ->get(array('share_id', 'label_id'))
									  ->toArray();
			$labelService = array();
	        $labelProduct = array();
			foreach($labels as $labels) {
	            $items = \SpaService::where('id', $labels['label_id'])
	                    			->orderBy('_parent', 'desc')
	                    			->orderBy('sort', 'desc')
	                    			->orderBy('updated_at', 'desc')
	                    			->get(array('id', 'title'));
	            foreach($items as $index=>$item) {
	            	$labelService[] = array(
	                	"id"=>$item->id,
	                	"title"=>$item->title
	                	);
	            }
	            $items = \SpaProduct::where('id', $labels['label_id'])
	                    			->orderBy('_parent', 'desc')
	                    			->orderBy('sort', 'desc')
	                    			->orderBy('updated_at', 'desc')
	                    			->get(array('id', 'title'));
	            foreach($items as $index=>$item) {
	            	$labelProduct[] = array(
	                	"id"=>$item->id,
	                	"title"=>$item->title
	                	);
	            }
	        }
	        return \View::make('spa.share.view_share_detail', array(
	        												  'article'=>$article,
	        												  'image'=>$image,
	        												  'gallery'=>$gallery,
	        												  'prevArticle'=>$prevArticle,
	        												  'nextArticle'=>$nextArticle,
	        												  'tabs'=>$tabs,
	        												  'labelService'=>$labelService,
	        												  'labelProduct'=>$labelProduct
	        												  ));
		}catch(Exception $e) {
			return Redirect::route('spa.index');
		}
	}



}