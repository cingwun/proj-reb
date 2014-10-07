<?php
/**
 * this helper is used to get host
 */
class Host {
    /**
     * get
     */
    public static function get($target='rebeauty', $includePort=true){
        $port = (int) $_SERVER['SERVER_PORT'];
        $domain = str_replace(':'.$port, '', $_SERVER['HTTP_HOST']);
        $domain = str_replace(array('www.', 'spa.'), '', $domain);
        $host = $domain;
        if ($port!=80)
            $host .= ':' . $port;

        switch($target){
            case 'domain':
                return $domain;
                break;
            case 'host':
                return $host;
                break;
            case 'spa':
                $postfix = ($includePort) ? $host : $domain;
                return 'spa.' . $postfix;
                break;
            default:
                $prefix = (preg_match('/rebeauty\.com\.tw/i', $host)==false) ? '' : 'www.';
                $postfix = ($includePort) ? $host : $domain;
                return $prefix . $postfix;
                break;
        }
    }

    /*
     * get selected language page
     * @params (string) $target 'tw'/'cn'
     */
    public static function getLangURL($target='tw'){
        //http://spa.rebeauty.com.tw:8080/tw/news/9
        //REQUEST_URI=>/tw/news/9
        //SERVER_NAME=>rebeauty.com.tw
        //HTTP_HOST=> spa.rebeauty.com.tw:8080 <=asset('')
        //HTTP_REFERER=>http://spa.rebeauty.com.tw:8080/tw/news
        //$_SERVER
        $after = str_replace(array('tw/', 'cn/'), '', $_SERVER['REQUEST_URI']);
        $theURL = asset('').$target.$after;
        return $theURL;
    }
}
?>