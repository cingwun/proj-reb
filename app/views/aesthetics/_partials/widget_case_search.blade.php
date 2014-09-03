<div class="caseSearch">
    <a href="#" class="csBox btn-caseSearch"><img src="<?php echo asset('aesthetics/img/sign/caseSearch.png')?>" alt="案例搜尋"/></a>
    <div class="funTool">
        <div class="tabNav2">
            <a href="#" title="服務項目" class="tab-service">服務項目</a>
            <a href="#" title="常見問題" class="tab-faq">常見問題</a>
        </div>
        <div class="wrapper">

            <input type="text" name="keyword" placeholder="請輸入關鍵字..."><a href="#" class="btn search btn-search">搜尋</a>

            <ul class="itemList tabBox2" id="item-list"></ul>
        </div>
        <a href="#" class="close btn-close" title="關閉搜尋"><img src="<?php echo asset('aesthetics/img/sign/close.png')?>"></a>
    </div>
    <!-- wrapper end -->
</div>
<!-- caseSearch end -->

@section('bottomContent')

    {{ HTML::script('js/widgets/caseSearch/js_widget_case_search.js'); }}

    <script type="text/x-tmpl" id="tmpl-tabItem">
            <li style="vertical-align: top;">
                <dl><dt><a>{%=o.title%}</a></dt>
                {% for(var i=0; i<o.subItems.length; i++){ %}
                <dd><a href="/wintness#/item/{%=o.subItems[i].id%}/{%=o.subItems[i].title.replace('/', '#')%}" class="items">{%=o.subItems[i].title%}</a></dd>
                {% } %}
                </dl>
            </li>
    </script>
    @parent
@stop
