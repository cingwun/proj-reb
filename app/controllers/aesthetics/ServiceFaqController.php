<?php
namespace aesthetics;

/*
 * This controller is used to handle request of Services
 */
class ServiceFaqController extends \BaseController {

    /*
     * display specific service
     * @params (int) $id
     */
    public function getArticle($type, $id){

        $model = \ServiceFaq::find($id);
        if ($model===null)
            return \Redirect::back();
        else
            $parent = \ServiceFaq::find($model->_parent);

        // Views
        if(\helper::views_cookie('service_faq',$id)){
            $model->views = $model->views + 1;
            $model->save();
        }

        $model->date = substr($model->created_at, 0, 10);

        // decode label and tab
        $lblIds = json_decode($model->labels, true);
        $labels = array();
        if (!empty($lblIds))
            $labels = \ServiceFaq::orderBy('id', 'asc')->find($lblIds, array('id', 'title'));

        $tabs=json_decode($model->tabs);
        if (empty($tabs))
            $tabs = array();

        $view = sprintf('aesthetics.%s.view_article', $type);

        return \View::make('aesthetics.serviceFaq.view_article', array(
            'model' => &$model,
            'parent' => &$parent,
            'images' => \ServiceFaqImage::where('sid', '=', $model->id)->orderBy('sort', 'asc')->get(),
            'labels' => &$labels,
            'tabs' => &$tabs,
            'navs' => $this->getNavigation($type),
            'type' => $type
        ));
    }

    /*
     * get navigation
     * @params (string) $type, default: service, values: service, faq
     * @return (array) $navs
     */
    private function getNavigation($type){
        $rows = \ServiceFaq::where('type', '=', $type)
                          ->where('status', '=', 'Y')
                          ->orderBy('_parent', 'desc')
                          ->orderBy('sort', 'desc')
                          ->orderBy('updated_at', 'desc')
                          ->get();
        $navs = array();
        if (sizeof($rows)==0)
            return $navs;

        foreach($rows as $r){
            $p = $r->_parent;
            $id = $r->id;
            if ($p!=='N'){
                if (isset($navs[$p])){
                    $navs[$p]['childs'][] = array('id'=>$id, 'title'=>$r->title);
                }
            }else{
                if (!isset($navs[$id]))
                    $navs[$id] = array('id'=>$id, 'title'=>$r->title, 'childs'=>array());
            }
        }
        return $navs;
    }

}
?>