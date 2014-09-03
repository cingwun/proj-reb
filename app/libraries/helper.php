<?php
class Helper
{

    /*
     * 取得文章分類
     *
    */
    public static function article_category($id = '') {
        $article_category = array('1' => '關於煥儷', '2' => '海外專區', '3' => '最新消息');

        return ($id) ? $article_category[$id] : $article_category;
    }

    /**
     * 文章瀏覽數
     *
     */
    public static function views_cookie($type, $id) {

        $key = $type . '_' . $id;
        if (Cookie::get($key)) {
            return false;
        }

        Cookie::queue($key, 1, 5);

        return true;
    }

    /**
     *
     * 顯示或隱藏nav bar
     *
     */
    public static function nav_show($name) {
        return Sentry::getUser()->hasAnyAccess(array('system', 'admin', $name)) ? true : false;
    }
}
