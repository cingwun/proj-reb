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
            $host = ':' . $port;

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
}
?>