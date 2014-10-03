<?php
    function createURL($url, $qs, $route, &$params, $page, $category){
        if (empty($route)){
            return $url .= '?page=' . $page . '&category=' . $category;
        }

        $params['page'] = $page;
        return URL::route($route, $params);
    }

    /*
	 * this widget is used to create pager
	 *
	 * @params (int) $currPage, default: 1
     * @params (int) $total, default: 0
	 * @params (int) $perPage, default: 10
	 * @params (string) $url, default: '/'
     * @params (string) $qs, default: ''
     * @params (string) $route, default: null
     * @params (array) $params, default: array()
     * @params (int) $size, default: 5
	 * @return (string) $pageList
	 */
    $currPage = Arr::get($wp, 'currPage', 1);
    $total = Arr::get($wp, 'total', 0);
    $perPage = Arr::get($wp, 'perPage', 10);
    $url = Arr::get($wp, 'URL', '/');
    $route = Arr::get($wp, 'route', null);
    $params = Arr::get($wp, 'params', array());
    $qs = Arr::get($wp, 'qs', '');
    $category = Arr::get($wp, 'category', 1);
    $size = 5;

	$totalPage = ceil($total/$perPage);
    $mod = $currPage % $size;
    $end = ($currPage+$size) - $mod;

    if ($mod==0)
        $end = $end - $size + 1;

    if ($mod==1)
        $end = $end - 1;

    $st = $end - $size + 1;

    if ($end>$totalPage)
        $end = $totalPage;

    if ($st<=0){
        $st = 1;
        $end = ($size<=$totalPage) ? $size : $totalPage;
    }

    $qs .= (!empty($qs)) ? '&' : '';
    // $url .= '?' . 'page=';

    $pageList = '<ul class="pagination">';

   if (($currPage-1)>0)
        $pageList .= sprintf('<li><a href="%s" title="上一頁">«</a></li>', createURL($url, $qs, $route, $params, ($currPage-1), $category));

    for($i=$st; $i<=$end; $i++){
        $num = str_pad($i, 2, "0", STR_PAD_LEFT);
        if ($i!=$currPage)
            $pageList .= sprintf('<li><a href="%s">%s</a></li>', createURL($url, $qs, $route, $params, $i, $category), $num);
        else
            $pageList .= sprintf('<li class="active"><a href="%s">%s</a></li>', createURL($url, $qs, $route, $params, $i, $category), $num);
    }

    if (($currPage+1)<=$totalPage)
        $pageList .= sprintf('<li><a href="%s" title="下一頁">»</a></li>', createURL($url, $qs, $route, $params, ($currPage+1), $category));

	echo $pageList .= '</ul>';
?>