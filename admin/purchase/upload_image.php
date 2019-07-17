<?php
include_once(dirname(__FILE__).'/../config.php');
/**
 * 表單接收頁面
 */

// 網頁編碼宣告（防止產生亂碼）
header('content-type:text/html;charset=utf-8');
// 封裝好的單一檔案上傳 function
include_once("./func_plant_purchase_details.php");
// 取得 HTTP 文件上傳變數
$fileInfo = $_FILES['myFile'];

switch ($_POST['onproduct_type']) {
	// 1 : 更新封面   2 : 新增更多圖片
	case '1':
		$path = $_POST['parameters'];
		$newName = uploadFile($fileInfo);
		update_image_url($newName,$_POST['onproduct_sn']);
		//重定向瀏覽器 
		header("Location: ".WT_SERVER."/admin/purchase/".$path); 
		break;
	
	case '2':
		$path = $_POST['parameters'];
		$newName = uploadFile($fileInfo);
		add_image_url($newName,$_POST['onproduct_sn']);
		//重定向瀏覽器 
		header("Location: ".WT_SERVER."/admin/purchase/".$path); 
		break;
}


//確保重定向後，後續代碼不會被執行 
exit;
?>
