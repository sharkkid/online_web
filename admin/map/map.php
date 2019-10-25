<?php
include_once("./func.php");
$area=GetParam('area');
$onadd_part_no=GetParam('onadd_part_no');

if(empty($area) && empty($onadd_part_no)) {
    $onadd_sn=GetParam('onadd_sn');
    $manage = getmanageLogBySn($onadd_sn);
    $area = $LOCATION_DB_AREA_MAPPING[$manage['dema_site_loc'].$manage['dema_floor_loc']];
    $onadd_part_no = $manage['onadd_part_no'];
}

$op=GetParam('op');
if(!empty($op)) {
    $ret_code = 1;
    $ret_msg = '';
    $ret_data = array();
    switch ($op) {
        case 'get':
            $onadd_sn = GetParam('onadd_sn');
            $ret_data = array();
            if(!empty($dema_sn)){
                $ret_code = 1;
                $ret_data = getmanageLogBySn($onadd_sn);
            } else {
                $ret_code = 0;
            }
            break;
            
        case 'setting':
            $area = GetParam('area');
            $position = GetParam('position');
            file_put_contents('./../../uploads/map/setting/'.$area, $position);
            if(!empty($_FILES['file']['name'])) {
                $upload_file_ret = uploadFile('./../../uploads/map/img/', $area, 'file');
                if($upload_file_ret['result']) {
                    $ret_msg = "設定成功.";
                } else {
                    $ret_code = -1;
                    $ret_msg = $upload_file_ret['msg'];
                    unlink('./../../uploads/map/img/'.$area);
                }
            } else {
                $ret_msg = "設定成功！";
            }
            
            $_SESSION['sys_map_setting_upload_data_result'] = $ret_msg;
            header('Location: map.php?area='.$area);
            die();
            break;
            
        case 'reset':
            $area = GetParam('area');
            file_put_contents('./../../uploads/map/setting/'.$area, '');
            echo 'reset success.';
            exit;
            
        default:
            $ret_msg = 'error!';
            break;
    }
    
    echo enclode_ret_data($ret_code, $ret_msg, $ret_data);
    exit;
} else {
    define('WEB_PAGE_TITLE', "植物栽培區");
    define('PAGE_FILE_NAME', "map.php");
   
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
    	<meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html">

	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo CN_NAME;?></title>
	<link href="./../../images/favicon.png" rel="icon">
	<link href="./../../css1/bootstrap.min.css" rel="stylesheet">
	<link href="./../../css1/simple-line-icons.css" rel="stylesheet">
	<link href="./../../css1/font-awesome.min.css" rel="stylesheet">
	<link href="./../../css1/pace.css" rel="stylesheet">
	<link href="./../../css1/jasny-bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="./../../css1/nanoscroller.css">
	<link rel="stylesheet" href="./../../css1/metismenu.min.css">
	<link href="./../../css1/c3.min.css" rel="stylesheet">
	<link href="./../../css1/blue.css" rel="stylesheet">
	<!-- dataTables -->
	<link href="./../../css1/jquery.datatables.min.css" rel="stylesheet" type="text/css">
	<link href="./../../css1/responsive.bootstrap.min.css" rel="stylesheet" type="text/css">
	<!-- <link href="./../css1/jquery.toast.min.css" rel="stylesheet"> -->
	<!--template css-->
	<link href="./../../css1/style.css" rel="stylesheet">
    	<?php include('./../htmlModule2/head.php');?>
		<script src="./../../lib/jquery.twbsPagination.min.js"></script>
		
		<script src="./../../lib/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js"></script>
		<link rel="stylesheet" href="./../../lib/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css">
		
		<style>
			.container {
			  position: relative;
			  width: 100%;
			  max-width: 1500px;
			}
			
			.container img {
			  width: 100%;
			  height: auto;
			}
			
			.container .btn1 {
			  position: absolute;
			  top: 50.3%;
			  left: 45%;
			  transform: translate(-50%, -50%);
			  -ms-transform: translate(-50%, -50%);
			  background-color: #555;
			  color: white;
			  font-size: 16px;
			  padding: 5px 5px;
			  border: none;
			  cursor: pointer;
			  border-radius: 5px;
			  text-align: center;
			}
			.container .btn2 {
			  position: absolute;
			  top: 54.5%;
			  left: 45%;
			  transform: translate(-50%, -50%);
			  -ms-transform: translate(-50%, -50%);
			  background-color: #555;
			  color: white;
			  font-size: 16px;
			  padding: 5px 5px;
			  border: none;
			  cursor: pointer;
			  border-radius: 5px;
			  text-align: center;
			}
			.container .btn3 {
			  position: absolute;
			  top: 58.7%;
			  left: 45%;
			  transform: translate(-50%, -50%);
			  -ms-transform: translate(-50%, -50%);
			  background-color: #555;
			  color: white;
			  font-size: 16px;
			  padding: 5px 5px;
			  border: none;
			  cursor: pointer;
			  border-radius: 5px;
			  text-align: center;
			}
			.container .btn4 {
			  position: absolute;
			  top: 62.7%;
			  left: 45%;
			  transform: translate(-50%, -50%);
			  -ms-transform: translate(-50%, -50%);
			  background-color: #555;
			  color: white;
			  font-size: 16px;
			  padding: 5px 5px;
			  border: none;
			  cursor: pointer;
			  border-radius: 5px;
			  text-align: center;
			}
			.container .btn5 {
			  position: absolute;
			  top: 67.2%;
			  left: 45%;
			  transform: translate(-50%, -50%);
			  -ms-transform: translate(-50%, -50%);
			  background-color: #555;
			  color: white;
			  font-size: 16px;
			  padding: 5px 5px;
			  border: none;
			  cursor: pointer;
			  border-radius: 5px;
			  text-align: center;
			}
			.container .btn6 {
			  position: absolute;
			  top: 50.3%;
			  left: 85%;
			  transform: translate(-50%, -50%);
			  -ms-transform: translate(-50%, -50%);
			  background-color: #555;
			  color: white;
			  font-size: 16px;
			  padding: 5px 5px;
			  border: none;
			  cursor: pointer;
			  border-radius: 5px;
			  text-align: center;
			}
			.container .btn7 {
			  position: absolute;
			  top: 54.5%;
			  left: 85%;
			  transform: translate(-50%, -50%);
			  -ms-transform: translate(-50%, -50%);
			  background-color: #555;
			  color: white;
			  font-size: 16px;
			  padding: 5px 5px;
			  border: none;
			  cursor: pointer;
			  border-radius: 5px;
			  text-align: center;
			}
			.container .btn8 {
			  position: absolute;
			  top: 58.7%;
			  left: 85%;
			  transform: translate(-50%, -50%);
			  -ms-transform: translate(-50%, -50%);
			  background-color: #555;
			  color: white;
			  font-size: 16px;
			  padding: 5px 5px;
			  border: none;
			  cursor: pointer;
			  border-radius: 5px;
			  text-align: center;
			}
			.container .btn9 {
			  position: absolute;
			  top: 62.7%;
			  left: 85%;
			  transform: translate(-50%, -50%);
			  -ms-transform: translate(-50%, -50%);
			  background-color: #555;
			  color: white;
			  font-size: 16px;
			  padding: 5px 5px;
			  border: none;
			  cursor: pointer;
			  border-radius: 5px;
			  text-align: center;
			}
			.container .btn10 {
			  position: absolute;
			  top: 67.2%;
			  left: 85%;
			  transform: translate(-50%, -50%);
			  -ms-transform: translate(-50%, -50%);
			  background-color: #555;
			  color: white;
			  font-size: 16px;
			  padding: 5px 5px;
			  border: none;
			  cursor: pointer;
			  border-radius: 5px;
			  text-align: center;
			}
		</style>
		
		<script type="text/javascript">
			
		</script>
    </head>
    
    <body>
    	<!--top bar start-->
	<?php include('./../htmlModule/nav.php');?>
        <!--main content start-->
        <section class="main-content">
<!--         	<div class="page-header">
        		<div class="row">
        			<div class="col-sm-6">
        				<h4>區域管理</h4>
        			</div>
        		</div>
        	</div> -->
		<!-- container -->
    	<div id="device_modal" class="modal fade">
			<div class="modal-dialog modal-lg">
			    <div class="modal-content">
			            <div class="modal-body">
			                <p>Loading...</p>
			            </div>
			            <div class="modal-footer">
			                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			            </div>
			    </div>
			</div>
		</div>

    	<?php include('./../htmlModule/nav.php');?>
    	
    	<!-- modal -->
		<div id="setting-modal" class="modal setting-modal" tabindex="-1" role="dialog">
		    <div class="modal-dialog modal-lg">
		        <div class="modal-content">
					<form autocomplete="off" method="post" action="./map.php" id="setting_form" class="form-horizontal" role="form" enctype="multipart/form-data">
			            <div class="modal-header">
			                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
			                <h4 class="modal-title">設定地圖</h4>
			            </div>
			            <div class="modal-body">
			            	<div class="row">
				        		<input type="hidden" name="op" value="setting">
				        		<input type="hidden" name="area" value="<?php echo $area;?>">
								<div class="form-group">
									<label for="addModalInput1" class="col-sm-2 control-label">位置</label>
									<div class="col-sm-9">
										<textarea class="form-control" rows="15" name="position"><?php echo $position_data;?></textarea>
										<div class="help-block" style="color: red" id="device_repeat_info"></div>
										<div class="help-block"><ul>
										</ul></div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">上傳地圖圖檔</label>
									<div class="col-sm-10">
										<div class="form-control-static">
											<input type="file" name="file">
											<div class="help-block">未更新不用上傳</div>
										</div>
									</div>
								</div>
							</div>
			            </div>
						<div class="modal-footer">
			            	<button type="button" class="btn btn-default edit_mode">進入抓取模式</button>
			            	<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
			            	<button type="submit" class="btn btn-primary">儲存</button>
						</div>
					</form>
		        </div>
		    </div>
		</div>

		<!-- container -->
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<h3 class="text-center wt-block-title"><?php echo WEB_PAGE_TITLE;?></h3>
					<div class="container">
					  <img id="map_img" style="width: 100%"src="./../../uploads/map/img/<?php echo $area;?>.jpg?x=<?php echo $x?>">
					  <button class="btn1" onclick="javascript:location.href='<?php echo WT_SERVER."/admin/purchase/plant_purchase.php?onadd_part_no=&onadd_part_name=&onadd_location=A1";?>'">前往查詢</button>
					  <button class="btn2" onclick="javascript:location.href='<?php echo WT_SERVER."/admin/purchase/plant_purchase.php?onadd_part_no=&onadd_part_name=&onadd_location=A2";?>'">前往查詢</button>
					  <button class="btn3" onclick="javascript:location.href='<?php echo WT_SERVER."/admin/purchase/plant_purchase.php?onadd_part_no=&onadd_part_name=&onadd_location=A3";?>'">前往查詢</button>
					  <button class="btn4" onclick="javascript:location.href='<?php echo WT_SERVER."/admin/purchase/plant_purchase.php?onadd_part_no=&onadd_part_name=&onadd_location=A4";?>'">前往查詢</button>
					  <button class="btn5" onclick="javascript:location.href='<?php echo WT_SERVER."/admin/purchase/plant_purchase.php?onadd_part_no=&onadd_part_name=&onadd_location=A5";?>'">前往查詢</button>
					  <button class="btn6" onclick="javascript:location.href='<?php echo WT_SERVER."/admin/purchase/plant_purchase.php?onadd_part_no=&onadd_part_name=&onadd_location=B1";?>'">前往查詢</button>
					  <button class="btn7" onclick="javascript:location.href='<?php echo WT_SERVER."/admin/purchase/plant_purchase.php?onadd_part_no=&onadd_part_name=&onadd_location=B2";?>'">前往查詢</button>
					  <button class="btn8" onclick="javascript:location.href='<?php echo WT_SERVER."/admin/purchase/plant_purchase.php?onadd_part_no=&onadd_part_name=&onadd_location=B3";?>'">前往查詢</button>
					  <button class="btn9" onclick="javascript:location.href='<?php echo WT_SERVER."/admin/purchase/plant_purchase.php?onadd_part_no=&onadd_part_name=&onadd_location=B4";?>'">前往查詢</button>
					  <button class="btn10" onclick="javascript:location.href='<?php echo WT_SERVER."/admin/purchase/plant_purchase.php?onadd_part_no=&onadd_part_name=&onadd_location=B5";?>'">前往查詢</button>
					</div>
					
				</div>
			</div>
		</div>

		<!--Start footer-->
        	<footer class="footer">
        		<span>Copyright &copy; 2019. Online Plant</span>
        	</footer>
        	<!--end footer-->

        </section>
        <!--end main content-->

        <!--Common plugins-->
        <!-- <script src="./../../js1/jquery.min.js"></script> -->
        <!-- <script src="./../../js1/bootstrap.min.js"></script> -->
        <script src="./../../js1/pace.min.js"></script>
        <script src="./../../js1/jasny-bootstrap.min.js"></script>
        <script src="./../../js1/jquery.slimscroll.min.js"></script>
        <script src="./../../js1/jquery.nanoscroller.min.js"></script>
        <script src="./../../js1/metismenu.min.js"></script>
        <script src="./../../js1/float-custom.js"></script>
         <!--page script-->
        <script src="./../../js1/d3.min.js"></script>
        <script src="./../../js1/c3.min.js"></script>
        <!-- iCheck for radio and checkboxes -->
        <script src="./../../js1/icheck.min.js"></script>
        <!-- Datatables-->
        <script src="./../../js1/jquery.datatables.min.js"></script>
        <script src="./../../js1/datatables.responsive.min.js"></script>
        <script src="./../../js1/jquery.toast.min.js"></script>
        <script src="./../../js1/dashboard-alpha.js"></script>
		
    </body>
</html>