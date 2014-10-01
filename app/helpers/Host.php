<?php
/**
 * this helper is used to get host
 */
class Host {
    /**
     * get
     */
    public static function get($target='rebeauty'){
        $port = (int) $_SERVER['SERVER_PORT'];
        $domain = str_replace(':'.$port, '', $_SERVER['HTTP_HOST']);
        $host = str_replace(array('www.', 'spa.'), '', $domain);
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
                return 'spa.' . $host;
                break;
            default:
                return (preg_match('/rebeauty\.com\.tw/i', $host)==false) ? $host : 'www.'.$host;
                break;
        }
    }
}
?>