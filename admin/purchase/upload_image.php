<?php
/**
 * 表單接收頁面
 */

// 網頁編碼宣告（防止產生亂碼）
header('content-type:text/html;charset=utf-8');
// 封裝好的單一檔案上傳 function
include_once("./func_plant_purchase_details.php");
// 取得 HTTP 文件上傳變數
$fileInfo = $_FILES['myFile'];
// 呼叫封將好的 function
$newName = uploadFile($fileInfo);
update_image_url($newName,$_POST['onproduct_sn']);
//重定向瀏覽器 
header("Location: http://localhost/online_web/admin/purchase/plant_purchase_details.php"); 
//確保重定向後，後續代碼不會被執行 
exit;
?>
