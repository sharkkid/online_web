<?php
include_once("./func_plant_purchase.php");
$status_mapping = array(0=>'<font color="red">關閉</font>', 1=>'<font color="blue">啟用</font>');
$DEVICE_SYSTEM = array(
	1=>"1.7",
	2=>"2.5",
	3=>"2.8",
	4=>"3.0",
	5=>"3.5",
	6=>"3.6",
	7=>"其他",
	8=>"瓶苗下重"
		// 1.7, 2.5, 2.8, 3.0, 3.5, 3.6 其他
);
$permissions_mapping = array(
	0=>'<font color="#666666">瓶苗</font>',
	1=>'<font color="#666666">1.7</font>',
	2=>'<font color="#666666">2.5</font>',
	3=>'<font color="#666666">2.8</font>',
	4=>'<font color="#666666">3.0</font>',
	5=>'<font color="#666666">3.5</font>',
	6=>'<font color="#666666">3.6</font>',
	7=>'<font color="#666666">其他</font>',
	8=>'<font color="#666666">瓶苗下種</font>' 
);

$permmsion = $_SESSION['user']['jsuser_admin_permit'];

// printr(getQuantityForseller('W2005','天使angel  (TY262)'));
// exit;

$op=GetParam('op');
if(!empty($op)) {
	$ret_code = 1;
	$ret_msg = '';
	$ret_data = array();
	switch ($op) {

		case 'get':
		$onproduct_sn=GetParam('onproduct_sn');
		$ret_data = array();
		if(!empty($onproduct_sn)){
			$ret_code = 1;
			$ret_data = getProductData($onproduct_sn);
			// $ret_msg = "123";
		} else {
			$ret_code = 0;
		}
		// printr($ret_data);
		// exit;

		break;

		case 'IsOver5Pics':
		$onproduct_sn=GetParam('onproduct_sn');
		$ret_data = array();
		if(!empty($onproduct_sn)){
			$ret_code = 1;
			$ret_data = getPicQty($onproduct_sn);
			// $ret_msg = "123";
		} else {
			$ret_code = 0;
		}
		// printr($ret_data);
		// exit;

		break;

		//新增預計出貨---------------------------------------------
		case 'upd1':
		$onbuda_part_no = GetParam('onbuda_part_no');
		$onbuda_part_name = GetParam('onbuda_part_name');
		$onbuda_quantity = GetParam('onbuda_quantity');
		$onbuda_size = GetParam('onbuda_size');
		$onbuda_date = strtotime (GetParam('onbuda_date'));
		$onbuda_client = GetParam('onbuda_client');
		$onbuda_seller = GetParam('onbuda_seller');
		$onbuda_sell_price = GetParam('onbuda_sell_price');
		$onbuda_year = substr(GetParam('onbuda_date'),0,4);
		$onbuda_month = substr(GetParam('onbuda_date'),5,-3);
		$now = time();
		$conn = getDB();

		$sql = "INSERT INTO `onliine_business_data`(`onbuda_add_date`, `onbuda_mod_date`, `onbuda_status`, `onbuda_part_no`, `onbuda_part_name`, `onbuda_date`, `onbuda_quantity`, `onbuda_size`, `onbuda_client`, `onbuda_year`, `onbuda_day`, onbuda_seller, onbuda_sell_price) ".
			"VALUES('{$now}', '{$now}', '1','{$onbuda_part_no}','{$onbuda_part_name}','{$onbuda_date}','{$onbuda_quantity}','{$onbuda_size}','{$onbuda_client}','{$onbuda_year}','{$onbuda_month}','{$onbuda_seller}', '{$onbuda_sell_price}');";
		if($conn->query($sql)) {
			$ret_msg = "完成！";
		} else {
			$ret_msg = "失敗！";
		}

		break;

		//刪除---------------------------------------------
		case 'del':
		$onadd_sn=GetParam('onadd_sn');

		if(empty($onadd_sn)){
			$ret_msg = "刪除失敗！";
		}else{
			$now = time();
			$conn = getDB();
			$sql = "DELETE FROM onliine_add_data WHERE onadd_sn='{$onadd_sn}'";
			if($conn->query($sql)) {
				$ret_msg = "刪除完成！";
			} else {
				$ret_msg = "刪除失敗！";
			}
			$conn->close();
		}
		break;

		default:
		$ret_msg = 'error!';
		break;

		
		//取得預計出貨明細-----------------------------------
		case 'get_customer_list':
		$onbuda_part_no=GetParam('onadd_part_no');
		$year=GetParam('year');
		$month=GetParam('month');
		$size=GetParam('size');
		if(empty($onbuda_part_no)){
			$ret_code = 0;
		}else{
			$ret_msg = '';
			$ret_code = 1;
			$ret_data = getExpectedList($onbuda_part_no,$year,$month,$size);
		}
		break;

		//汰除---------------------------------------------
		case 'eli':
		$onadd_sn=GetParam('onadd_sn');
		$onadd_newpot_sn=GetParam('onadd_newpot_sn');
		if($onadd_newpot_sn == "0"){
			$list = getUserBySn($onadd_sn);
		}
		else{
			$list = getUserBySn($onadd_newpot_sn);
		}
		$onadd_part_no = $list['onadd_part_no'];
		$onadd_part_name = $list['onadd_part_name'];
		$onadd_quantity=GetParam('onadd_quantity');//下種數量
		$jsuser_sn = GetParam('supplier');//編輯人員
		$onadd_quantity_del=GetParam('onadd_quantity_del');//汰除數量
		$onelda_reason=GetParam('onelda_reason');//汰除原因
		$jsuser_sn = GetParam('supplier');//編輯人員
		$onadd_quantity_del123 = ($onadd_quantity - $onadd_quantity_del);
		if($onadd_quantity_del123 < 0) {
			$onadd_status = -1;
		} else {
			$onadd_status = 1;
		}

		if(empty($onadd_quantity_del)){
			$ret_msg = "*為必填！";
		} 
		else if($onadd_status != -1){
			$now = time();
			$conn = getDB();
			$sql1 = "UPDATE onliine_add_data SET onadd_quantity='{$onadd_quantity_del123}', onadd_status='{$onadd_status}' WHERE onadd_sn='{$onadd_sn}'";
			$sql = "INSERT INTO online_elimination_data (onelda_add_date, onelda_mod_date, onelda_quantity, onelda_reason, onadd_sn, onadd_part_no, onadd_part_name) " .
				"VALUES ('{$now}', '{$now}', '{$onadd_quantity_del}', '{$onelda_reason}', '{$onadd_newpot_sn}', '{$onadd_part_no}', '{$onadd_part_name}');";
			if($conn->query($sql1) && $conn->query($sql)) {
				$ret_msg = "汰除完成！";
				if($onadd_quantity_del123 == 0){
					$sql = "UPDATE onliine_add_data SET onadd_quantity='{$onadd_quantity_del123}', onadd_status='-1' WHERE onadd_sn='{$onadd_sn}'";
					$conn->query($sql);
				}
			} else {
				$ret_msg = "汰除失敗！";
			}
		}
		else if($onadd_status == -1){
			$ret_msg = "錯誤！ 汰除數量不可大於下種數量！";
		}

		break;
		//汰除---------------------------------------------

		default:
		$ret_msg = 'error!';
		break;
	}

	echo enclode_ret_data($ret_code, $ret_msg, $ret_data);
	exit;
} else {
	function getSQL($qry) {
		$conn = getDB();
		$result = $conn->query($qry);
		$conn->close();
		return $result;
	}

	$onadd_part_no = GetParam('onadd_part_no');
	$onadd_part_name = GetParam('onadd_part_name');
	$onadd_growing = GetParam('onadd_growing');
	$onadd_quantity_del = GetParam('onadd_quantity_del');
	$user_list = getExpectedShipByMonth($onadd_quantity_del,$onadd_part_no,$onadd_growing);
	$business_data = getBusinessData($onadd_part_no,$onadd_quantity_del);
	// printr($user_list);
	// exit;
	$data_list = getDataDetails($onadd_part_no,$onadd_part_name);
	$eli_list = getQuantityForseller($data_list[0]['onproduct_part_no'],$data_list[0]['onproduct_part_name']);
	// printr($eli_list);
	// exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html">

	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<title><?php echo CN_NAME;?></title>
	<!-- Common plugins -->
	<!-- <link href="./../img/apple-touch-icon.png" rel="apple-touch-icon"> -->
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
	<?php include('./../htmlModule/head.php');?>
	<script src="./../../lib/jquery.twbsPagination.min.js"></script>
	<script src="./../../lib/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js" charset="UTF-8"></script>
    <script src="./../../lib/bootstrap-datetimepicker/bootstrap-datetimepicker.zh-TW.js" charset="UTF-8"></script>
	<link rel="stylesheet" href="./../../lib/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css">
	<script type="text/javascript">
		//汰除-----------------------------------------------------------
			function do_emli(onadd_sn){
				$('#eli-modal1').modal();
				$('#eli_form1')[0].reset();

				$.ajax({
					url: './plant_purchase.php',
					type: 'post',
					dataType: 'json',
					data: {op:"get", onadd_sn:onadd_sn},
					beforeSend: function(msg) {
						$("#ajax_loading").show();
					},
					complete: function(XMLHttpRequest, textStatus) {
						$("#ajax_loading").hide();
					},
					success: function(ret) {
			                // console.log(ret);
			                if(ret.code==1) {
			                	var d = ret.data;
			                	$('#eli_form1 input[name=onadd_sn]').val(d.onadd_sn);
			                	$('#eli_form1 input[name=onadd_part_no]').val(d.onadd_part_no);
			                	$('#eli_form1 input[name=onadd_quantity]').val(d.onadd_quantity);
			                	if(d.onadd_newpot_sn == "0"){
				                	$('#eli_form1 input[name=onadd_newpot_sn]').val(d.onadd_sn);
				                }
				                else{
				                	$('#eli_form1 input[name=onadd_newpot_sn]').val(d.onadd_newpot_sn);
				                }
			                	
			                }
			            },
			            error: function (xhr, ajaxOptions, thrownError) {
		                	// console.log('ajax error');
		                    // console.log(xhr);
		                }
		            });
			};
			//汰除-----------------------------------------------------------
		$(document).ready(function() {
			$('#carousel-example-generic').carousel({
			    interval: false
			});

			<?php
					//	init search parm
			// print "$('#search [name=onadd_status] option[value={$onadd_status}]').prop('selected','selected');";
			// print "$('#search [name=onadd_growing] option[value={$onadd_growing}]').prop('selected','selected','selected','selected','selected','selected','selected');";
			?>

			

			bootbox.setDefaults({
				locale: "zh_TW",
			});

			$('button.del').on('click', function(){
				onadd_sn = $(this).data('onadd_sn')
				bootbox.confirm("確認刪除？", function(result) {
					if(result) {
						$.ajax({
							url: './plant_purchase.php',
							type: 'post',
							dataType: 'json',
							data: {op:"del", onadd_sn:onadd_sn},
							beforeSend: function(msg) {
								$("#ajax_loading").show();
							},
							complete: function(XMLHttpRequest, textStatus) {
								$("#ajax_loading").hide();
							},
							success: function(ret) {
								alert_msg(ret.msg);
							},
							error: function (xhr, ajaxOptions, thrownError) {
				                	// console.log('ajax error');
				                }
				            });
					}
				});
			});

			$('button.upd1').on('click', function(){
				onproduct_part_name = $(this).data('onproduct_part_name');
				onproduct_part_no = $(this).data('onproduct_part_no');
			    $('#upd_form1 input[name=onbuda_part_no]').val(onproduct_part_no);
			    $('#upd_form1 input[name=onbuda_part_name]').val(onproduct_part_name);
			    $('#upd-modal1').modal();
			});

			$('#upd_form1').validator().on('submit', function(e) {
				if (!e.isDefaultPrevented()) {
					e.preventDefault();
					var param = $(this).serializeArray();
					$(this).parents('.modal').modal('hide');
					$(this)[0].reset();

					 	// console.table(param);

					 	$.ajax({
					 		url: './details_table.php',
					 		type: 'post',
					 		dataType: 'json',
					 		data: param,
					 		beforeSend: function(msg) {
					 			$("#ajax_loading").show();
					 		},
					 		complete: function(XMLHttpRequest, textStatus) {
					 			$("#ajax_loading").hide();
					 		},
					 		success: function(ret) {
					 			alert_msg(ret.msg);
					 		},
					 		error: function (xhr, ajaxOptions, thrownError) {
			                	// console.log('ajax error');
			                     // console.log(xhr);
			                 }
			             });
					 }
			});

			$('#datetimepicker1').datetimepicker({
				minView: 2,
				language:  'zh-TW',
				format: 'yyyy-mm-dd',
				useCurrent: false
			});

			$('#datetimepicker2').datetimepicker({
				minView: 2,
				language:  'zh-TW',
				format: 'yyyy-mm-dd',
				useCurrent: false
			});
			$('button.cancel').on('click', function() {
				location.href = "./../";
			});
		});
		function customer_list(onadd_part_no,year,month,size){
			$('#month_customers_title').html(year+" 年 "+month+" 月 - "+onadd_part_no+" 客戶明細(預計出貨)");
			$('#modal_month_customers').modal();
			$.ajax({
				url: './details_table.php',
				type: 'post',
				dataType: 'json',
				data: {op:"get_customer_list", onadd_part_no:onadd_part_no, year:year, month:month, size:size},
				beforeSend: function(msg) {
					$("#ajax_loading").show();
				},
				complete: function(XMLHttpRequest, textStatus) {
					$("#ajax_loading").hide();
				},
				success: function(ret) {
					$('#month_customers_cotent').html('<div class="col-md-12"><div class="col-sm-10"><label for="addModalInput1" class="col-sm-2 control-label">客戶名稱</label><label for="addModalInput1" class="col-sm-2 control-label">預計出貨數量</label><label for="addModalInput1" class="col-sm-2 control-label">預計出貨日期</label><label for="addModalInput1" class="col-sm-2 control-label">預計出貨尺寸</label><label for="addModalInput1" class="col-sm-2 control-label">該筆新增日期</label><label for="addModalInput1" class="col-sm-2 control-label">售出價格</label></div></div>');
					$.each(ret.data, function(key,value){	
						if(key < ret.data.length){										
							$('#month_customers_cotent').html($('#month_customers_cotent').html()+'<div class="col-md-12"><div class="col-sm-10"><label for="addModalInput1" class="col-sm-2 control-label">'+value.onbuda_client+'</label><label for="addModalInput1" class="col-sm-2 control-label">'+value.onbuda_quantity+'</label><label for="addModalInput1" class="col-sm-2 control-label">'+value.onbuda_date+'</label></label><label for="addModalInput1" class="col-sm-2 control-label">'+value.onbuda_size+'吋</label><label for="addModalInput1" class="col-sm-2 control-label">'+value.onbuda_add_date+'</label><label for="addModalInput1" class="col-sm-2 control-label">$'+value.onbuda_sell_price+'</label></div></div>');								
						}

					});
					
				},
				error: function (xhr, ajaxOptions, thrownError) {
			   	console.log('ajax error');
			        // console.log(xhr);
			    }
			});
			// $('#month_customers_cotent').html($('#month_customers_cotent').html()+'<div class="col-md-12"><div class="col-sm-10"><label for="addModalInput1" class="col-sm-2 control-label">客戶名稱</label><label for="addModalInput1" class="col-sm-2 control-label">預計出貨數量</label><label for="addModalInput1" class="col-sm-2 control-label">預計出貨日期</label><label for="addModalInput1" class="col-sm-2 control-label">該筆新增日期</label></div></div>');
		}
	function upd_btn_click(onproduct_sn) {
		$.ajax({
				url: './details_table.php',
				type: 'post',
				dataType: 'json',
				data: {op:"IsOver5Pics", onproduct_sn:onproduct_sn},
				beforeSend: function(msg) {
					$("#ajax_loading").show();
				},
				complete: function(XMLHttpRequest, textStatus) {
					$("#ajax_loading").hide();
				},
				success: function(ret) {
					if(ret.data >= 5){
						alert("圖片至多只能上傳5張！");
					}
					else{
						$('#Upload_Image_Modal').modal('show');
	  					$('#onproduct_sn').val(onproduct_sn);
					}
				},
				error: function (xhr, ajaxOptions, thrownError) {
			   	console.log('ajax error');
			        // console.log(xhr);
			    }
			});
	}
	</script>
</head>

<body>
	<?php include('./../htmlModule/nav.php');?>
	<!--main content start-->
	<section class="main-content">
		<!--page header start-->
		<div style="visibility: hidden;" id="hidden_onproduct_sn"><?php echo $data_list[0]['onproduct_sn']; ?></div>
		<div class="page-header">
			<div class="row">
				<div class="col-sm-6">
					<h4>可供量表</h4>
				</div>
			</div>
		</div>

		<!--modal-->
		<div class='modal fade' id='Upload_Image_Modal' role='dialog'>
			<div class='modal-dialog modal-lg'>
				<div class='modal-content'>
					<div class='modal-body'>
						<h4 class="modal-title">照片上傳</h4>
						<form action="./upload_image.php" method="post" enctype="multipart/form-data">
						    <!-- 限制上傳檔案的最大值 -->
						    <input type="hidden" name="MAX_FILE_SIZE" value="2097152">
						    <input type="hidden" id="onproduct_sn" name="onproduct_sn" value="">
						    <input type="hidden" id="onproduct_type" name="onproduct_type" value="2">
						    <input type="hidden" id="parameters" name="parameters" value="<?php echo "details_table.php?onadd_part_no=".GetParam('onadd_part_no')."&onadd_growing=".GetParam('onadd_growing').'&onadd_quantity_del='.GetParam('onadd_quantity_del').'&onadd_part_name='.GetParam('onadd_part_name'); ?>">
						    <!-- accept 限制上傳檔案類型 -->
						    <input type="file" name="myFile" accept="image/jpeg,image/jpg,image/gif,image/png">

						    <input type="submit" value="上傳檔案">
						</form>
					</div>	
				</div>		
			</div>			
		</div>

		<!--顯示月份出貨明細----------------------------------------------------------->
		<div id="modal_month_customers" class="modal upd-modal2" tabindex="-1" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<form autocomplete="off" method="post" action="./" id="upd_form2" class="form-horizontal" role="form" data-toggle="validator">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
							<h4 class="modal-title" id="month_customers_title">出貨</h4>
						</div>
						<div class="modal-body">
							<div class="row" id="month_customers_cotent">
								<div class="col-md-12">									
									<div class="col-sm-10">
										<label for="addModalInput1" class="col-sm-2 control-label">客戶名稱</label>
										<label for="addModalInput1" class="col-sm-2 control-label">預計出貨數量</label>
										<label for="addModalInput1" class="col-sm-2 control-label">預計出貨日期</label>
										<label for="addModalInput1" class="col-sm-2 control-label">該筆新增日期</label>
									</div>	
								</div>

							</div>
						</div>

						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">關閉</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!--顯示月份出貨明細----------------------------------------------------------->

		<!--汰除----------------------------------------------------------->
		<div id="eli-modal1" class="modal upd-modal1" tabindex="-1" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<form autocomplete="off" method="post" action="./details_table.php" id="eli_form1" class="form-horizontal" role="form" data-toggle="validator">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
							<h4 class="modal-title">汰除</h4>
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-md-12">
									<input type="hidden" name="op" value="eli">
									<input type="hidden" name="onadd_sn">
									<input type="hidden" name="onadd_newpot_sn">
									<div class="form-group">
										<label for="addModalInput1" class="col-sm-2 control-label">品號<font color="red">*</font></label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="addModalInput1" name="onadd_part_no" placeholder="" required minlength="1" maxlength="32" readonly="readonly">
											<div class="help-block with-errors"></div>
										</div>
									</div>
									<div class="form-group">
										<label for="addModalInput1" class="col-sm-2 control-label">下種數量<font color="red">*</font></label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="addModalInput1" name="onadd_quantity" placeholder="" required minlength="1" maxlength="32" readonly="readonly">
											<div class="help-block with-errors"></div>
										</div>
									</div> 
									<div class="form-group">
										<label for="addModalInput1" class="col-sm-2 control-label">汰除數量<font color="red">*</font></label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="addModalInput1" name="onadd_quantity_del" placeholder="" required minlength="1" maxlength="32">
											<div class="help-block with-errors"></div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label">汰除原因<font color="red">*</font></label>
										<div class="col-sm-10">
											<select class="form-control" name="onelda_reason">
												<option value="4">其他</option>
												<option value="3">黑頭</option>
												<option value="2">褐斑</option>
												<option selected="selected" value="1">軟腐</option>
											</select>
										</div>
									</div>        								
								</div>
							</div>
						</div>

						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
							<button type="submit" class="btn btn-primary">更新</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!--汰除----------------------------------------------------------->

		<!--預計出貨----------------------------------------------------------->
		<div id="upd-modal1" class="modal upd-modal1" tabindex="-1" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<form autocomplete="off" method="post" action="./" id="upd_form1" class="form-horizontal" role="form" data-toggle="validator">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
							<h4 class="modal-title">新增預計出貨</h4>
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-md-12">
									<input type="hidden" name="op" value="upd1">
									<input type="hidden" name="onadd_sn">
									<input type="hidden" name="onbuda_part_no">
									<input type="hidden" name="onbuda_part_name">
									<div class="form-group">
										<label for="addModalInput1" class="col-sm-2 control-label">預計出貨數量<font color="red">*</font></label>
										<div class="col-sm-10">
											
											<input type="text" class="form-control" id="addModalInput1" name="onbuda_quantity" placeholder="" required minlength="1" maxlength="32">
											<div class="help-block with-errors"></div>
										</div>
									</div> 
									<div class="form-group">
										<label class="col-sm-2 control-label">預計出貨日期&nbsp;</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="datetimepicker1" name="onbuda_date" placeholder="">
											<div class="help-block with-errors"></div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label">預計出貨尺寸<font color="red">*</font></label>
										<div class="col-sm-10">
											<select class="form-control" name="onbuda_size">
												<option value="7">其他</option>
												<option value="6">3.6</option>
												<option value="5">3.5</option>
												<option value="4">3.0</option>
												<option value="3">2.8</option>
												<option value="2">2.5</option>
												<option selected="selected" value="1">1.7</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label for="addModalInput1" class="col-sm-2 control-label">出貨對象<font color="red">*</font></label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="addModalInput1" name="onbuda_client" placeholder="" required minlength="1" maxlength="32">
											<div class="help-block with-errors"></div>
										</div>
									</div>   
									<div class="form-group">
										<label for="addModalInput1" class="col-sm-2 control-label">銷售人員<font color="red">*</font></label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="addModalInput1" name="onbuda_seller" placeholder="" required minlength="1" maxlength="32">
											<div class="help-block with-errors"></div>
										</div>
									</div>  
									<div class="form-group">
										<label for="addModalInput1" class="col-sm-2 control-label">銷售價格(單棵)<font color="red">*</font></label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="addModalInput1" name="onbuda_sell_price" placeholder="" required minlength="1" maxlength="32">
											<div class="help-block with-errors"></div>
										</div>
									</div>  							
								</div>
							</div>
						</div>

						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
							<button type="submit" class="btn btn-primary">新增</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!--預計出貨----------------------------------------------------------->

		<!-- Page Content -->
		<div  class="container-fluid">
			<div class="row">
				<!-- <div class="col-md-5"></div> -->
				<div class="col-md-5">
					<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
						<!-- Indicators -->
						<ol class="carousel-indicators">
							<?php							
							$pics = getPic($data_list[0]['onproduct_sn']);
							for($i=0;$i<count($pics);$i++){
								if($i==0)
									echo '<li data-target="#carousel-example-generic" data-slide-to="'.$i.'" class="active"></li>';
								else
									echo '<li data-target="#carousel-example-generic" data-slide-to="'.$i.'"></li>';
							}
							?>
						</ol>
						<div class="carousel-inner" style='text-align:center;'>
							<?php
								if(!empty($data_list[0]['onproduct_pic_url'])){									
									for($i=0;$i<count($pics);$i++){
										if($i==0){
											echo '<div class="item active">';
												echo "<img class='img-rounded' src='".$pics[$i]['onpic_img_path']."'>";
											echo '</div>';
										}
										else{
											echo '<div class="item">';
												echo "<img class='img-rounded' src='".$pics[$i]['onpic_img_path']."'>";
											echo '</div>';
										}
									}
								}
								else
									echo "<img class='img-rounded' style='text-align:center;' src='images/nopic.png' >";
							?>
							
						</div>
					</div>
					<a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
						<span class="glyphicon glyphicon-chevron-left"></span>
					</a>
					<a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
						<span class="glyphicon glyphicon-chevron-right"></span>
					</a>
				</div> 
				<div class="col-md-4">
					<?php
					foreach ($data_list as $row) {
						echo '<div style="display:none" id="onproduct_sn">'.$row['onproduct_sn'].'</div>';
						echo '<h3>'.$onproduct_part_no.'</h3>';
					?> 
						<table style="font-size: 1.5rem" class="table table-hover">
							<thead>
								<tr>
									<th style="text-align: center;font-size: 1.2em">詳細資料</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>品號(Part no.)：
										<?php echo $row['onproduct_part_no']; ?> 
									</td>
								</tr>
								<tr>
									<td>品名(Part name.)：
										<?php echo $row['onproduct_part_name']; ?>
									</td>
								</tr>
								<tr>
									<td>花色 (Flower Color)：
										<?php echo $row['onproduct_color']; ?>
									</td>
								</tr>
								<tr>
									<td>花徑 (Flower Size)：
										<?php echo $row['onproduct_size']; ?>
									</td>
								</tr>
								<tr>
									<td>高度 (Plant Height)：
										<?php echo $row['onproduct_height']; ?>
									</td>
								</tr>
								<tr>
									<td>適合開花盆徑 (Suitable flowering pot size)：
										<?php echo $row['onproduct_pot_size']; ?> 
									</td>
								</tr>
							</tbody>								
						</table>				
					<?php }
				?> 
				</div>         
			</div>
		</div>
		<hr>
		
			</div>
		</div></br>

		<!-- <?php printr($data_list);?> -->
		<div class="col-md-6" style="margin-bottom: 10px;clear:both;">
			<ul class="nav nav-pills pull-right toolbar">
				<?php if($permmsion == 0 || $permmsion == 3){ ?>
					<li><button type="button" class="btn btn-primary btn-xs" onClick="upd_btn_click(<?php echo $data_list[0]['onproduct_sn'];?>)"><i class="glyphicon glyphicon-plus"></i>新增更多圖片</button></li>
					<li><button type="button" class="btn btn-primary btn-xs upd1" <?php echo "data-onproduct_part_no=\"".$data_list[0]['onproduct_part_no']."\" data-onproduct_part_name=\"".$data_list[0]['onproduct_part_name']."\"" ;?>><i class="glyphicon glyphicon-plus" ></i>預計出貨資料</button></li>
				<?php } ?>
			</ul>
			<hr>
			<table id="table_summary" class="table table-hover">
				<thead style="font-size: 1.3em">
					<tr>
						<th style="text-align: center;">下種日期</th>
						<th style="text-align: center;">目前尺寸</th>
						<th style="text-align: center;">數量</th>
						<!-- <?php if($permmsion == 0){ ?>
							<th style="text-align: center;">操作</th> 
						<?php } ?>   -->    						
					</tr>
				</thead>
				<tbody></tbody>
					<?php
						for($i=0;$i<count($eli_list);$i++){
							echo '<tr>';
							echo '<td style="vertical-align: middle;text-align: center;">'.date('Y-m-d',$eli_list[$i]['onadd_planting_date']).'</td>';
							// if($eli_list[$i]['onadd_cur_size'] == 0 || $eli_list[$i]['onadd_cur_size'] == 8)
							// 	echo '<td style="vertical-align: middle;text-align: center;">'.$permissions_mapping[$eli_list[$i]['onadd_cur_size']].'</td>';
							// else
							echo '<td style="vertical-align: middle;text-align: center;">'.$permissions_mapping[$eli_list[$i]['onadd_cur_size']].'寸</td>';
							echo '<td style="vertical-align: middle;text-align: center;">'.$eli_list[$i]['SUM(onadd_quantity)'].'</td>';
							// if($permmsion == 0){
							// 	echo '<td style="vertical-align: middle;text-align: center;">'.'<a href="javascript:do_emli(\''.$eli_list[$i]['onadd_sn'].'\');"><button type="button" class="btn btn-xs btn-warning">汰除</button></a></td>'; 
							// }     
							echo '</tr>';  							
						}
					?>    
				</tbody>
			</table>
		</div>

		<!-- container -->
		<div  class="container-fluid">
			<div class="row">
				<div class="col-md-8">
					<?php
					$href = './details_table.php?onadd_part_no='.$onadd_part_no.'&onadd_growing='.$onadd_growing.'&onadd_quantity_del='.'2020'.'&end='.$end;
					?>
					<!-- echo '<td><button type="button" class="btn btn-info btn-xs" onclick="location.href=\'./details_table.php?onadd_part_no='.$row['onadd_part_no'].'&onadd_growing='.$row['onadd_growing'].'&onadd_quantity_del='.$row['onadd_quantity_del'].'&start='.$start.'&end='.$end.'\'">查看</button></td>'; -->

					<!-- details_table.php?onadd_part_no=PP-0052&onadd_growing=1&onadd_quantity_del=2019 -->
					<ul class="nav nav-tabs" style="font-size: 1.2em">
						<?php
						$font_size = '';
						// echo GetParam('onadd_quantity_del');
						for($i=0;$i<5;$i++){
							$n = ((date('Y')-1)+$i);
							if($n == GetParam('onadd_quantity_del')){
								echo '<li class="active"><a style="color:#000000;">'.$n.'</a></li>';
							}
							else{
								echo '<li class="active"><a style="color:#23b7e5;" href="'.WT_URL_ROOT.'/admin/purchase/details_table.php?onadd_part_no='.GetParam('onadd_part_no').'&onadd_growing='.GetParam('onadd_growing').'&onadd_quantity_del='.$n.'&onadd_part_name='.GetParam('onadd_part_name').'">'.$n.'</a></li>';
							}
						}
						?>
					</ul>
                </div>
            </div>
        </div>
        <div class="container-fluid">
        	<div class="row">
        		<div class="col-md-8">
        			<table id="table_summary" class="table table-hover table-condensed table-bordered">
        				<thead>
        					<tr>
        						<th style="text-align: center; vertical-align: middle;font-size: 1.1em" rowspan="2">出售</br>尺寸</th>
        						<th style="text-align: center; font-size: 1.2em" colspan="12" class="tableheader" align="center">可供出售月份(系統計算)</th>
        					</tr>
        					<tr>
        						<th style="text-align: center;">一月</th>
        						<th style="text-align: center;">二月</th>
        						<th style="text-align: center;">三月</th>
        						<th style="text-align: center;">四月</th>
        						<th style="text-align: center;">五月</th>
        						<th style="text-align: center;">六月</th>
        						<th style="text-align: center;">七月</th>
        						<th style="text-align: center;">八月</th>
        						<th style="text-align: center;">九月</th>
        						<th style="text-align: center;">十月</th>
        						<th style="text-align: center;">十一月</th>
        						<th style="text-align: center;">十二月</th>
        					</tr>
        				</thead>
        				<tbody>
        					<?php
        					for($i=0;$i<8;$i++){
        						$n = 0;
        						for($j=1;$j<=12;$j++){
        							$n += $user_list[$i][$j];
        						}
        						if($n != 0){
        							echo '<tbody>';
        							echo '<td style="text-align: center;">'.$permissions_mapping[$i].'寸'.'</td>';
		        					for($j = 1 ;$j <= 12;$j++){
		        						echo '<td style="text-align: center;">'.$user_list[$i][$j].'</td>';//預計成熟月份數量
		                            }
		                            echo '</tbody>';
        						}

        					}
                             ?>
                         </tbody>
                     </table>

                     <table id="table_summary" class="table table-striped table-hover table-condensed table-bordered">
        				<thead>
        					<tr>
        						<th style="text-align: center; vertical-align: middle;font-size: 1.1em" rowspan="2">預計</br>尺寸</th>
        						<th style="text-align: center;font-size: 1.2em" colspan="12" class="tableheader" align="center">預計出貨月份</th>
        					</tr>
        					<tr>
        						<th style="text-align: center;">一月</th>
        						<th style="text-align: center;">二月</th>
        						<th style="text-align: center;">三月</th>
        						<th style="text-align: center;">四月</th>
        						<th style="text-align: center;">五月</th>
        						<th style="text-align: center;">六月</th>
        						<th style="text-align: center;">七月</th>
        						<th style="text-align: center;">八月</th>
        						<th style="text-align: center;">九月</th>
        						<th style="text-align: center;">十月</th>
        						<th style="text-align: center;">十一月</th>
        						<th style="text-align: center;">十二月</th>
        					</tr>
        				</thead>

        					<?php        					
        					for($size_n=1;$size_n <= 6;$size_n++){
        						echo '<tbody>'; 
        						if(!empty($business_data[$size_n]['size'])){
	            	             	echo '<td style="vertical-align: middle; text-align: center;">'.$permissions_mapping[$business_data[$size_n]['size']].'寸'.'</td>';
	        						for($i = 1 ;$i <= 12;$i++){
	        							if(!empty($business_data[$size_n][$i]))
	            	                        echo '<td style="vertical-align: middle;text-align: center;"><a href="javascript: void(0)" onclick="customer_list(\''.$onadd_part_no.'\','.$onadd_quantity_del.','.($i).','.$business_data[$size_n]['size'].')">'.$business_data[$size_n][$i].'</a></td>';//品號
	            	                   	else
	            	                   		echo '<td style="vertical-align: middle; text-align: center;">0</td>';
	            	             	}
	            	             }
            	             	echo '</tbody>';        					
                        	}
                            ?>
                         
                     </table>
                 </div>
             </div>
         </div>
         <?php
         	// $data = "2019-05-01";
         	// echo substr($data,0,4);
         	// echo substr($data,6,-3);
         ?>
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
 </html>?>