<?php
/*
 * this controller is used to handle all request of wintness
 */
class WintnessController extends BaseController{
    /*
     * display article edit page
     * @params (mixed) $id, default: null
     */
    public function getArticleAction($id=null){
        try{
            $article = array('id'=>null, 'status'=>1, 'isInSiderbar'=>0);

            if ($id!=null){
                $article = Wintness::find($id)->toArray();
                if (empty($article))
                    throw new Exception('Error request [10]');
            }

            $imgUploaderList = array(
                'cover' => array('fieldName'=>'cover', 'items'=>null),
                'before' => array('fieldName'=>'img_before', 'items'=>null),
                'after' => array('fieldName'=>'img_after', 'items'=>null),
                'gallery' => array('fieldName'=>'gallery', 'items'=>null),
            );

            foreach($imgUploaderList as $key=>$val){
                $value = Arr::get($article, $val['fieldName'], null);
                $imgUploaderList[$key]['items']  = (empty($value)) ? array() : json_decode($value, true);
            }

            // detect is the request has tabs
            $rows = Tabs::where('type', '=', 'wintness')
                        ->where('item_id', '=', $article['id'])
                        ->orderBy('sort', 'asc')
                        ->get(array('title', 'content', 'sort'));

            $tabItems = array();
            if (!empty($rows)){
                foreach($rows as $r)
                    $tabItems[] = $r->toArray();
            }else{
                if ((($tabNames = Input::old('tabName', null)) !== null) && (($tabContents = Input::old('tabContents', null)) !== null)) {
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

            $labelItmes = array('service'=>array(), 'faq'=>array());
            $list = ServiceFaq::where("status", "=", 'Y')
                              ->where('_parent', '<>', 'N')
                              ->orderBy('_parent', 'desc')
                              ->orderBy('sort', 'desc')
                              ->orderBy('updated_at', 'desc')
                              ->get(array('id', 'title', 'type'));
            foreach($list as $item)
                $labelItmes[$item->type][$item->id] = $item->title;

            $labelSelected = WintnessLabels::where('wid', '=', $article['id'])
                                           ->lists('label_id');

            return View::make('admin.wintness.view_article_action', array(
                'article'=>$article,
                'labelItems' => &$labelItmes,
                'labelSelected' => &$labelSelected,
                'tabItems' => &$tabItems,
                'imgUploaderList' => &$imgUploaderList
            ));

        }catch(Exception $e){
            return Redirect::route('admin.wintness.article.list', array('errorMessage'=>$e->getMessage()));
        }
    }

    /*
     * list all article by specific category or non-category
     * @params (int) $page
     */
    public function getArticleList($page=1){

        $limit = 10;
        $offset = ($page-1) * $limit;

        $cmd = new Wintness;
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
            'route' => 'admin.wintness.article.list',
            'params' => &$params
        );

        return View::make('admin.wintness.view_article_list', array(
            'articles' => &$articles,
            'pagerParam' => &$widgetParam
        ));
    }

    /*
     * list all photo in gallery
     * @params (int) $page
     */
    public function getGallery($page=1){
        $limit = 10;
        $offset = ($page-1) * $limit;
        $photos = WintnessGallery::skip($offset)
                                 ->take($limit)
                                 ->orderBy('sort', 'desc')
                                 ->orderBy('updated_at', 'desc')
                                 ->get();

        $rowsNum = WintnessGallery::count();
        $widgetParam = array(
            'currPage' => $page,
            'total' => $rowsNum,
            'perPage' => $limit,
            'URL' => null,
            'route' => 'admin.wintness.gallery',
        );

        return View::make('admin.wintness.view_gallery', array(
            'photos' => &$photos,
            'wp' => &$widgetParam
        ));
    }

    /*
     * display action page of gallery
     * @params (int) $id
     */
    public function getGalleryAction($id=null){
        $data = array();
        $isNew = true;
        if ($id!=null){
            $model = WintnessGallery::find($id);
            if (!empty($model)){
                $data = $model->toArray();
                $isNew = false;
            }
        }

        return View::make('admin.wintness.view_gallery_action', array(
            'data' => &$data,
            'isNew' => $isNew
        ));
    }

    /*
     * handle delete article by specific id
     * @params (int) $id
     */
    public function postArticleDelete(){
        try{
            $id = Arr::get($_POST, 'id', null);

            if (empty($id))
                throw new Exception("Error request [10]");

            $m = Wintness::find($id);

            if (empty($m))
                throw new Exception('Error request [11]');

            $fields = array('cover', 'img_before', 'img_after', 'gallery');
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
                fps::getInstance()->delete($img);
            return Response::json(array('status'=>'ok', 'message'=>'success'));
        }catch(Exception $e){
            return Response::json(array('status'=>'ok', 'message'=>$e->getMessage()));
        }
    }

    /*
     * handle request of gallery action
     */
    public function postGalleryAction(){
        try{
            $id = Arr::get($_POST, 'id', null);
            $imageURL = Arr::get($_POST, 'imageURL', null);
            $title = Arr::get($_POST, 'title', 'unknown');
            $link = Arr::get($_POST, 'link', '#');
            $target = Arr::get($_POST, 'target', '_self');
            $sort = (int) Arr::get($_POST, 'sort', 1);
            $status = Arr::get($_POST, 'status', '1');
            $imgList = Arr::get($_POST, 'imgList', '');

            if ($imageURL==null)
                throw new Exception("Error request [10]");


            $m = (!empty($id)) ? WintnessGallery::find($id) : new WintnessGallery;

            if ($m==null)
                throw new Exception("Error request [11]");

            $m->title = $title;
            $m->imageURL = $imageURL;
            $m->link = $link;
            $m->target = $target;
            $m->sort = $sort;
            $m->status = $status;
            $m->updated_at = date('Y-m-d H:i:s');
            if (!$m->save())
                throw new Exception("Error request [110]");

            if (!empty($imgList)){
                $list = explode('=sep=', $imgList);
                foreach($list as $item)
                    if (md5($item)!=md5($image)) fps::getInstance()->delete($item);
            }
            return Redirect::route('admin.wintness.gallery', array('page'=>1, 'message'=>'success'));
        }catch(Exceptions $e){
            return Redirect::route('admin.wintness.gallery', array('message'=>$e->getMessage()));
        }

    }

    /*
     * handle delete gallery by specific id
     * @params (int) $id
     */
    public function getGalleryDelete(){
        try{
            $id = Arr::get($_GET, 'id', null);
            if (empty($id))
                throw new Exception("Error request [10]");

            $m = WintnessGallery::find($id);

            if (empty($m))
                throw new Exception('Error request [11]');

            $file = $m->imageURL;

            if (!$m->delete())
                throw new Exception("Error request [110]");

            fps::getInstance()->delete($file);
            return Redirect::route('admin.wintness.gallery', array('page'=>1, 'message'=>'success'));
        }catch(Exception $e){
            return Redirect::route('admin.wintness.gallery', array('page'=>1, 'message'=>'error'));
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

            $id = (int) Arr::get($_POST, 'id');
            $sort = (int) Arr::get($_POST, 'sort');
            $isUpdatedTime = Arr::get($_POST, 'isUpdatedTime', false);
            $lastUpdatedId = Arr::get($_POST, 'lastUpdatedId', false);

            $model = ($type=='gallery') ? WintnessGallery::find($id) : Wintness::find($id);

            if (empty($model))
                throw new Exception("Error request [11]");

            $model->sort = $sort;

            if (!$model->save())
                throw new Exception("更新排序失敗，請通知工程師");

            if ($isUpdatedTime){
                $orm = ($type=='gallery') ? 'WintnessGallery' : 'Wintness';
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

            return Response::json(array(
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

    /*
     * handle request of write
     */
    public function postArticleAction(){
        try {
            if (!isset($_POST['id']))
                throw new Exception("Error Processing Request [10]");

            $id = (int) Arr::get($_POST, 'id', null);
            if (empty($id))
                $model = new Wintness;
            else{
                $model = Wintness::find($id);
                if ($model==null)
                    throw new Exception("Error Processing Request [11]");
            }

            // collect image uploader
            /*
             * (array) currentList
             * (string) competeKey
             * (array) newList
             * (string) deleteFieldName
             */
            $delImages = Arr::get($_POST, 'deleteImages', array());
            $delLength = sizeof($delImages);
            for($i=0; $i<$delLength; $i++)
                $delLength[$i] = basename($delLength);

            $imgUploaderList = array(
                'cover' => array('fieldName'=>'cover', 'items'=>null),
                'before' => array('fieldName'=>'img_before', 'items'=>null),
                'after' => array('fieldName'=>'img_after', 'items'=>null),
                'gallery' => array('fieldName'=>'gallery', 'items'=>null),
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
                $imagesDesc = Input::get($descFieldName, array());
                $images = Input::get($val['fieldName'], array());

                foreach ($images as $idx=>$image){
                    $list[] = array(
                        'id' => basename($image),
                        'image' => $image,
                        'text' => $imagesDesc[$idx],
                    );
                }

                $imgUploaderList[$key]['items'] = $list;
            }



            $status = (int) Arr::get($_POST, 'status', 0);
            $isInSiderbar = (int) Arr::get($_POST, 'isInSiderbar', 0);

            $model->title = Input::get('title');
            $model->background_color = Input::get('background_color', '#ccc');
            $model->cover = json_encode($imgUploaderList['cover']['items']);
            $model->img_before = json_encode($imgUploaderList['before']['items']);
            $model->img_after = json_encode($imgUploaderList['after']['items']);
            $model->description = Input::get('description', '');
            $model->gallery = json_encode($imgUploaderList['gallery']['items']);
            $model->status = $status % 2;
            $model->isInSiderbar = $isInSiderbar % 2;
            $model->created_at = time();
            $model->updated_at = time();
            $model->save();


            WintnessLabels::where('wid', '=', $model->id)
                          ->delete();

            $types = array('service', 'faq');
            foreach($types as $type){
                $fieldName = 'label_' . $type;
                $labels  = Input::get($fieldName, array());
                foreach ($labels as $label)
                    WintnessLabels::create(array('wid'=>(int) $model->id, 'label_id'=>((int) $label)));
            }

            Tabs::where('type', '=', 'wintness')
                ->where('item_id', '=', $model->id)
                ->delete();
            // collect tabs
            $tabContents = Input::get('tabContents', array());
            $tabs        = array();
            $tabName = Input::get('tabName', array());
            $order = 1;
            foreach ($tabName as $key => $tab){
                if (!isset($tabContents[$key]))
                    continue;
                else
                    Tabs::create(array('type'=>'wintness', 'item_id'=>$model->id, 'title'=>$tab, 'content'=>$tabContents[$key], 'sort'=>($order++)));
            }

            return Redirect::route('admin.wintness.article.list', array('page'=>1, 'message'=>'success'));
        }catch (Exception $e) {
            return Redirect::back()->withInput()->withErrors($e->getMessage());
        }
    }
}
?>