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

		            $labelCmd = \SpaShares::find($sh['id'], array('label_service', 'label_product'));
					$labelService = json_decode($labelCmd->label_service);
					$labelProduct = json_decode($labelCmd->label_product);

					$labelServiceCmd = ($labelService) ? \SpaService::whereIn('id', $labelService)->get(array('id', 'title'))->toArray() : array();
					$labelProductCmd = ($labelProduct) ? \SpaProduct::whereIn('id', $labelProduct)->get(array('id', 'title'))->toArray() : array();
		            $shares[] = array(
		            	'share'=>$sh,
		            	'tab'=>$tabs,
		            	'labelService'=>$labelServiceCmd,
		            	'labelProduct'=>$labelProductCmd
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
			if($this->getLocale()!=$article->language){
				$refId = $article->reference;
				$article = \SpaShares::find($refId);
			}

			if(empty($article))
				return \Redirect::route('spa.share');
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

			$labelCmd = \SpaShares::find($id, array('label_service', 'label_product'));
			$labelService = json_decode($labelCmd->label_service);
			$labelProduct = json_decode($labelCmd->label_product);

			$labelServiceCmd = ($labelService) ? \SpaService::whereIn('id', $labelService)->get(array('id', 'title'))->toArray() : array();
			$labelProductCmd = ($labelProduct) ? \SpaProduct::whereIn('id', $labelProduct)->get(array('id', 'title'))->toArray() : array();

	        return \View::make('spa.share.view_share_detail', array(
	        												  'article'=>$article,
	        												  'image'=>$image,
	        												  'gallery'=>$gallery,
	        												  'prevArticle'=>$prevArticle,
	        												  'nextArticle'=>$nextArticle,
	        												  'tabs'=>$tabs,
	        												  'labelService'=>$labelServiceCmd,
	        												  'labelProduct'=>$labelProductCmd
	        												  ));
		}catch(Exception $e) {
			return Redirect::route('spa.index');
		}
	}



}