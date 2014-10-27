<?php
namespace spaAdmin;

/*
 * Manage AboutSpa/ News/ Oversea articles.
 */
class ArticleController extends \BaseController {

	protected function beforeAction($actionType){
        $actionType .= 's';
        parent::permissionFilter($actionType);
    }

	/**
	 * Get specific categroy articles' list.
	 * @params (string) $category about / news / oversea
	 *
	 */
	public function getList($category = 'about', $lang = 'all') {
		try{
			$cmd = new \SpaArticles;
			$Articles = array();
			if($lang!='all')
				$cmd = \SpaArticles::where('lang', $lang);
			$Articles = $cmd->where('category', $category)
							->orderBy('sort', 'desc')
							->get();

			if($Articles)
				return \View::make('spa_admin.articles.view_list', array(
					'category'=>$category,
					'selectedArticles'=>$Articles,
					'lang'=>$lang
					));
		}catch(Exception $e){
			return Redirect::route('spa.admin.articles.list', array('errorMessage'=>$e->getMessage()));
		}catch (Exception $e) {
			return Redirect::route('spa.admin.index');
		}
	}


	/*
	 * Get the form for create new article or modify a specific article.
	 * @params (int) $id, (string) $changeLang, (string) $Category
	 *
	 */
	public function getAction($id = null, $changeLang = null, $Category = null) {
		if(empty($id)){
			$specArticle = array(
							'id'=>0,
							'status'=>1,
							'lang'=>'tw',
							);

			$imgUploaderList = array(
                'cover' => array('fieldName'=>'cover', 'items'=>null)
                );
            foreach($imgUploaderList as $key=>$val){
                $value = \Arr::get($specArticle, $val['fieldName'], null);
                $imgUploaderList[$key]['items']  = (empty($value)) ? array() : json_decode($value, true);
                if(isset($changeLang))
                    $imgUploaderList[$key]['items'] = array();
            }

			return \View::make('spa_admin.articles.view_articles_action', array(
				'specArticle'=>$specArticle,
				'createCategory'=>$Category,
				'imgUploaderList' => $imgUploaderList
				));
		}else {
			$specArticle = \SpaArticles::find($id)->toArray();

			$imgUploaderList = array(
                'cover' => array('fieldName'=>'cover', 'items'=>null)
                );
            foreach($imgUploaderList as $key=>$val){
                $value = \Arr::get($specArticle, $val['fieldName'], null);
                $imgUploaderList[$key]['items']  = (empty($value)) ? array() : json_decode($value, true);
                if(isset($changeLang))
                    $imgUploaderList[$key]['items'] = array();
            }

			return \View::make('spa_admin.articles.view_articles_action', array(
				'specArticle'=>$specArticle,
				'changeLang'=>$changeLang,
				'createCategory'=>$Category,
				'imgUploaderList'=>$imgUploaderList
				));
		}
	}

	/**
	 * Save the modidied article into database.
	 * @params (int) $id, (string) $changeLang
	 *
	 */
	public function postAction($id = null, $changeLang = null) {

		try
		{
			$sort = 'do';
			if(empty($id)){
				$article = new \SpaArticles;
			}
			elseif($changeLang=="modifyLanguage"){
				$refArticle = \SpaArticles::find($id);

				$newArticle = new \SpaArticles;

				$imgUploaderList = array(
                'cover' => array('fieldName'=>'cov', 'items'=>null),
                );
				foreach($imgUploaderList as $key=>$val){
	                $imgs = json_decode($newArticle->$val['fieldName']);
	                if (!empty($imgs) && sizeof($imgs)>0){
	                    foreach($imgs as $img){
	                        if ($delLength>0 && in_array($img->id, $delImages))
	                            fps::getInstance()->delete($img->image);
	                    }
	                }

	                $list = array();
	                $descFieldName = $val['fieldName'] . '_desc';
	                $imagesDesc = \Input::get($descFieldName, array());
	                $images = \Input::get($val['fieldName'], array());

	                foreach ($images as $idx=>$image){
	                    $list[] = array(
	                        'id' => basename($image),
	                        'image' => $image,
	                        'text' => $imagesDesc[$idx],
	                        );
	                }

	                $imgUploaderList[$key]['items'] = $list;
	            }

				$newArticle->title = \Input::get('title');
				$newArticle->cover = json_encode($imgUploaderList['cover']['items']);
				$newArticle->content = \Input::get('content');
				$newArticle->category = \Input::get('category');
				$newArticle->open_at = \Input::get('open_at');
				$newArticle->status = \Input::get('status');
				$newArticle->lang = \Input::get('lang');
				$newArticle->sort = \SpaArticles::max('sort')+1;
				$newArticle->meta_name = \Input::get('meta_name');
				$newArticle->meta_content = \Input::get('meta_content');
				$newArticle->meta_title = \Input::get('meta_title');
				$newArticle->h1 = \Input::get('h1');

				$newArticle->ref_id = $refArticle->id;
				$newArticle->save();

				$refArticle->ref_id = $newArticle->id;
				$refArticle->save();
				return \Redirect::route('spa.admin.articles.list', array('category'=>$newArticle->category));
			}else{
				$article = \SpaArticles::find($id);
				$sort = 'doNot';
			}

			$imgUploaderList = array(
                'cover' => array('fieldName'=>'cov', 'items'=>null),
                );
			foreach($imgUploaderList as $key=>$val){
                $imgs = json_decode($article->$val['fieldName']);
                if (!empty($imgs) && sizeof($imgs)>0){
                    foreach($imgs as $img){
                        if ($delLength>0 && in_array($img->id, $delImages))
                            fps::getInstance()->delete($img->image);
                    }
                }
                $list = array();
                $descFieldName = $val['fieldName'] . '_desc';
                $imagesDesc = \Input::get($descFieldName, array());
                $images = \Input::get($val['fieldName'], array());

                foreach ($images as $idx=>$image){
                    $list[] = array(
                        'id' => basename($image),
                        'image' => $image,
                        'text' => $imagesDesc[$idx],
                        );
                }
                $imgUploaderList[$key]['items'] = $list;
            }

			$article->title = \Input::get('title');
			$article->cover = json_encode($imgUploaderList['cover']['items']);
			$article->content = \Input::get('content');
			$article->category = \Input::get('category');
			$article->open_at = \Input::get('open_at');
			$article->status = \Input::get('status');
			$article->lang = \Input::get('lang');
			$article->meta_name = \Input::get('meta_name');
			$article->meta_content = \Input::get('meta_content');
			$article->meta_title = \Input::get('meta_title');
			$article->h1 = \Input::get('h1');

			if($sort=='do')
				$article->sort = \SpaArticles::max('sort')+1;
			$article->save();
			return \Redirect::route('spa.admin.articles.list', array('category'=>$article->category));
		}catch(Exception $e)
		{
			return Redirect::route('spa.admin.articles.list', array('errorMessage'=>$e->getMessage()));
		}

	}

	/**
	 * Delete a specific article.
	 * @params (int) $id
	 *
	 */
	public function postDelete($id)
	{
		try{
			if(empty($id))
				return \Redirect::route('spa.admin.articles.list');

			$article = \SpaArticles::find($id);
			$category = $article->category;
			if($article->ref_id != 0){
				$refArticle = \SpaArticles::where('id',"=",$article->ref_id)->first();
				$refArticle->ref_id = 0;
				$refArticle->save();
			}
			$article->delete();
			return \Redirect::route('spa.admin.articles.list', array('category'=>$category));
		}catch(Exception $e){
			return Redirect::route('spa.admin.articles.list', array('errorMessage'=>$e->getMessage()));
		}
	}

	/*
	 * Sort articles.
	 * @params (int) $id, (int) $sort, (string) $role
	 *
	 */
	public function postSort(){
		try{
		    if (!isset($_POST) || !isset($_POST['id']) || !isset($_POST['sort']) || !isset($_POST['role']))
		        throw new Exception('Error request [10]');

		    $id = (int) \Input::get('id');
		    $role = \Input::get('role');
		    $sort = (int) \Input::get('sort');
		    $isUpdatedTime = \Input::get('isUpdatedTime', false);
		    $lastUpdatedId = \Input::get('lastUpdatedId', false);

		    $model = \SpaArticles::find($id);
		    if (empty($model))
		        throw new Exception("Error request [11]");

		    $model->sort = $sort;

		    if (!$model->save())
		        throw new Exception("更新排序失敗，請通知工程師");

		    if ($isUpdatedTime){
		        $cmd = \SpaArticles::where('id', '<>', $id)
		                           ->where('sort', '=', $sort)
		                           ->where('id', '>=', $lastUpdatedId)
		                           ->orderBy('sort', 'desc')
		                           ->orderBy('updated_at', 'desc');

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
		            'message' => '更新排序完成',
		        ));

		}catch(Exception $e){
		    return Response::json(array(
		            'status' => 'error',
		            'message' => $e->getMessage()
		        ));
		}
	}


}
