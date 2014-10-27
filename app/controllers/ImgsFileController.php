<?php

class ImgsFileController extends BaseController{

	public function checkImgsFile() {
		$modelColumn = array(
			array('table'=>'banners', 'columns'=>'image', 'format'=>'url'),
			array('table'=>'beauty_news', 'columns'=>'cover', 'format'=>'json'),
			array('table'=>'beauty_news', 'columns'=>'fb', 'format'=>'json'),
			array('table'=>'service_faq', 'columns'=>'image', 'format'=>'url'),
			array('table'=>'service_faq', 'columns'=>'tabs', 'format'=>'html'),
			array('table'=>'service_faq_images', 'columns'=>'image', 'format'=>'url'),
			array('table'=>'articles', 'columns'=>'description', 'format'=>'html'),
	        array('table'=>'spa_articles', 'columns'=>'cover', 'format'=>'json'),//
	        array('table'=>'spa_articles', 'columns'=>'content', 'format'=>'html'),
	        array('table'=>'spa_product', 'columns'=>'image', 'format'=>'url'),
	        array('table'=>'spa_product', 'columns'=>'content', 'format'=>'html'),
	        array('table'=>'spa_product_images', 'columns'=>'image_path', 'format'=>'url'),
	        array('table'=>'spa_service', 'columns'=>'image', 'format'=>'url'),
	        array('table'=>'spa_service', 'columns'=>'content', 'format'=>'html'),
	        array('table'=>'spa_service_images', 'columns'=>'image_path', 'format'=>'url'),
	        array('table'=>'spa_shares', 'columns'=>'image', 'format'=>'json'),
	        array('table'=>'spa_shares', 'columns'=>'gallery', 'format'=>'json'),
	        array('table'=>'spa_shares_gallery', 'columns'=>'imageURL', 'format'=>'url'),
	        array('table'=>'spa_shares_tabs', 'columns'=>'content', 'format'=>'html'),
	        array('table'=>'tabs', 'columns'=>'content', 'format'=>'html'),
	        array('table'=>'technologies', 'columns'=>'image', 'format'=>'url'),
	        array('table'=>'wintness', 'columns'=>'cover', 'format'=>'json'),
	        array('table'=>'wintness', 'columns'=>'img_before', 'format'=>'json'),
	        array('table'=>'wintness', 'columns'=>'img_after', 'format'=>'json'),
	        array('table'=>'wintness', 'columns'=>'tabs', 'format'=>'html'),
	        array('table'=>'wintness', 'columns'=>'gallery', 'format'=>'json')
		);

		$imgList = array();
		foreach ($modelColumn as $row) {
			try {
				$imgsData = DB::select('select '.$row['columns'].' from '.$row['table']);
				$imgs = array();
				if(!empty($imgsData)) {
					foreach ($imgsData as $key => $data) {
						if(!empty($data->$row['columns'])) {
							switch ($row['format']) {
								case 'url':
									$imgs[] = Imagehandler::url($data->$row['columns']);
									break;
								case 'json':
									$jsonCmd = Imagehandler::json($data->$row['columns']);
									if(!empty($jsonCmd)){
										$imgs = array_merge($imgs, $jsonCmd);
									}
									break;
								case 'html':
									$htmlCmd = Imagehandler::html($data->$row['columns']);
									if(!empty($htmlCmd)){
										$imgs = array_merge($imgs, $htmlCmd);
									}
									break;
							}
						}
					}
				}
				$imgList = array_merge($imgList, $imgs);
			} catch (Exception $e) {
				echo "<span style='color:red'>".$e->getMessage().'</span><br/>';
			}
		}

    	//check the file img exist in the database
		$imgsfile = glob('../app/storage/files/*.*');
		foreach ($imgsfile as $key => $row) {
			if(in_array(basename($row),$imgList)) {
				unset($imgsfile[$key]);
			}
		}

		//remove file
		try {
			foreach ($imgsfile as $row) {
				//cache
				$imgName = basename($row);
				if (is_dir("../app/storage/files/cache/$imgName")) {

					$cacheFile = glob("../app/storage/files/cache/$imgName/*.*");
					if(!empty($cacheFile)) {
						foreach ($cacheFile as $cache) {
							unlink($cache);
						}
					}
					rmdir("../app/storage/files/cache/$imgName");
				}
				//img
				unlink($row);
			}
		} catch (Exception $e) {
			echo $e->getMessage();
		}
		echo "Clear completed";
	}
}
?>
