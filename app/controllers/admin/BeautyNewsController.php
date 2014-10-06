<?php
/*
 * this controller is used to handle all request of beauty news
 */
class BeautyNewsController extends BaseController{
    /*
     * display article edit page
     * @params (mixed) $id, default: null
     */
    public function getAction($id=null){
        try{
            $article = array('id'=>null, 'status'=>1);

            if ($id!=null){
                $article = BeautyNews::find($id)->toArray();
                if (empty($article))
                    throw new Exception('Error request [10]');
            }

            $imgUploaderList = array(
                'cover' => array('fieldName'=>'cover', 'items'=>null),
                'fb' => array('fieldName'=>'fb', 'items'=>null),
            );

            foreach($imgUploaderList as $key=>$val){
                $value = Arr::get($article, $val['fieldName'], null);
                $imgUploaderList[$key]['items']  = (empty($value)) ? array() : json_decode($value, true);
            }

            return View::make('admin.beautynews.view_action', array(
                'article' => $article,
                'imgUploaderList' => &$imgUploaderList,
                'lang' => Input::get('lang','tw')
            ));

        }catch(Exception $e){
            return Redirect::route('admin.beautynews.list', array('errorMessage'=>$e->getMessage()));
        }
    }

    /*
     * list all article by specific category or non-category
     * @params (int) $page
     */
    public function getList($page=1){
        $lang = Input::get('lang','tw');
        $limit = 10;
        $offset = ($page-1) * $limit;

        $cmd = new BeautyNews;
        $rowsNum = $cmd->count();
        $articles = $cmd->where('lang', $lang)
                        ->orderBy('sort', 'desc')
                        ->orderBy('updated_at', 'desc')
                        ->skip($offset)
                        ->take($limit)
                        ->get();

        $widgetParam = array(
            'currPage' => $page,
            'total' => $rowsNum,
            'perPage' => $limit,
            'URL' => null,
            'route' => 'admin.beautynews.list',
            'params' => &$params
        );

        return View::make('admin.beautynews.view_list', array(
            'articles' => &$articles,
            'pagerParam' => &$widgetParam,
            'lang' => $lang
        ));
    }

    /*
     * handle delete article by specific id
     * @params (int) $id
     */
    public function postDelete(){
        try{
            $id = Arr::get($_POST, 'id', null);

            if (empty($id))
                throw new Exception("Error request [10]");

            $m = BeautyNews::find($id);

            if (empty($m))
                throw new Exception('Error request [11]');

            $fields = array('cover', 'fb');
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
     * handle AJAX request of change sort
     */
    public function postUpdateSort(){
        try{
            if (!isset($_POST) || !isset($_POST['id']) || !isset($_POST['sort']))
                throw new Exception('Error request [10]');

            $id = (int) Arr::get($_POST, 'id');
            $sort = (int) Arr::get($_POST, 'sort');
            $isUpdatedTime = Arr::get($_POST, 'isUpdatedTime', false);
            $lastUpdatedId = Arr::get($_POST, 'lastUpdatedId', false);

            $model = BeautyNews::find($id);

            if (empty($model))
                throw new Exception("Error request [11]");

            $model->sort = $sort;

            if (!$model->save())
                throw new Exception("更新排序失敗，請通知工程師");

            if ($isUpdatedTime){
                $cmd = BeautyNews::where('id', '<>', $id)
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
    public function postAction(){
        try {
            if (!isset($_POST['id']))
                throw new Exception("Error Processing Request [10]");

            $id = (int) Arr::get($_POST, 'id', null);
            if (empty($id))
                $model = new BeautyNews;
            else{
                $model = BeautyNews::find($id);
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
                'fb' => array('fieldName'=>'fb', 'items'=>null),
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
            $lang = Input::get('lang', 'tw');

            $model->title = Input::get('title');
            $model->style = Arr::get($_POST, 'style', 1);
            $model->cover = json_encode($imgUploaderList['cover']['items']);
            $model->fb = json_encode($imgUploaderList['fb']['items']);
            $model->link = Arr::get($_POST, 'link', '#');
            $model->target = Arr::get($_POST, 'target', '_self');
            $model->description = Arr::get($_POST, 'description', '');
            $model->status = $status % 2;
            $model->sort = (int) Arr::get($_POST, 'sort', 1);
            $model->lang = $lang;
            $model->created_at = time();
            $model->updated_at = time();
            $model->save();
            //system auto create other language
            if(empty($id)){
                $langContrast = array(
                    'tw'=>'cn',
                    'cn'=>'tw',
                );
                $refModel = new BeautyNews;

                $refModel->title = Input::get('title');
                $refModel->style = Arr::get($_POST, 'style', 1);
                $refModel->cover = "";
                $refModel->fb = "";
                $refModel->link = Arr::get($_POST, 'link', '#');
                $refModel->target = Arr::get($_POST, 'target', '_self');
                $refModel->description = Arr::get($_POST, 'description', '');
                $refModel->status = 0;
                $refModel->sort = (int) Arr::get($_POST, 'sort', 1);
                $refModel->lang = $langContrast[$lang];
                $refModel->ref = $model->id;
                $refModel->created_at = time();
                $refModel->updated_at = time();
                $refModel->save();

                $cmd = BeautyNews::find($model->id);
                $cmd->ref = $refModel->id;
                $cmd->save();
            }
            return Redirect::route('admin.beautynews.list', array('page'=>1, 'message'=>'success'));
        }catch (Exception $e) {
            return Redirect::back()->withInput()->withErrors($e->getMessage());
        }
    }
}
?>