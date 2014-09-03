<?php
namespace aesthetics;

/*
 * This controller is used to handle request of wintness
 */
class WintnessController extends \BaseController {

    /*
     * dispaly wintness list
     */
    public function getIndex(){
        $gallery = \WintnessGallery::where('status', '=', '1')
                                   ->orderBy('sort', 'desc')
                                   ->orderBy('updated_at', 'desc')
                                   ->get(array('title', 'link', 'target', 'imageURL'));
        if (empty($gallery))
            $gallery = array();

        // read service
        $servicefaq = \ServiceFaq::where('status', '=', 'Y')
                        ->orderBy('type', 'asc')
                        ->orderBy('_parent', 'desc')
                        ->orderBy('sort', 'desc')
                        ->orderBy('updated_at', 'desc')
                        ->get(array('id', 'title', '_parent', 'type'));
        $servicesFaqs = array('service'=>array(), 'faq'=>array());
        foreach($servicefaq as $item){
            $key = $item->id;
            $parent = $item->_parent;
            $list = $servicesFaqs[$item->type];
            if ($parent=='N'){
                if (!isset($list[$key]))
                    $list[$key] = array('id'=>$key, 'title'=>$item->title, 'subItems'=>array());
            }else{
                $list[$parent]['subItems'][] = array('id'=>$key, 'title'=>$item->title);
            }
            $servicesFaqs[$item->type] = $list;
        }

        return \View::make('aesthetics.wintness.view_index', array(
            'bodyId' => 'case',
            'gallery' => &$gallery,
            'servicesFaqs' => &$servicesFaqs
        ));
    }

    /*
     * show article by specific id
     * @params (int) $id
     */
    public function getArticle($id=0){
        try{
            if (empty($id))
                throw new \Exception("Error request [10]");

            $model = \Wintness::where('status', '=', '1')
                              ->find($id);
            if (empty($model))
                throw new \Exception('Error request [11]');

            $list = array(
                'cover' => array('fieldName'=>'cover', 'items'=>array()),
                'before' => array('fieldName'=>'img_before', 'items'=>array()),
                'after' => array('fieldName'=>'img_after', 'items'=>array()),
                'gallery' => array('fieldName'=>'gallery', 'items'=>array()),
            );

            foreach($list as $key=>$val){
                $bool = ($key=='gallery' || $key=='tabs') ? false : true;
                $list[$key]['items'] = json_decode($model->$val['fieldName'], $bool);
            }

            $labels = array();
            $rows = \WintnessLabels::where('wid', '=', $model->id)
                                   ->lists('label_id');
            if (!empty($rows)) $labels = $rows;

            $labelList = array('service'=>array(), 'faq'=>array());
            $rows = \ServiceFaq::where('status', '=', 'Y')
                               ->orderBy('_parent', 'desc')
                               ->orderBy('sort', 'desc')
                               ->orderBy('updated_at', 'desc')
                               ->findMany($labels, array('id', 'title', 'type'));

            if (!empty($rows)){
                foreach($rows as $row)
                    $labelList[$row->type][] = array('id'=>$row->id, 'title'=>$row->title);
            }

            // find prev and next
            $prev = \Wintness::where('status', '=', '1')
                             ->where('sort', '>=', $model->sort)
                             ->where('updated_at', '>', $model->updated_at)
                             ->first(array('id', 'title'));

            $next = \Wintness::where('status', '=', '1')
                             ->where('sort', '<=', $model->sort)
                             ->where('updated_at', '<', $model->updated_at)
                             ->first(array('id', 'title'));

            // read service
            $servicefaq = \ServiceFaq::where('status', '=', 'Y')
                            ->orderBy('type', 'asc')
                            ->orderBy('_parent', 'desc')
                            ->orderBy('sort', 'desc')
                            ->orderBy('updated_at', 'desc')
                            ->get(array('id', 'title', '_parent', 'type'));

            $servicesFaqs = array('service'=>array(), 'faq'=>array());
            foreach($servicefaq as $item){
                $key = $item->id;
                $parent = $item->_parent;
                $items = $servicesFaqs[$item->type];
                if ($parent=='N'){
                    if (!isset($items[$key]))
                        $items[$key] = array('id'=>$key, 'title'=>$item->title, 'subItems'=>array());
                }else{
                    $items[$parent]['subItems'][] = array('id'=>$key, 'title'=>$item->title);
                }
                $servicesFaqs[$item->type] = $items;
            }

            $tabs = \Tabs::where('type', '=', 'wintness')
                         ->where('item_id', '=', $model->id)
                         ->orderBy('sort', 'desc')
                         ->get(array('title', 'content'));

            return \View::make('aesthetics.wintness.view_article', array(
                'bodyId' => 'casePost',
                'labelList' => &$labelList,
                'list' => &$list,
                'model' => &$model,
                'prev' => $prev,
                'next' => $next,
                'servicesFaqs' => &$servicesFaqs,
                'tabs' => &$tabs
            ));

        }catch(Exception $e){
            return \Redirect::route('frontend.wintness.index');
        }
    }

    /*
     * handle ajax request of get article list
     * @params (int) $page
     */
    public function getAjaxArticles(){
        try{
            $page = \Arr::get($_GET, 'page', null);
            $itemId = (int) \Arr::get($_GET, 'item', null);
            $keyword = \Arr::get($_GET, 'keyword', null);

            if (empty($page))
                throw new \Exception("Error request [10]");

            $page = (int) $page;
            $limit = 10;
            $offset = ($page-1)*$limit;
            $cmd = \DB::table('wintness')->select('wintness.id', 'wintness.title', 'wintness.cover', 'wintness.background_color', 'wintness.description')
                                         ->where('wintness.status', '=', '1')
                                         ->orderBy('wintness.sort', 'desc')
                                         ->orderBy('wintness.updated_at', 'desc')
                                         ->skip($offset)
                                         ->take($limit);

            if ($itemId>=1){
                $cmd = $cmd->join('wintness_labels', 'wintness.id', '=', 'wintness_labels.wid')
                           ->where('wintness_labels.label_id', '=', $itemId);
            }

            if (!empty($keyword)){
                $format = '(`tabs`.`title` like "%s" or `tabs`.`content` like "%s" or `wintness`.`title` like "%s" or `wintness`.`description` like "%s")';
                $keyword = '%' . $keyword . '%';
                $cmd = $cmd->join('tabs', 'wintness.id', '=', 'tabs.item_id')
                           ->where('tabs.type', '=', 'wintness')
                           ->whereRaw(sprintf($format, $keyword, $keyword, $keyword, $keyword))
                           ->groupBy('wintness.id');
            }

            $rows = $cmd->get();

            $data = array();
            if (!empty($rows)){
                foreach($rows as $row){
                    $cover = json_decode($row->cover, true);
                    $row->cover = (!empty($cover)) ? $cover[0]['image'] : 'null';

                    $labels_service = \WintnessLabels::where('wid', '=', $row->id)
                                                  ->lists('label_id');

                    $services = null;
                    if (!empty($labels_service)){
                        $services = \ServiceFaq::where('status', '=', 'Y')
                                           ->where('type', '=', 'service')
                                           ->whereIn('id', $labels_service)
                                           ->get(array('id', 'title'));
                    }

                    $labelList = array();
                    if (!empty($services)){
                        foreach($services as $idx=>$label){
                            $label->link = \URL::route('frontend.service_faq.article', array('type'=>'service', 'id'=>$label->id));
                            $labelList[] = $label->toArray();
                        }
                    }

                    $row->label_faq = $labelList;
                    $row->description = \Text::preEllipsize($row->description, 46, .6);
                    $data[] = (array) $row;
                }
            }

            return \Response::json(array('status'=>'ok', 'data'=>$data, 'item'=>$itemId));
        }catch(Excpetion $e){
            return \Response::json(array('status'=>'error', 'message'=>$e->getMessage()));
        }
    }
}
?>
