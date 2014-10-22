<?php
/*
 * this controller is used to handle all request of serivce
 */
class ServiceFaqController extends BaseController{
    protected $filterExcept = array();
/*
    public function __construct(){
        parent::permissionFilter();
    }*/

    protected function beforeAction($actionType){
        $actionType .= 's';
        parent::permissionFilter($actionType);
    }

    /*
     * display article edit page
     */
    public function getArticleAction($type, $id=null){
        $this->beforeAction($type);
        try {
            $articleLang = Input::get('langList', 'tw');
            $article = array();
            $images = array();
            if ($id!=null){
                $article = ServiceFaq::find($id)->toArray();
                $imgList = ServiceFaqImage::where("sid", "=", $id)->get();
                if (sizeof($imgList)>0){
                    foreach($imgList as $img)
                        $images[] = array('id'=>$img->id, 'image'=>$img->image, 'text'=>$img->text);
                }
            }

            $article['status'] = Arr::get($article, 'status', 'Y');

            $labels = null;
            $tabs = array();

            if (isset($article['labels']))
                $labels = json_decode($article['labels'], true);

            if (isset($article['tabs']))
                $tabs = json_decode($article['tabs'], true);

            // detect is the request has tabs
            $items = array();
            if ((($tabNames = Input::old('tabName', null)) !== null) && (($tabContents = Input::old('tabContents', null)) !== null)) {
                $order = 0;
                foreach ($tabNames as $key => $tab) {
                    if (!isset($tabContents[$key]))
                        continue;
                    $items[] = array(
                        'title' => $tab,
                        'content' => $tabContents[$key],
                        'sort' => $order
                    );
                }
            }

            if (sizeof($items)==0)
                $items = $tabs;

            $labelItmes = array();
            $lblType = ($type=='service') ? 'faq' : 'service';
            $list = ServiceFaq::where("type", "=", $lblType)->where('lang', $articleLang)->where('_parent', '<>', 'N')->get(array(
                    'id',
                    'title'
                ));
            foreach($list as $item)
                $labelItmes[$item->id] = $item->title;
            
            // detect is the request has labels
            return View::make('admin.service_faq.view_article_action', array(
                'type' => $type,
                'article' => &$article,
                'categories' => ServiceFaq::where("type", "=", $type)->where("_parent", "=", "N")->where("lang", $articleLang)->get(),
                'images' => &$images,
                'label' => array(
                    'elementId' => 'label-box',
                    'fieldName' => 'labels[]',
                    'formTitle' => '標籤',
                    'items' => &$labelItmes,
                    'selected' => $labels
                ),
                'tab' => array(
                    'elementId' => 'tab-box',
                    'formTitle' => 'Tab項目',
                    'items' => $items
                ),
                'articleLang' => $articleLang
            ));
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }


    /*
     * list all article by specific category or non-category
     */
    public function getArticleList($type){
        $this->beforeAction($type);
        $page = (int) Arr::get($_GET, 'page', 1);
        $category = (int) Arr::get($_GET, 'category', 0);
        $limit = 10;
        $offset = ($page-1) * $limit;
        $cmd = ServiceFaq::where('type', '=', $type);
        $route = 'admin.service_faq.article.list';
        $params = array('type'=>$type);

        $articleLang = Input::get('langList', 'tw');
        $params['langList'] = $articleLang;

        if (!empty($category)){
            $category = (int) $category;
            $cateCmd = ServiceFaq::find($category);
            if($cateCmd->lang != $articleLang)
                $category = $cateCmd->ref;
            $cmd = $cmd->where("_parent", "=", $category); 
            $params['category'] = $category;
        }else
            $cmd = $cmd->where('_parent', '<>', 'N');

        $rowsNum = $cmd->where('lang', $articleLang)
                       ->count();

        $articles = $cmd->where('lang', $articleLang)
                        ->orderBy('sort', 'desc')
                        ->orderBy('updated_at', 'desc')
                        ->skip($offset)
                        ->take($limit)
                        ->get();
        if(!empty($articles)) {
            foreach ($articles as $key => $article) {
                $articles[$key]['ref_display'] = ServiceFaq::where('id', $article->ref)
                                                          ->first(array('status'))
                                                          ->status;
            }
        }
        
        $cats = ServiceFaq::where('type', '=', $type)
                          ->where('_parent', '=', 'N')
                          ->get();
        $categories = array();
        foreach($cats as $cat)
            $categories[$cat->id] = $cat->title;
        unset($cats);

        $widgetParam = array(
            'currPage' => $page,
            'total' => $rowsNum,
            'perPage' => $limit,
            'URL' => null,
            'route' => $route,
            'params' => &$params
        );

        $twListUrl = URL::route('admin.service_faq.article.list', array('type'=>$type, 'langList'=>'tw', 'category'=>$category));
        $cnListUrl = URL::route('admin.service_faq.article.list', array('type'=>$type, 'langList'=>'cn', 'category'=>$category));
        $category = Arr::get($categories, $category, '');

        $langControlGroup = array(
            'tw' => 'cn',
            'cn' => 'tw'
        );

        return View::make('admin.service_faq.view_article_list', array(
            'type' => $type,
            'articles' => &$articles,
            'category' => $category,
            'categories' => &$categories,
            'pagerParam' => &$widgetParam,
            'articleLang' => $articleLang,
            'twListUrl' => $twListUrl,
            'cnListUrl' => $cnListUrl,
            'langControlGroup' => $langControlGroup
        ));
    }

    /*
     * list all category
     */
    public function getCategoryList($type, $page=1){
        $this->beforeAction($type);
        //$limit = 10;
        //$offset = ($page-1) * $limit;
        //$rowsNum = ServiceFaq::where("type", "=", $type)
        //                     ->where("_parent", "=", "N")
        //                     ->count();

        $catsTW = ServiceFaq::where("type", "=", $type)
                          ->where("_parent", "=", "N")
                          ->where("lang",'tw')
                          ->orderBy('sort', 'desc')
                          ->orderBy('updated_at', 'desc')
                          ->get();
        $catsCN = ServiceFaq::where("type", "=", $type)
                          ->where("_parent", "=", "N")
                          ->where("lang",'cn')
                          ->orderBy('sort', 'desc')
                          ->orderBy('updated_at', 'desc')
                          ->get();
        
        $cats = array(
            'twList' => $catsTW,
            'cnList' => $catsCN
        );

        /*$widgetParam = array(
            'currPage' => $page,
            'total' => $rowsNum,
            'perPage' => $limit,
            'URL' => null,
            'route' => 'admin.service_faq.category.list',
            'params' => array('type'=>$type)
        );*/

        return View::make('admin.service_faq.view_category_list', array(
            'type' => $type,
            'cats' => &$cats
        ));
    }

    /*
     * hadnle AJAX request for delete item
     * @params (string) $type
     */
    public function postDelete($type){
        $this->beforeAction($type);
        try{
            if (!isset($_POST) || !isset($_POST['id']))
                throw new Exception('Error request [10]');

            $catId = (int) $_POST['id'];
            $catCmd = ServiceFaq::find($catId);
            if (empty($catCmd))
                throw new Exception("Error request");
            $catCmdArray = array($catCmd->id, $catCmd->ref);
            foreach ($catCmdArray as $cat) {
                $articles = ServiceFaq::where('_parent', '=', $cat)
                                      ->get();
                $imgs = array();
                if (sizeof($articles)>0){
                    foreach($articles as $article){
                        $tabs = json_decode($article->tabs, true);
                        if (sizeof($tabs)>0){
                            foreach($tabs as $tab){
                                preg_match_all('/<img [^>]*src=["|\']([^"|\']+)/i', $tab['content'], $matches);
                                if (isset($matches[1])){
                                    foreach($matches[1] as $key=>$img)
                                        $imgs[] = $img;
                                }
                            }
                        }

                        $images = ServiceFaqImage::where('sid', '=', $article->id);
                        if (sizeof($images)>0){
                            foreach($images as $img){
                                $imgs[] = $img->image;
                                $img->delete();
                            }
                        }

                        if (!empty($article->image))
                            $imgs[] = $article->image;

                        $article->delete();
                    }
                }

                if (sizeof($imgs)>0){
                    $fps = new fps;
                    while($img=array_pop($imgs))
                        $fps->delete($img);
                }
            }
            ServiceFaq::whereIn('id', $catCmdArray)->delete();
            return Response::json(array(
                    'status' => 'ok',
                    'message' => '刪除完成!',
                ));
        }catch(Exception $e){
            return Response::json(array(
                    'status' => 'error',
                    'message' => $e->getMessage()
                ));
        }
    }

    /*
     * handle AJAX request of change category item
     * @params (string) $type
     */
    public function postUpdateCategory($type){
        $this->beforeAction($type);
        try{
            if (!isset($_POST))
                throw new Exception('Error request [10]');

            $meta = array(
                array('key'=>'id', 'isRequire'=>false, 'isPrimaryKey'=>true, 'defaultValue'=>false),
                array('key'=>'title', 'isRequire'=>true, 'defaultValue'=>null),
                array('key'=>'status', 'isRequire'=>true, 'defaultValue'=>'Y'),
                array('key'=>'sort', 'isRequire'=>true, 'defaultValue'=>1, 'pattern'=>'/\d+/', 'message'=>'排序格式錯誤，Ex: 10, 99')
            );

            $model = null;
            foreach($meta as $m){
                $key = $m['key'];
                $isPrimaryKey = (isset($m['isPrimaryKey'])) ? $m['isPrimaryKey'] : false;
                $value = (isset($_POST[$key])) ? $_POST[$key] : $m['defaultValue'];

                if (empty($value) && $m['isRequire'])
                    throw new Exception("Error request [11]");

                if (isset($m['pattern'])){
                    if (preg_match($m['pattern'], $value)==false)
                        throw new Exception($m['message'] . " [110]");
                }

                if ($isPrimaryKey){
                    $pk = (int) $value;
                    $model = ($value=='null' || empty($value)) ? new ServiceFaq : ServiceFaq::find($pk);
                    if (empty($model))
                        $model = new ServiceFaq;
                }else
                    $model->$key = $value;
            }

            $model->type = $type;
            if (!$model->save())
                throw new Exception("儲存失敗，請重試一次或通知工程師!");
            //system automatically generates another language,and the corresponding
            $id = Input::get('id',null);
            if(empty($id)){
                $refCmd = new ServiceFaq;
                $refCmd->type = $type;
                $refCmd->title = Input::get('title');
                $refCmd->status = 'N';
                $refCmd->sort = Input::get('sort');
                $refCmd->lang = 'cn';
                $refCmd->ref = $model->id;
                $refCmd->save();

                $cmd = ServiceFaq::find($model->id);
                $cmd->ref = $refCmd->id;
                $cmd->save();
            }
            return Redirect::route('admin.service_faq.category.list', array('type'=>$type));
        }catch(Exception $e){
            echo $e->getMessage();
            exit;
        }
    }

    /*
     * handle AJAX request of change sort for category
     * @params (string) $type
     */
    public function postUpdateSort($type){
        $this->beforeAction($type);
        try{
            if (!isset($_POST) || !isset($_POST['id']) || !isset($_POST['sort']) || !isset($_POST['role']))
                throw new Exception('Error request [10]');

            $id = (int) Arr::get($_POST, 'id');
            $role = Arr::get($_POST, 'role');
            $sort = (int) Arr::get($_POST, 'sort');
            $isUpdatedTime = Arr::get($_POST, 'isUpdatedTime', false);
            $lastUpdatedId = Arr::get($_POST, 'lastUpdatedId', false);

            $model = ServiceFaq::find($id);
            if (empty($model))
                throw new Exception("Error request [11]");

            $model->sort = $sort;

            if (!$model->save())
                throw new Exception("更新排序失敗，請通知工程師");

            if ($isUpdatedTime){
                $cmd = ServiceFaq::where('id', '<>', $id)
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
     * handle request for write article
     * @params (string) $type
     */
    public function postWriteArticle($type){
        $this->beforeAction($type);
        try {
            if (!isset($_POST['id']))
                throw new Exception("Error Processing Request [10]");

            $id = (int) Arr::get($_POST, 'id', null);
            if (empty($id))
                $model = new ServiceFaq;
            else{
                $model = ServiceFaq::find($id);
                if ($model==null)
                    throw new Exception("Error Processing Request [11]");
            }
            $articleLang = Input::get('articleLang', 'tw');
            $labels  = Input::get('labels', array());
            $lblList = array();
            foreach ($labels as $label)
                $lblList[] = (int) $label;
            $order       = 0;
            $tabContents = Input::get('tabContents', array());
            $tabs        = array();
            foreach (Input::get('tabName', array()) as $key => $tab) {
                if (!isset($tabContents[$key]))
                    continue;
                $tabs[] = array(
                    'title' => $tab,
                    'content' => $tabContents[$key],
                    'sort' => $order
                );
                $order++;
            }
            //$model             = new ServiceFaq;
            $model->type       = $type;
            $model->title      = Input::get('title');
            $model->image      = Input::get('image_path');
            $model->content    = Input::get('content');
            $model->labels     = json_encode($lblList);
            $model->tabs       = json_encode($tabs);
            $model->status     = Input::get('status');
            $model->_parent    = Input::get('category');
            $model->lang = $articleLang;
            //$model->created_at = time();
            $model->updated_at = time();
            $model->meta_keywords = Input::get('meta_keywords');
            $model->meta_desc = Input::get('meta_desc');
            $model->save();

            # Handling Images
            $imgs = ServiceFaqImage::where('sid', '=', $model->id)
                                   ->get();
            $delImages = Arr::get($_POST, 'deleteImages', array());
            if (sizeof($imgs)>0){
                $delLength = sizeof($delImages);
                foreach($imgs as $img){
                    if ($delLength>0 && in_array($img->id, $delImages))
                        fps::getInstance()->delete($img->image);
                    $img->delete();
                }
            }
            $order = 1;
            $imagesDesc = Input::get('imageDesc', array());
            foreach (Input::get('images', array()) as $key => $image) {
                ServiceFaqImage::create(array(
                    'sid' => $model->id,
                    'image' => $image,
                    'text' => $imagesDesc[$key],
                    'sort' => $order
                ));
                $order++;
            }
            //system automatically generates another language,and the corresponding
            if(empty($id)) {
                $langControlGroup = array(
                    'tw' => 'cn',
                    'cn' => 'tw'
                );
                $refModel = new ServiceFaq;

                $cateCmd = ServiceFaq::find(Input::get('category'));

                $refModel->type = $type;
                $refModel->title = Input::get('title');
                $refModel->image = "";
                $refModel->content = Input::get('content');
                $refModel->labels = json_encode($lblList);
                $refModel->tabs = json_encode($tabs);
                $refModel->status = Input::get('status');
                $refModel->_parent = $cateCmd->ref;
                $refModel->lang = $langControlGroup[$articleLang];
                $refModel->status = 'N';
                $refModel->ref = $model->id;
                //$refModel->created_at = time();
                $refModel->updated_at = time();
                $refModel->meta_keywords = Input::get('meta_keywords');
                $refModel->meta_desc = Input::get('meta_desc');
                $refModel->save();

                //set ref
                $ModelCmd = ServiceFaq::find($model->id);
                $ModelCmd->ref = $refModel->id;
                $ModelCmd->save();
            }

            return Redirect::route('admin.service_faq.article.list', array('type'=>$model->type, 'category'=>$model->_parent, 'afterAction'=>1));
        }catch (Exception $e) {
            return Redirect::back()->withInput()->withErrors($e->getMessage());
        }
    }

    /*
     * Display category action page
     * params (int) $id
     */
    public function getCategoryAction($type,$id = null){
        $this->beforeAction($type);
        try {
            $action = 'create';

            $writeURL = URL::route('admin.service_faq.category.update',array('type'=>$type));
            $category = array();
            if(!empty($id)) {
                $action = 'edit';
                $category = ServiceFaq::find($id);
            }

            return View::make('admin.service_faq.view_category_action', array(
                'action' => $action,
                'type' => $type,
                'category' => $category,
                'writeURL' => $writeURL
            ));
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }
}
?>