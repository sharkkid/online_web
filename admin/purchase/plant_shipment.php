<?php
include_once("./func_plant_shipment.php");
$export_error = GetParam('export_error');
$status_mapping = array(0=>'<font color="red">關閉</font>', 1=>'<font color="blue">啟用</font>');
$DEVICE_SYSTEM = array(
		1=>"1.7",
		2=>"2.5",
		3=>"2.8",
		4=>"3.0",
		5=>"3.5",
		6=>"3.6",
		7=>"其他",
		8=>"瓶苗下種"
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
		
	}

	echo enclode_ret_data($ret_code, $ret_msg, $ret_data);
	exit;
} else {
	// search
	if(($onadd_part_no = GetParam('onadd_part_no'))) {
		$search_where[] = "a.onadd_part_no like '%{$onadd_part_no}%'";
		$search_query_string['onadd_part_no'] = $onadd_part_no;
	}

	if(($onadd_part_name = GetParam('onadd_part_name'))) {
		$search_where[] = "a.onadd_part_name like '%{$onadd_part_name}%'";
		$search_query_string['onadd_part_name'] = $onadd_part_name;
	}

	if(($start = GetParam('start',""))) {
		$start_c = str2time($start ." 00:00");
		$search_where[] = "a.onshda_add_date>={$start_c}";
		$search_query_string['start'] = $start;
	} else {
		$start_c = time() - 30 * 86400;
		$start = date('Y-m-d 00:00', $start_c);
		$search_where[] = "a.onshda_add_date>={$start_c}";
		$search_query_string['start'] = $start;
		$start = date('Y-m-d', $start_c);
	}

	if(($end = GetParam('end',""))) {
		$end_c = str2time($end ." 23:59");
		$search_where[] = "a.onshda_add_date<={$end_c}";
		$search_query_string['end'] = $end;
	} else {
		$end_c = time();
		$end = date("Y-m-d 23:59", $end_c);
		$search_where[] = "a.onshda_add_date<={$end_c}";
		$search_query_string['end'] = $end;
		$end = date("Y-m-d", $end_c);
	}
	$search_where = isset($search_where) ? implode(' and ', $search_where) : '';
	$search_query_string = isset($search_query_string) ? http_build_query($search_query_string) : '';

	// page
	$pg_page = GetParam('pg_page', 1);
	$pg_rows = 20;
	$pg_total = GetParam('pg_total')=='' ? getUserQty($search_where) : GetParam('pg_total');
	$pg_offset = $pg_rows * ($pg_page - 1);
	$pg_pages = $pg_rows == 0 ? 0 : ( (int)(($pg_total + ($pg_rows - 1)) /$pg_rows) );

	$user_list = getUser($search_where, $pg_offset, $pg_rows);
	
	// printr($user_list);
	// exit;
	$User_forExcel = getUser_forExcel($search_where);
	
	if($export_error==1) {
        ob_end_clean(); //  避免亂碼
        header("Content-Type:text/html; charset=utf-8");
        include_once(WT_PATH_ROOT.'/lib/PHPExcel_1.8.0/PHPExcel.php');
        include_once(WT_PATH_ROOT.'/lib/PHPExcel_1.8.0/PHPExcel/Writer/Excel2007.php');

        // init excel
        $inputfilename = WT_PATH_ROOT.'/admin/purchase/shipment_temp.xls';

        if(!file_exists($inputfilename)) exceptions("查無Excel巡檢表");
        $originalexcel = PHPExcel_IOFactory::load($inputfilename);
        // init data
        $add_date = date('Y/m/d H:i:s');
        $sheetname = 'data';
        $sheet = $originalexcel->getSheetByName($sheetname);
        $sheet->freezePane('A3');
        $sheet->setTitle("出貨報表_".date("Y-m-d"));
    // 塞值
        $n = 3;
        for($i=0;$i<count($User_forExcel);$i++){
            $sheet->setCellValue('A'.($n+$i), date('Y',$User_forExcel[$i]['onshda_add_date']).'-'.$User_forExcel[$i]['onadd_sn']);//產品編號
            $sheet->setCellValue('B'.($n+$i), $User_forExcel[$i]['onadd_part_no']);//品名
            $sheet->setCellValue('C'.($n+$i), $User_forExcel[$i]['onadd_part_name']);//品號
            $sheet->setCellValue('D'.($n+$i), $DEVICE_SYSTEM[$User_forExcel[$i]['onadd_cur_size']]);//目前尺寸
            $sheet->setCellValue('E'.($n+$i), date('Y-m-d',$User_forExcel[$i]['onshda_add_date']));//出貨日期
            $sheet->setCellValue('F'.($n+$i), $User_forExcel[$i]['onshda_quantity']);//出貨數量
            $sheet->setCellValue('G'.($n+$i), $User_forExcel[$i]['onshda_price']);//出貨單價
            $sheet->setCellValue('H'.($n+$i), $User_forExcel[$i]['onshda_price']*$User_forExcel[$i]['onshda_quantity']);//總收入
            $sheet->setCellValue('I'.($n+$i), $User_forExcel[$i]['oncoda_cost']*$User_forExcel[$i]['onshda_quantity']+$User_forExcel[$i]['onadd_cost_month']*$User_forExcel[$i]['date_month']);//總成本
            $sheet->setCellValue('J'.($n+$i), $User_forExcel[$i]['onshda_price']*$User_forExcel[$i]['onshda_quantity']-($User_forExcel[$i]['oncoda_cost']*$User_forExcel[$i]['onshda_quantity']+($User_forExcel[$i]['onadd_cost_month']*$User_forExcel[$i]['date_month'])));//毛利
            $sheet->setCellValue('K'.($n+$i), $User_forExcel[$i]['onshda_client']);//客戶
        }
  //       printr($User_forExcel);
		// exit;

    // 產生檔案
        $excelextend = substr($inputfilename, strpos($inputfilename, "."));
        $filename="出貨報表_".date("YmdHis");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=".$filename.$excelextend);
        header('Cache-Control: max-age=0');
        // $objWriter = PHPExcel_IOFactory::createWriter($originalexcel, 'Excel2007');
        // $objWriter->setIncludeCharts(TRUE);
        // $objWriter->save('php://output');
        if($excelextend == "xlsx")
            $objWriter = PHPExcel_IOFactory::createWriter($originalexcel, 'Excel2007');
        else
            $objWriter = PHPExcel_IOFactory::createWriter($originalexcel, 'Excel5');
        $objWriter->save('php://output');

        unlink($ir_file_name);
        unlink($dc_file_name);
        exit;
    }
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
		$(document).ready(function() {
			<?php
					//	init search parm
			// print "$('#search [name=onadd_status] option[value={$onadd_status}]').prop('selected','selected');";
			// print "$('#search [name=onadd_growing] option[value={$onadd_growing}]').prop('selected','selected','selected','selected','selected','selected','selected');";
			?>

			$('button.upd').on('click', function(){
				$('#upd-modal').modal();
				$('#upd_form')[0].reset();

				$.ajax({
					url: './plant_purchase.php',
					type: 'post',
					dataType: 'json',
					data: {op:"get", onadd_sn:$(this).data('onadd_sn')},
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
			                	$('#upd_form input[name=onadd_sn]').val(d.onadd_sn);
			                	$('#upd_form input[name=onadd_part_no]').val(d.onadd_part_no);
			                	$('#upd_form input[name=onadd_part_name]').val(d.onadd_part_name);
			                	$('#upd_form input[name=onadd_color]').val(d.onadd_color);
			                	$('#upd_form input[name=onadd_size]').val(d.onadd_size);
			                	$('#upd_form input[name=onadd_height]').val(d.onadd_height);
			                	$('#upd_form input[name=onadd_pot_size]').val(d.onadd_pot_size);
			                	$('#upd_form input[name=onadd_supplier]').val(d.onadd_supplier);
			                	// $('#upd_form input[name=onadd_planting_date]').val(d.onadd_planting_date);
			                	$('#upd_form input[name=onadd_quantity]').val(d.onadd_quantity);
			                	// $('#upd_form input[name=onadd_growing]').val(d.onadd_growing);
			                	$('#upd_form [name=onadd_growing] option[value='+d.onadd_growing+']').prop('selected','selected','selected','selected','selected','selected','selected');
			                	$('#upd_form input[name=onadd_change_basin]').val(d.onadd_change_basin);			                	
			                	$('#upd_form [name=onadd_status] option[value='+d.onadd_status+']').prop('selected','selected');
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
					url: './plant_purchase.php',
					type: 'post',
					dataType: 'json',
					data: {op:"get", onadd_sn:$(this).data('onadd_sn')},
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
			                	$('#upd_form1 input[name=onadd_sn]').val(d.onadd_sn);
			                	$('#upd_form1 input[name=onadd_part_no]').val(d.onadd_part_no);
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
					url: './plant_purchase.php',
					type: 'post',
					dataType: 'json',
					data: {op:"get", onadd_sn:$(this).data('onadd_sn')},
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
			                	$('#upd_form2 input[name=onadd_sn]').val(d.onadd_sn);
			                	$('#upd_form2 input[name=onadd_part_no]').val(d.onadd_part_no);
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

			$('#add_form, #upd_form, #upd_form1, #upd_form2').validator().on('submit', function(e) {
				if (!e.isDefaultPrevented()) {
					e.preventDefault();
					var param = $(this).serializeArray();

					$(this).parents('.modal').modal('hide');
					$(this)[0].reset();

					 	// console.table(param);

					 	$.ajax({
					 		url: './plant_purchase.php',
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

				$('button.export_excel').on('click', function(){
            	    window.open("plant_shipment.php?export_error=1&start="+
            	    	<?PHP echo '"'.GetParam('start')."&end=".GetParam('end')."&onadd_part_no=".GetParam('onadd_part_no')."&onadd_part_name=".GetParam('onadd_part_name').'"';
            	    	?>);
	
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
        				<h4>出貨統計報表</h4>
        			</div>
        		</div>
        	</div>

        	
        	<!-- container -->
        	<div class="container-fluid">
        		<div class="row">
        			<div class="col-md-12">

        				<!-- nav toolbar -->

        				<!-- search -->
        				<div id="search" style="clear:both;">
        					<form autocomplete="off" method="get" action="./plant_shipment.php" id="search_form" class="form-inline alert alert-info" role="form">
        						<div class="row">
        							<div class="col-md-12">
        								<div class="form-group">
        										<label for="datetimepicker1">出貨日期</label>
        										<input type="text" class="form-control" id="datetimepicker1" name="start" value="<?php echo $start;?>" placeholder="">
        									</div>
        									<div class="form-group">
        										<label for="datetimepicker2">~</label>
        										<input type="text" class="form-control" id="datetimepicker2" name="end" value="<?php echo $end;?>" placeholder="">
        									</div>
        								<div class="form-group">
        									<label for="searchInput1">品號</label>
        									<input type="text" class="form-control" id="searchInput1" name="onadd_part_no" value="<?php echo $onadd_part_no;?>" placeholder="">
        								</div>
        								<div class="form-group">
        									<label for="searchInput4">品名</label>
        									<input type="text" class="form-control" id="searchInput4" name="onadd_part_name" value="<?php echo $onadd_part_name;?>" placeholder="">
        								</div>

        								<button type="submit" class="btn btn-info" op="search">搜尋</button>
        								<div class="form-group">
                                            <button type="submit" class="btn btn-info export_excel">匯出出貨報表</button>
                                        </div>
        							</div>
        						</div>
        					</form>
        				</div>

        				<!-- content -->
        				<table class="table table-striped table-hover table-condensed tablesorter">
        					<thead>
        						<tr style="font-size: 1.1em">
        							<th style="text-align: center;">產品編號</th>
        							<th style="text-align: center;">品號</th>
        							<th style="text-align: center;">品名</th>
        							<th style="text-align: center;">出貨日期</th>
        							<th style="text-align: center;">出貨數量</th>     
        							<th style="text-align: center;">價格(單棵)</th>							
        							<th style="text-align: center;">客戶</th>
        						</tr>
        					</thead>
        					<tbody>
        						<?php
        						foreach ($user_list as $row) {
        							echo '<tr>';
        							if($row['onadd_plant_st'] == 0){//產品編號
										echo '<td style="border-right:0.1rem #BEBEBE dashed;text-align: center;">'.date('Y',$row['onshda_add_date']).'-'.$row['onadd_sn'].'</td>';
									}
									else{
										echo '<td style="border-right:0.1rem #BEBEBE dashed;text-align: center;">'.'P'.date('Y',$row['onshda_add_date']).'-'.$row['onadd_sn'].'</td>';
									} 
        							echo '<td style="border-right:0.1rem #BEBEBE dashed;text-align: center;">'.$row['onadd_part_no'].'</td>';//品號
        							echo '<td style="border-right:0.1rem #BEBEBE dashed;text-align: center;">'.$row['onadd_part_name'].'</td>';//品名  							
        							echo '<td style="border-right:0.1rem #BEBEBE dashed;text-align: center;">'.date('Y-m-d',$row['onshda_add_date']).'</td>';
        							echo '<td style="border-right:0.1rem #BEBEBE dashed;text-align: center;">'.$row['onshda_quantity'].'</td>';//出貨數量
        							echo '<td style="text-align: center;">'.$row['onshda_price'].'</td>';//出貨價格(單棵)
        							echo '<td style="text-align: center;">'.$row['onshda_client'].'</td>';//出貨對象
        							echo '</td></tr>';
        						}
        						?>
        					</tbody>
        				</table>

        				<?php include('./../htmlModule/page.php');?>

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