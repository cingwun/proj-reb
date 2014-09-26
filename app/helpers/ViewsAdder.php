<?php
class ViewsAdder
{

    /**
     * 文章瀏覽數
     *
     */
    public static function views_cookie($type, $id) {

        $key = $type . '_' . $id;

        if(Cookie::get($key)) {
            return false;
        }

        Cookie::queue($key, 1, 120);
        return true;
    }
    
}
