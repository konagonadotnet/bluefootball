$(function(){
    var $grid = $('.test1');

    // 総ページ数取得
    var $total_page_num = $('#total_number_of_pages').text();
    // 空白スペースを削除
    $total_page_num = $.trim($total_page_num);

    // 次ページベースURL取得
    var $base_url = $('.navigation li a').attr('href');

    // 次ページurlからページ番号を抽出
    var $base_url_num = $base_url.replace(/[^0-9]/g, ""); //  一致した全ての数字を""で置換

    // 各ページURL格納用配列
    var $pages_url = new Array();
    // 次ページURL設定
    $pages_url[0] = $base_url;
    // ページ番号格納用変数(次ページ2は$pages_url[0]で格納済みのため3で初期化)
    var $page_num = 3;
    // 3ページ目から最後のページまでのURLを格納する
    for (var num = 1; num < $total_page_num - 1; num++) {
    	// ベースURLの数字を各ページ番号に置換
    	$pages_url[num] = $pages_url[0].replace(/\d/, $page_num); // 数字1桁を$page_numに置換
        // 各ページ番号を設定
        $page_num = $page_num + 1;
    }

    $grid.infiniteScroll({
    	path: getPath,
        append: '.test1',
        hideNav: '.navigation',
        status: '.page-load-status',
    });

    function getPath() {
    	var slug = $pages_url[ this.loadCount ];
        if( slug ) {
        	return slug;
        }
    }
});