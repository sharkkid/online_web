<?php
include_once("./func_plant_business.php");
$status_mapping = array(0=>'<font color="red">關閉</font>', 1=>'<font color="blue">啟用</font>');
$DEVICE_SYSTEM = array(
	1=>"1.7",
	2=>"2.5",
	3=>"2.8",
	4=>"3.0",
	5=>"3.5",
	6=>"3.6",
	7=>"其他"
		// 1.7, 2.5, 2.8, 3.0, 3.5, 3.6 其他
);
$permissions_mapping = array(
	1=>'<font color="#666666">1.7</font>',
	2=>'<font color="#666666">2.5</font>',
	3=>'<font color="#666666">2.8</font>',
	4=>'<font color="#666666">3.0</font>',
	5=>'<font color="#666666">3.5</font>',
	6=>'<font color="#666666">3.6</font>',
	7=>'<font color="#666666">其他</font>' 
);

$op=GetParam('op');
if(!empty($op)) {
	$ret_code = 1;
	$ret_msg = '';
	$ret_data = array();
	switch ($op) {
		case 'add':
		$oncoda_add_date=GetParam('oncoda_add_date');//建立日期
		$oncoda_mod_date=GetParam('oncoda_mod_date');//修改日期
		$oncoda_grass=GetParam('oncoda_grass');//品號
		$oncoda_labor=GetParam('oncoda_labor');//品名
		$oncoda_water=GetParam('oncoda_water');//花色
		$oncoda_status=GetParam('oncoda_status');//狀態 1 啟用 0 刪除
		$oncoda_soft = GetParam('supplier');//編輯人員

		if(empty($oncoda_grass)||empty($oncoda_labor)){
			$ret_msg = "*為必填！";
		} else { 
			$user = getUserByAccount($oncoda_grass);
			$now = time();
			$conn = getDB();
			$sql = "INSERT INTO online_cost_data (oncoda_add_date, oncoda_mod_date, oncoda_grass, oncoda_labor, oncoda_water, oncoda_electricity, oncoda_status, oncoda_soft) " .
			"VALUES ('{$now}', '{$now}', '{$oncoda_grass}', '{$oncoda_labor}', '{$oncoda_water}', '{$oncoda_electricity}', '1', '{$oncoda_soft}');";
			if($conn->query($sql)) {
				$ret_msg = "新增成功！";
			} else {
				$ret_msg = "新增失敗！";
			}
			$conn->close();
		}
		break;

		case 'get':
		$oncoda_sn=GetParam('oncoda_sn');
		$ret_data = array();
		if(!empty($oncoda_sn)){
			$ret_code = 1;
			$ret_data = getUserBySn($oncoda_sn);
		} else {
			$ret_code = 0;
		}

		break;

		case 'upd':
		$oncoda_sn=GetParam('oncoda_sn');
		$oncoda_add_date=GetParam('oncoda_add_date');//建立日期
		$oncoda_mod_date=GetParam('oncoda_mod_date');//修改日期
		$oncoda_grass=GetParam('oncoda_grass');//品號
		$oncoda_labor=GetParam('oncoda_labor');//品名
		$oncoda_water=GetParam('oncoda_water');//花色
		$oncoda_electricity=GetParam('oncoda_electricity');//花徑
		// $oncoda_status=GetParam('oncoda_status');//狀態 1 啟用 0 刪除
		$oncoda_soft = GetParam('supplier');//編輯人員
		$user = getUserByAccount($oncoda_grass);
		$onadd_planting_date = str2time($onadd_planting_date);
		$now = time();
		$conn = getDB();
		$sql = "INSERT INTO online_cost_data (oncoda_add_date, oncoda_mod_date, oncoda_grass, oncoda_labor, oncoda_water, oncoda_electricity, oncoda_status, oncoda_soft) " .
		"VALUES ('{$now}', '{$now}', '{$oncoda_grass}', '{$oncoda_labor}', '{$oncoda_water}', '{$oncoda_electricity}', '1', '{$oncoda_soft}');";

		$sql1 = "UPDATE online_cost_data SET oncoda_status='{$oncoda_status}' WHERE oncoda_sn='{$oncoda_sn}'";
		if($conn->query($sql) && $conn->query($sql1)) {
			$ret_msg = "新增成功！";
		} else {
			$ret_msg = "新增失敗！";
		}
		$conn->close();
		break;

		//汰除---------------------------------------------
		case 'upd1':
		$oncoda_sn=GetParam('oncoda_sn');
		$list = getUserBySn($oncoda_sn);
		$oncoda_grass = $list['oncoda_grass'];
		$oncoda_labor = $list['oncoda_labor'];
		$onadd_quantity=GetParam('onadd_quantity');//下種數量
		$oncoda_soft = GetParam('supplier');//編輯人員
		$onadd_quantity_del=GetParam('onadd_quantity_del');//汰除數量
		$onelda_reason=GetParam('onelda_reason');//汰除原因
		$oncoda_soft = GetParam('supplier');//編輯人員
		$onadd_quantity_del123 = ($onadd_quantity - $onadd_quantity_del);
		if($onadd_quantity_del123<=0) {
			$oncoda_status = -1;
		} else {
			$oncoda_status = 1;
		}

		if(empty($onadd_quantity_del)){
			$ret_msg = "*為必填！";
		} else {
			$now = time();
			$conn = getDB();
			$sql1 = "UPDATE online_cost_data SET onadd_quantity='{$onadd_quantity_del123}', oncoda_status='{$oncoda_status}' WHERE oncoda_sn='{$oncoda_sn}'";
			if($conn->query($sql1)) {
				$ret_msg = "修改完成！";
			} else {
				$ret_msg = "修改失敗！";
			}
		}

		break;
		//汰除---------------------------------------------

		//出貨---------------------------------------------
		case 'upd2':
		$oncoda_sn=GetParam('oncoda_sn');
		$list = getUserBySn($oncoda_sn);
		$oncoda_grass = $list['oncoda_grass'];
		$oncoda_labor = $list['oncoda_labor'];
		$onadd_quantity=GetParam('onadd_quantity');//下種數量
		$oncoda_soft = GetParam('supplier');//編輯人員
		$onadd_plant_year=GetParam('onadd_plant_year');//汰除數量
		$onshda_client=GetParam('onshda_client');//汰除數量
		$onadd_quantity_shi123 = ($onadd_quantity - $onadd_plant_year);
		if($onadd_quantity_shi123<=0) {
			$oncoda_status = -1;
		} else {
			$oncoda_status = 1;
		}

		if(empty($onadd_plant_year)){
			$ret_msg = "*為必填！";
		} else {
			$now = time();
			$conn = getDB();
			$sql = "UPDATE online_cost_data SET onadd_quantity='{$onadd_quantity_shi123}', oncoda_status='{$oncoda_status}' WHERE oncoda_sn='{$oncoda_sn}'";
			if($conn->query($sql)) {
				$ret_msg = "修改完成！";
			} else {
				$ret_msg = "修改失敗！";
			}
			$conn->close();
		}

		if(empty($onadd_plant_year)){
			$ret_msg = "*為必填！";
		} else {
			$now = time();
			$conn = getDB();
			$sql = "INSERT INTO online_shipment_data (onshda_add_date, onshda_mod_date, onshda_client, onshda_quantity, oncoda_sn, oncoda_grass, oncoda_labor) " .
			"VALUES ('{$now}', '{$now}', '{$onshda_client}', '{$onadd_plant_year}', '{$oncoda_sn}', '{$oncoda_grass}', '{$oncoda_labor}');";
			if($conn->query($sql)) {
				$ret_msg = "修改完成！";
			} else {
				$ret_msg = "修改失敗！";
			}			
			$conn->close();
		} 
		break;
		//出貨---------------------------------------------

		case 'del':
		$oncoda_sn=GetParam('oncoda_sn');

		if(empty($oncoda_sn)){
			$ret_msg = "刪除失敗！";
		}else{
			$now = time();
			$conn = getDB();
			$sql = "DELETE FROM online_cost_data WHERE oncoda_sn='{$oncoda_sn}'";
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
	}

	echo enclode_ret_data($ret_code, $ret_msg, $ret_data);
	exit;
} else {
	$cost_table = get_CostTable();
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
	<script src="./../../lib/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js"></script>
	<link rel="stylesheet" href="./../../lib/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css">
	<script type="text/javascript">
		$(document).ready(function() {
			<?php
					//	init search parm
			print "$('#search [name=oncoda_status] option[value={$oncoda_status}]').prop('selected','selected');";
			print "$('#search [name=onadd_growing] option[value={$onadd_growing}]').prop('selected','selected','selected','selected','selected','selected','selected');";
			?>

			$('button.upd').on('click', function(){
				$('#upd-modal').modal();
				$('#upd_form')[0].reset();

				$.ajax({
					url: './plant_business.php',
					type: 'post',
					dataType: 'json',
					data: {op:"get", oncoda_sn:$(this).data('oncoda_sn')},
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
			                	$('#upd_form input[name=oncoda_sn]').val(d.oncoda_sn);
			                	$('#upd_form input[name=oncoda_grass]').val(d.oncoda_grass);
			                	$('#upd_form input[name=oncoda_labor]').val(d.oncoda_labor);
			                	$('#upd_form input[name=oncoda_water]').val(d.oncoda_water);
			                	$('#upd_form input[name=oncoda_electricity]').val(d.oncoda_electricity);
			                	$('#upd_form input[name=onadd_height]').val(d.onadd_height);
			                	$('#upd_form input[name=onadd_pot_size]').val(d.onadd_pot_size);
			                	$('#upd_form input[name=onadd_supplier]').val(d.onadd_supplier);
			                	// $('#upd_form input[name=onadd_planting_date]').val(d.onadd_planting_date);
			                	$('#upd_form input[name=onadd_quantity]').val(d.onadd_quantity);
			                	// $('#upd_form input[name=onadd_growing]').val(d.onadd_growing);		                	
			                	$('#upd_form [name=oncoda_status] option[value='+d.oncoda_status+']').prop('selected','selected');
			                }
			            },
			            error: function (xhr, ajaxOptions, thrownError) {
		                	// console.log('ajax error');
		                    // console.log(xhr);
		                }
		            });
			});

			//汰除-----------------------------------------------------------
			$('button.upd1').on('click', function(){
				$('#upd-modal1').modal();
				$('#upd_form1')[0].reset();

				$.ajax({
					url: './plant_business.php',
					type: 'post',
					dataType: 'json',
					data: {op:"get", oncoda_sn:$(this).data('oncoda_sn')},
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
			                	$('#upd_form1 input[name=oncoda_sn]').val(d.oncoda_sn);
			                	$('#upd_form1 input[name=oncoda_grass]').val(d.oncoda_grass);
			                	$('#upd_form1 input[name=oncoda_soft]').val(d.oncoda_soft);
			                	$('#upd_form1 input[name=oncoda_labor]').val(d.oncoda_labor);
			                	$('#upd_form1 input[name=oncoda_water]').val(d.oncoda_water);
			                	$('#upd_form1 input[name=oncoda_electricity]').val(d.oncoda_electricity);
			                	$('#upd_form1 input[name=onadd_quantity]').val(d.onadd_quantity);
			                }
			            },
			            error: function (xhr, ajaxOptions, thrownError) {
		                	// console.log('ajax error');
		                    // console.log(xhr);
		                }
		            });
			});
			//汰除-----------------------------------------------------------

			//出貨-----------------------------------------------------------
			$('button.upd2').on('click', function(){
				$('#upd-modal2').modal();
				$('#upd_form2')[0].reset();

				$.ajax({
					url: './plant_business.php',
					type: 'post',
					dataType: 'json',
					data: {op:"get", oncoda_sn:$(this).data('oncoda_sn')},
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
			                	$('#upd_form2 input[name=oncoda_sn]').val(d.oncoda_sn);
			                	$('#upd_form2 input[name=oncoda_grass]').val(d.oncoda_grass);
			                	$('#upd_for2 input[name=oncoda_soft]').val(d.oncoda_soft);
			                	$('#upd_form2 input[name=oncoda_labor]').val(d.oncoda_labor);
			                	$('#upd_form2 input[name=oncoda_water]').val(d.oncoda_water);
			                	$('#upd_form2 input[name=oncoda_electricity]').val(d.oncoda_electricity);
			                	$('#upd_form2 input[name=onadd_quantity]').val(d.onadd_quantity);
			                }
			            },
			            error: function (xhr, ajaxOptions, thrownError) {
		                	// console.log('ajax error');
		                    // console.log(xhr);
		                }
		            });
			});
			//出貨-----------------------------------------------------------

			bootbox.setDefaults({
				locale: "zh_TW",
			});

			$('button.del').on('click', function(){
				oncoda_sn = $(this).data('oncoda_sn')
				bootbox.confirm("確認刪除？", function(result) {
					if(result) {
						$.ajax({
							url: './plant_business.php',
							type: 'post',
							dataType: 'json',
							data: {op:"del", oncoda_sn:oncoda_sn},
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

			$('#add_form, #upd_form, #upd_form1, #upd_form2').validator().on('submit', function(e) {
				if (!e.isDefaultPrevented()) {
					e.preventDefault();
					var param = $(this).serializeArray();

					$(this).parents('.modal').modal('hide');
					$(this)[0].reset();

					 	// console.table(param);

					 	$.ajax({
					 		url: './plant_business.php',
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
	</script>
</head>

<body>
	<?php include('./../htmlModule/nav.php');?>
	<!--main content start-->
	<section class="main-content">



		<!--page header start-->
		<div class="page-header">
			<div class="row">
				<div class="col-sm-6">
					<h4>成本管理</h4>
				</div>
			</div>
		</div>

		<!-- modal -->
		<div id="add-modal" class="modal add-modal" tabindex="-1" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<form autocomplete="off" method="post" action="./" id="add_form" class="form-horizontal" role="form" data-toggle="validator">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
							<h4 class="modal-title">新品項資料建立</h4>
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-md-12">
									<input type="hidden" name="op" value="add">
									<div class="form-group">
										<label for="addModalInput1" class="col-sm-2 control-label">品號<font color="red">*</font></label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="addModalInput1" name="oncoda_grass" placeholder="" required minlength="1" maxlength="32">
											<div class="help-block with-errors"></div>
										</div>
									</div>
									<div class="form-group">
										<label for="addModalInput1" class="col-sm-2 control-label">品名<font color="red">*</font></label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="addModalInput1" name="oncoda_labor" placeholder="" required minlength="1" maxlength="32">
											<div class="help-block with-errors"></div>
										</div>
									</div>
									<div class="form-group">
										<label for="addModalInput1" class="col-sm-2 control-label">花色<font color="red">*</font></label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="addModalInput1" name="oncoda_water" placeholder="" required minlength="1" maxlength="32">
											<div class="help-block with-errors"></div>
										</div>
									</div>
									<div class="form-group">
										<label for="addModalInput1" class="col-sm-2 control-label">花徑<font color="red">*</font></label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="addModalInput1" name="oncoda_electricity" placeholder="" required minlength="1" maxlength="32">
											<div class="help-block with-errors"></div>
										</div>
									</div>
									<div class="form-group">
										<label for="addModalInput1" class="col-sm-2 control-label">高度<font color="red">*</font></label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="addModalInput1" name="onadd_height" placeholder="" required minlength="1" maxlength="32">
											<div class="help-block with-errors"></div>
										</div>
									</div>
									<div class="form-group">
										<label for="addModalInput1" class="col-sm-2 control-label">適合開花盆徑<font color="red">*</font></label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="addModalInput1" name="onadd_pot_size" placeholder="" required minlength="1" maxlength="32">
											<div class="help-block with-errors"></div>
										</div>
									</div>
									<div class="form-group">
										<label for="addModalInput1" class="col-sm-2 control-label">供應商<font color="red">*</font></label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="addModalInput1" name="onadd_supplier" placeholder="" required minlength="1" maxlength="32">
											<div class="help-block with-errors"></div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label">下種日期&nbsp;</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="datetimepicker1" name="onadd_planting_date" placeholder="">
											<div class="help-block with-errors"></div>
										</div>
									</div>
									<div class="form-group">
										<label for="addModalInput1" class="col-sm-2 control-label">下種數量<font color="red">*</font></label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="addModalInput1" name="onadd_quantity" placeholder="" required minlength="1" maxlength="32">
											<div class="help-block with-errors"></div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label">預計成長大小<font color="red">*</font></label>
										<div class="col-sm-10">
											<select class="form-control" name="onadd_growing">
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
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
							<button type="reset" class="btn btn-default">清空</button>
							<button type="submit" class="btn btn-primary">新增</button>
						</div>
					</form>
				</div>
			</div>
		</div>

		<div id="upd-modal" class="modal upd-modal" tabindex="-1" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<form autocomplete="off" method="post" action="./" id="upd_form" class="form-horizontal" role="form" data-toggle="validator">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
							<h4 class="modal-title">換盆</h4>
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-md-12">
									<input type="hidden" name="op" value="upd">
									<input type="hidden" name="oncoda_sn">
									<div class="form-group">
										<label for="addModalInput1" class="col-sm-2 control-label">品號<font color="red">*</font></label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="addModalInput1" name="oncoda_grass" placeholder="" required minlength="1" maxlength="32">
											<div class="help-block with-errors"></div>
										</div>
									</div>
									<div class="form-group">
										<label for="addModalInput1" class="col-sm-2 control-label">品名<font color="red">*</font></label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="addModalInput1" name="oncoda_labor" placeholder="" required minlength="1" maxlength="32">
											<div class="help-block with-errors"></div>
										</div>
									</div>
									<div class="form-group">
										<label for="addModalInput1" class="col-sm-2 control-label">花色<font color="red">*</font></label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="addModalInput1" name="oncoda_water" placeholder="" required minlength="1" maxlength="32">
											<div class="help-block with-errors"></div>
										</div>
									</div>
									<div class="form-group">
										<label for="addModalInput1" class="col-sm-2 control-label">花徑<font color="red">*</font></label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="addModalInput1" name="oncoda_electricity" placeholder="" required minlength="1" maxlength="32">
											<div class="help-block with-errors"></div>
										</div>
									</div>
									<div class="form-group">
										<label for="addModalInput1" class="col-sm-2 control-label">高度<font color="red">*</font></label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="addModalInput1" name="onadd_height" placeholder="" required minlength="1" maxlength="32">
											<div class="help-block with-errors"></div>
										</div>
									</div>
									<div class="form-group">
										<label for="addModalInput1" class="col-sm-2 control-label">適合開花盆徑<font color="red">*</font></label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="addModalInput1" name="onadd_pot_size" placeholder="" required minlength="1" maxlength="32">
											<div class="help-block with-errors"></div>
										</div>
									</div>
									<div class="form-group">
										<label for="addModalInput1" class="col-sm-2 control-label">供應商<font color="red">*</font></label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="addModalInput1" name="onadd_supplier" placeholder="" required minlength="1" maxlength="32">
											<div class="help-block with-errors"></div>
										</div>
									</div>
									<div class="form-group">
										<label for="addModalInput1" class="col-sm-2 control-label">下種數量<font color="red">*</font></label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="addModalInput1" name="onadd_quantity" placeholder="" required minlength="1" maxlength="32">
											<div class="help-block with-errors"></div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label">換盆日期&nbsp;</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="datetimepicker2" name="onadd_planting_date" value="<?php echo (empty($device['onadd_planting_date'])) ? '' : date('Y-m-d', $device['onadd_planting_date']);?>" placeholder="">
											<div class="help-block with-errors"></div>
										</div>
									</div>        								
									<div class="form-group">
										<label for="addModalInput1" class="col-sm-2 control-label" >換盆數量<font color="red">*</font></label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="addModalInput1" name="onadd_plant_day" placeholder="" required minlength="1" maxlength="32">
											<div class="help-block with-errors"></div>
										</div>
									</div>	
									<div class="form-group">
										<label class="col-sm-2 control-label">預計成長大小<font color="red">*</font></label>
										<div class="col-sm-10">
											<select class="form-control" name="onadd_growing">
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
		<div id="upd-modal1" class="modal upd-modal1" tabindex="-1" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<form autocomplete="off" method="post" action="./" id="upd_form1" class="form-horizontal" role="form" data-toggle="validator">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
							<h4 class="modal-title">成本修改</h4>
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-md-12">
									<input type="hidden" name="op" value="upd1">
									<input type="hidden" name="oncoda_sn">
									<div class="form-group">
										<label for="addModalInput1" class="col-sm-2 control-label">1.7<font color="red">*</font></label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="addModalInput1" name="oncoda_soft" placeholder="" required minlength="1" maxlength="32">
											<div class="help-block with-errors"></div>
										</div>
									</div>
									<div class="form-group">
										<label for="addModalInput1" class="col-sm-2 control-label">2.5<font color="red">*</font></label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="addModalInput1" name="oncoda_grass" placeholder="" required minlength="1" maxlength="32">
											<div class="help-block with-errors"></div>
										</div>
									</div>	
									<div class="form-group">
										<label for="addModalInput1" class="col-sm-2 control-label">3.5<font color="red">*</font></label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="addModalInput1" name="oncoda_labor" placeholder="" required minlength="1" maxlength="32">
											<div class="help-block with-errors"></div>
										</div>
									</div>	
									<div class="form-group">
										<label for="addModalInput1" class="col-sm-2 control-label">其他<font color="red">*</font></label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="addModalInput1" name="oncoda_water" placeholder="" required minlength="1" maxlength="32">
											<div class="help-block with-errors"></div>
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
		<!--汰除----------------------------------------------------------->
		<div id="upd-modal2" class="modal upd-modal2" tabindex="-1" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<form autocomplete="off" method="post" action="./" id="upd_form2" class="form-horizontal" role="form" data-toggle="validator">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
							<h4 class="modal-title">成本修改</h4>
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-md-12">
									<input type="hidden" name="op" value="upd1">
									<input type="hidden" name="oncoda_sn">
									<div class="form-group">
										<label for="addModalInput1" class="col-sm-2 control-label">水費<font color="red">*</font></label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="addModalInput1" name="oncoda_water" placeholder="" required minlength="1" maxlength="32">
											<div class="help-block with-errors"></div>
										</div>
									</div>
									<div class="form-group">
										<label for="addModalInput1" class="col-sm-2 control-label">電費<font color="red">*</font></label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="addModalInput1" name="oncoda_electricity" placeholder="" required minlength="1" maxlength="32">
											<div class="help-block with-errors"></div>
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

		<!-- container -->
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="navbar-collapse collapse pull-right" style="margin-bottom: 10px;">
						<ul class="nav nav-pills pull-right toolbar">
							<li><button data-parent="#toolbar" data-toggle="modal" data-target=".add-modal" class="accordion-toggle btn btn-primary"><i class="glyphicon glyphicon-plus"></i> 成本種類建立</button></li>
						</ul>
					</div>
					<!-- content -->
					<?php 
						foreach ($cost_table as $key => $value) {
					?>
						<div id="search" style="clear:both;">
							<div class="row">
								<div class="col-md-10">
									<div class="h4 page-header text-center offset-bottom" style="background-color:#D1E9E9;"><?php echo $value['oncost_name']?></div>
								</div>
								<div class="col-md-2">
									<button type="button" class="btn btn-primary btn-xs upd1" data-onadd_sn="">新增</button>
								</div>
	
							</div>
							<!-- content -->
							<table class="table table-striped table-hover table-condensed tablesorter">
								<thead>
									<tr style="font-size: 1.1em">
										<th style="text-align: center;">項目</th>
										<th style="text-align: center;">單位</th>
										<th style="text-align: center;">成本金額</th>
										<th style="text-align: center;">操作</th>
									</tr>
								</thead>
								<tbody >
									<?php 
										$cost_detail = get_CostDetail($value['oncost_sn']);
										foreach ($cost_detail as $key2 => $value2) {
									?>
									<tr style="font-size: 1.1em">
										<td  style="text-align: center;"><?php echo $value2['oncoda_name']; ?></td>
										<td  style="text-align: center;"><?php echo $value2['oncoda_num'].$value2['oncoda_unit']; ?></td>
										<td  style="text-align: center;"><?php echo $value2['oncoda_cost']." NT"; ?></td>
										<td  style="text-align: center;">
											<button type="button" class="btn btn-primary btn-xs upd1" data-onadd_sn="">修改</button>
											<button type="button" class="btn btn-danger btn-xs upd1" data-onadd_sn="">刪除</button>
										</td>
									</tr>
									<?php 
										}
									?>
								</tbody>
							</table>
						</div> 
					<?php 
						}
					?>
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
    </html>?>