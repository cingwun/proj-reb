<?php 
namespace spa;

class ShareController extends \BaseController {

	public function getShare() {

		$shareArticle = \SpaShares::where('language', 'tw')
								  ->where('status', '1')
						   		  ->orderBy('sort', 'desc')
						   		  ->get(array('id', 'title', 'cover', 'description'))
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
				foreach($labels as $la) {
					$labelService = array();
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
		            $labelProduct = array();
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
	}
}