<?php

/**
* This class helps you deal with string or text manipulation.
*/
class Text {
    /**
     * Ellipsize String
     *
     * This function will strip tags from a string, split it at its max_length and ellipsize
     *
     * !! This function is copied from CodeIgniter text_helper.
     * I already modified it to be compatible with UTF-8 string.
     * @see http://www.codeigniter.org.tw/user_guide/helpers/text_helper.html
     * @param   string      string to ellipsize
     * @param   integer     max length of string
     * @param   mixed       int (1|0) or float, .5, .2, etc for position to split
     * @param   string      encording
     * @param   string      ellipsis ; Default '...'
     * @return  string      ellipsized string
     */
    public static function ellipsize($str, $max_length, $position = 1, $encode='UTF-8', $ellipsis = '…')
    {
        // Is the string long enough to ellipsize?
        if (mb_strlen($str, $encode) <= $max_length)
        {
            return $str;
        }

        $beg = mb_substr($str, 0, floor($max_length * $position), $encode);

        $position = ($position > 1) ? 1 : $position;

        if ($position === 1)
        {
            $end = mb_substr($str, 0, -($max_length - mb_strlen($beg, $encode)), $encode);
        }
        else
        {
            $end = mb_substr($str, -($max_length - mb_strlen($beg, $encode)), -1, $encode);
        }

        return $beg.$ellipsis.$end;
    }

    /**
     * Ellipsize String for prefix
     *
     * This function will strip tags from a string, split it at its max_length and ellipsize
     *
     * !! This function is copied from CodeIgniter text_helper.
     * I already modified it to be compatible with UTF-8 string.
     * @see http://www.codeigniter.org.tw/user_guide/helpers/text_helper.html
     * @param   string      string to ellipsize
     * @param   integer     max length of string
     * @param   string      check whether the string is for spa_share or not
     * @param   string      encording
     * @param   string      ellipsis ; Default '...'
     * @return  string      ellipsized string
     */
    public static function preEllipsize($str, $max_length, $type=null, $rate=0.4, $ellipsis = '...', $encode='UTF-8'){
        // Is the string long enough to ellipsize?
        if (mb_strlen($str, $encode) <= $max_length)
            return $str;
        $bool = true;
        $i = 0;
        while($bool){
            $preStr = mb_substr($str, 0, $max_length+$i, $encode);
            if (($engLen=preg_match_all('/[\w\s]/', $preStr, $matches))==0)
                $bool = false;
            else{
                $len = ceil($engLen*$rate) + ($max_length-$engLen) + $i;
                $bool = ($len<($max_length));
                $i++;
            }
        }

        return ($type=='spa_share') ? $preStr.$ellipsis.'more' : $preStr.$ellipsis;
    }

    /**
     * Add a space between chinese chars and number/english
     * ex.
     *  '6房6廳12323232366衛 廚房 陽台 （廚房 陽台）' => '6 房 6 廳 12323232366 衛 廚房 陽台 （廚房 陽台）'
     *  '6房6廳abc衛 廚房 陽台' => '6 房 6 廳 abc 衛 廚房 陽台'
     *
     * @param string $s
     * @return string
     */
    public static function addWordSpacing($s) {
        if (!is_string($s)) { return $s; }

        // chinese utf8 char table: http://www.ansell-uebersetzungen.com/gbuni.html
        $utf8char = '\x{4E00}-\x{9FA0}'; // the chinese utf8 chars
        $s = preg_replace("/([^$utf8char]+)(?=[$utf8char])/u", '$1 ', $s);
        $s = preg_replace("/([$utf8char])(?=[^$utf8char]+)/u", '$1 ', $s);
        return $s;
    }

}
