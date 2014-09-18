<?php
namespace spaAdmin;

class ShareController extends \BaseController {

    public function getArticleList($page=1){

        $limit = 10;
        $offset = ($page-1) * $limit;

        $cmd = new \SpaShares;
        $rowsNum = $cmd->count();
        $articles = $cmd->orderBy('sort', 'desc')
        ->orderBy('updated_at', 'desc')
        ->skip($offset)
        ->take($limit)
        ->get();

        $widgetParam = array(
            'currPage' => $page,
            'total' => $rowsNum,
            'perPage' => $limit,
            'URL' => null,
            'route' => 'spa.admin.share.article.list',
            'params' => &$params
            );

        return \View::make('spa_admin.shares.view_shares_list', array(
            'articles' => &$articles,
            'pagerParam' => &$widgetParam
            ));
    }

    /*
     * display article edit page
     * @params (mixed) $id, default: null
     */
    public function getArticleAction($id=null, $changeLang=null){
        try{
            $article = array('id'=>null, 'status'=>1, 'isInSiderbar'=>0, 'language'=>1, 'reference'=>0);

            if ($id!=null){
                $article = \SpaShares::find($id)->toArray();
                if (empty($article))
                    throw new Exception('Error request [10]');
            }

            $imgUploaderList = array(
                'cover' => array('fieldName'=>'cover', 'items'=>null),
                //'before' => array('fieldName'=>'img_before', 'items'=>null),
                //'after' => array('fieldName'=>'img_after', 'items'=>null),
                'image' => array('fieldName'=>'image', 'items'=>null),
                'gallery' => array('fieldName'=>'gallery', 'items'=>null),
                );

            foreach($imgUploaderList as $key=>$val){
                $value = \Arr::get($article, $val['fieldName'], null);
                $imgUploaderList[$key]['items']  = (empty($value)) ? array() : json_decode($value, true);
                if(isset($changeLang))
                    $imgUploaderList[$key]['items'] = array();   //I force users upload images while modifying tw/cn lang 'cause I can not fix the bug 
                                                                 //that if user delete reference article and the other's images will be delete, too. Kettan
            }

            // detect whether the request has spa_shares_tabs or not
            $rows = \SpaSharesTabs::where('type', '=', 'shares')
            ->where('item_id', '=', $article['id'])
            ->orderBy('sort', 'asc')
            ->get(array('title', 'content', 'sort'));

            $tabItems = array();
            if (!empty($rows)){
                foreach($rows as $r)
                    $tabItems[] = $r->toArray();
            }else{
                if ((($tabNames = \Input::old('tabName', null)) !== null) && (($tabContents = \Input::old('tabContents', null)) !== null)) {
                    $order = 1;
                    foreach ($tabNames as $key => $tab) {
                        if (!isset($tabContents[$key]))
                            continue;
                        $tabItems[] = array(
                            'title' => $tab,
                            'content' => $tabContents[$key],
                            'sort' => $order
                            );
                    }
                }
            }

            //For Label Using
            $labelItems = array('service'=>array(), 'product'=>array());
            $items = \SpaService::where('_parent', '<>', 'N')
                    ->orderBy('_parent', 'desc')
                    ->orderBy('sort', 'desc')
                    ->orderBy('updated_at', 'desc')
                    ->get(array('id', 'title'));
            foreach($items as $item)
                $labelItems['service'][$item->id] = $item->title;
            
            $items = \SpaProduct::where('_parent', '<>', 'N')
                    ->orderBy('_parent', 'desc')
                    ->orderBy('sort', 'desc')
                    ->orderBy('updated_at', 'desc')
                    ->get(array('id', 'title'));
            foreach($items as $item)
                $labelItems['product'][$item->id] = $item->title;

            $labelSelected = \SpaSharesLabels::where('share_id', '=', $article['id'])
                                              ->lists('label_id');

            /*$labelItmes = array('service'=>array(), 'product'=>array());
            $list = \SpaService::where('_parent', '<>', 'N')
                              ->orderBy('_parent', 'desc')
                              ->orderBy('sort', 'desc')
                              ->orderBy('updated_at', 'desc')
                              ->get(array('id', 'title'));
            foreach($list as $item)
                $labelItmes['service'][$item->id] = $item->title;

            $labelSelected = WintnessLabels::where('wid', '=', $article['id'])
            ->lists('label_id');*/
            
            return \View::make('spa_admin.shares.view_shares_action', array(
                'article'=>$article,
                'labelItems' => &$labelItems,
                'labelSelected' => &$labelSelected,
                'tabItems' => &$tabItems,
                'imgUploaderList' => &$imgUploaderList,
                'changeLang' => &$changeLang
                ));

        }catch(Exception $e){
            return Redirect::route('spa_admin.shares.view_shares_list', array('errorMessage'=>$e->getMessage()));
        }
    }


    /*
     * handle request of write
     */
    public function postArticleAction(){
        try {
            if (!isset($_POST['id']))
                throw new Exception("Error Processing Request [10]");

            $nosort = 0; //
            $id = (int) \Input::get('id', null);
            if (empty($id))
                $model = new \SpaShares;
            elseif(isset($_POST['changeLang'])){
                $model = new \SpaShares;
                $refmodel = \SpaShares::find($id);
            }
            else{
                $model = \SpaShares::find($id);
                $nosort = 1;
            }
            if ($model==null)
                throw new Exception("Error Processing Request [11]");

            // collect image uploader
            /*
             * (array) currentList
             * (string) competeKey
             * (array) newList
             * (string) deleteFieldName
             */
            $delImages = \Input::get('deleteImages', array());
            $delLength = sizeof($delImages);
            for($i=0; $i<$delLength; $i++)
                $delLength[$i] = \basename($delLength);

            $imgUploaderList = array(
                'cover' => array('fieldName'=>'cov', 'items'=>null),/////////////////////////////////////////////////////
                //'before' => array('fieldName'=>'img_before', 'items'=>null),
                'image' => array('fieldName'=>'img', 'items'=>null),
                'gallery' => array('fieldName'=>'galle', 'items'=>null),//////////////////////////////////////////////
                );



            foreach($imgUploaderList as $key=>$val){
                $imgs = json_decode($model->$val['fieldName']);
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



            $status = (int) \Input::get('status', 0);
            $isInSiderbar = (int) \Input::get('isInSiderbar', 0);

            $model->title = \Input::get('title');
            $model->background_color = \Input::get('background_color', '#ccc');
            $model->cover = json_encode($imgUploaderList['cover']['items']);//////////////////////////////////////////////
            //$model->img_before = json_encode($imgUploaderList['before']['items']);
            $model->image = json_encode($imgUploaderList['image']['items']);
            $model->description = \Input::get('description', '');
            $model->gallery = json_encode($imgUploaderList['gallery']['items']);///////////////////////////////////////////
            $model->status = $status % 2;
            $model->isInSiderbar = $isInSiderbar % 2;
            ($nosort == 1) ? $nosort = 1 : $model->sort = \SpaShares::max('sort')+1;
            $model->language = \Input::get('lang');
            $model->save();

            if(isset($refmodel)){
                $model->reference = $id;
                $refmodel->reference = $model->id;
                $model->save();
                $refmodel->save();
            }

            \SpaSharesLabels::where('share_id', '=', $model->id)
            ->delete();

            $types = array('service', 'product');
            foreach($types as $type){
                $fieldName = 'label_' . $type;
                $labels  = \Input::get($fieldName, array());
                foreach ($labels as $label){
                    \SpaSharesLabels::create(array('share_id'=>(int) $model->id, 'label_id'=>((int) $label)));
                    $insertShare[] = $label;
                }
            }


            /*WintnessLabels::where('wid', '=', $model->id)
                          ->delete();

            $types = array('shares');
            foreach($types as $type){
                $fieldName = 'label_' . $type;
                $labels  = \Input::get($fieldName, array());
                foreach ($labels as $label)
                    WintnessLabels::create(array('wid'=>(int) $model->id, 'label_id'=>((int) $label)));
                }*/

                \SpaSharesTabs::where('type', '=', 'shares')
                ->where('item_id', '=', $model->id)
                ->delete();
            // collect SpaSharesTabs
                $tabContents = \Input::get('tabContents', array());
                $tabs        = array();
                $tabName = \Input::get('tabName', array());
                $order = 1;
                foreach ($tabName as $key => $tab){
                    if (!isset($tabContents[$key]))
                        continue;
                    else
                        $tabInner = new \SpaSharesTabs;
                    $tabInner->type = 'shares';
                    $tabInner->item_id = $model->id;
                    $tabInner->title = $tab;
                    $tabInner->content = $tabContents[$key];
                    $tabInner->sort = $order++;
                    $tabInner->save();
                }

                return \Redirect::route('spa.admin.share.article.list', array('page'=>1, 'message'=>'success'));
            }catch (Exception $e) {
                return Redirect::back()->withInput()->withErrors($e->getMessage());
            }
        }


    /*
     * handle delete article by specific id
     * @params (int) $id
     */
    public function postArticleDelete(){
        try{
            $id = \Input::get('id', null);

            if (empty($id))
                throw new Exception("Error request [10]");

            $m = \SpaShares::find($id);

            if (empty($m))
                throw new Exception('Error request [11]');

            if($m->reference!=0){
                $refmodel = \SpaShares::find($m->reference);
                $refmodel->reference = 0;
                $refmodel->save();
            }

            $fields = array('cover', 'img', 'gallery');
            $images = array();
            foreach($fields as $field){
                $imgs = json_decode($m->$field);
                if (empty($imgs))
                    continue;

                foreach($imgs as $img)
                    $images[] = $img->image;
            }

            if (!$m->delete())
                throw new Exception("Error request [110]");

            foreach($images as $img)
                \fps::getInstance()->delete($img);
            return \Response::json(array('status'=>'ok', 'message'=>'success'));
        }catch(Exception $e){
            return \Response::json(array('status'=>'ok', 'message'=>$e->getMessage()));
        }
    }

    /*
     * handle AJAX request of change sort for category
     * @params (string) $type
     */
    public function postUpdateSort($type){
        try{
            if (!isset($_POST) || !isset($_POST['id']) || !isset($_POST['sort']))
                throw new Exception('Error request [10]');

            $id = (int) \Input::get('id');
            $sort = (int) \Input::get('sort');
            $isUpdatedTime = \Input::get('isUpdatedTime', false);
            $lastUpdatedId = \Input::get('lastUpdatedId', false);

            //$model = ($type=='gallery') ? WintnessGallery::find($id) : \SpaShares::find($id);
            $model = \SpaShares::find($id);

            if (empty($model))
                throw new Exception("Error request [11]");

            $model->sort = $sort;

            if (!$model->save())
                throw new Exception("更新排序失敗，請通知工程師");

            if ($isUpdatedTime){
                //$orm = ($type=='gallery') ? 'WintnessGallery' : 'Wintness';
                $orm = 'SpaShares';
                $cmd = $orm::where('id', '<>', $id)
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
            return \Response::json(array(
                'status' => 'error',
                'message' => $e->getMessage()
                ));
        }
    }




}