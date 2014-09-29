<?php
class ViewsAdder
{

    /**
     * Judge whether the user has seem this article or not.
     * @params (string) article's type, (int) article's id
     * 文章瀏覽數
     *
     */
    public static function views_cookie($type, $id) {
        $key = $type.'_'.$id;

        if(Cookie::get($key))
            return false;

        //This cookie exists 120 minutes.
        Cookie::queue($key, 1, 120);
        return true;
    }

}
