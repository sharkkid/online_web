<?php
include_once("./func_plant_purchase.php");
	// search
if(($onadd_part_no = GetParam('onadd_part_no'))) {
	$search_where[] = "onadd_part_no like '%{$onadd_part_no}%'";
	$search_query_string['onadd_part_no'] = $onadd_part_no;
}
if(($onadd_part_name = GetParam('onadd_part_name'))) {
	$search_where[] = "onadd_part_name like '%{$onadd_part_name}%'";
	$search_query_string['onadd_part_name'] = $onadd_part_name;
}
if(($onadd_supplier = GetParam('onadd_supplier'))) {
	$search_where[] = "onadd_supplier like '%{$onadd_supplier}%'";
	$search_query_string['onadd_supplier'] = $onadd_supplier;
}
if(($onadd_status = GetParam('onadd_status', -1))>=0) {
	$search_where[] = "onadd_status='{$onadd_status}'";
	$search_query_string['onadd_status'] = $onadd_status;
}
if(($onadd_growing = GetParam('onadd_growing', -1))>=0) {
	$search_where[] = "onadd_growing='{$onadd_growing}'";
	$search_query_string['onadd_growing'] = $onadd_growing;
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
$list17 = getDetails('1');//計算1.7
$list25 = getDetails('2');//計算2.5
$list35 = getDetails('5');//計算3.5

$sum17 = $list17['SUM(onadd_quantity)'];
$sum25 = $list25['SUM(onadd_quantity)'];
$sum35 = $list35['SUM(onadd_quantity)'];

foreach ($list17 as $row) {
	$sum17 = $row['SUM(onadd_quantity)']; 
}
foreach ($list25 as $row) {
	$sum25 = $row['SUM(onadd_quantity)']; 
}
foreach ($list35 as $row) {
	$sum35 = $row['SUM(onadd_quantity)']; 
}

$op=GetParam('op');
	if(!empty($op)) {
		$ret_code = 1;
		$ret_msg = '';
		$ret_data = array();
		switch ($op) {
			case 'search_dayreport':
				// $ret_msg = "test";
				$day = GetParam('day');
				$ret_msg = "搜尋成功!";
				$ret_data = getQuantity_Day($day);
			break;
		}
		echo enclode_ret_data($ret_code, $ret_msg, $ret_data);
		exit;
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

	<script>
		$(document).ready(function () {
			$(function () {
        //page view chart
        <?php 
        //出貨統計表資料
        echo "$('#quantity_title').html(\"".date('Y')."年出貨報表\");";
	    $SellQuantity = getSellQuantity(date('Y'));
	    $EliminationQuantity = getEliminationQuantity(date('Y'));
	    $DFC_SellQuantity = "";
	    $DFC_EliminationQuantity = "";
	    $Label_Months = "";
		for($i = 0;$i < count($EliminationQuantity) ;$i++){
			if($i != count($EliminationQuantity)-1){
				//如果月份包含0 去除0
				(strpos($EliminationQuantity[$i]['months'], '0') !== false ) ? $Label_Months .= '\''.substr($EliminationQuantity[$i]['months'], 1)."月'," : '\''.$Label_Months .= $SellQuantity[$i]['months'].'月\',';
				($EliminationQuantity[$i]['quantity'] != null) ? $DFC_SellQuantity .= $EliminationQuantity[$i]['quantity']."," : $DFC_SellQuantity .= '0,';
				$DFC_EliminationQuantity .= $EliminationQuantity[$i]['quantity'].",";
			}
			else{
				(strpos($EliminationQuantity[$i]['months'], '0') !== false) ? $Label_Months .= '\''.substr($EliminationQuantity[$i]['months'], 1)."月'" : '\''.$Label_Months .= $EliminationQuantity[$i]['months'].'月\'';
				($EliminationQuantity[$i]['quantity'] != null) ? $DFC_SellQuantity .= $EliminationQuantity[$i]['quantity'] : $DFC_SellQuantity .= '0';
				$DFC_EliminationQuantity .= $EliminationQuantity[$i]['quantity'];
			}

		}

		//廠區使用空間計算
		$UsedQuantity = getUsedQuantity()[0]['add_quantity'] - (getUsedQuantity()[1]['elda_quantity']+getUsedQuantity()[2]['ship_quantity']);
		
        ?>
        //直方圖
        c3.generate({
        	bindto: '#stocked',
        	data: {
        		columns: [
        		['損耗數量', <?php echo $DFC_EliminationQuantity;?>],
        		['出貨數量', <?php echo $DFC_SellQuantity;?>]
        		],
        		colors: {
        			出貨數量: '#23b7e5',
        			損耗數量: '#ddd'
        		},
        		type: 'bar',
        		groups: [
        		['出貨數量', '損耗數量']
        		]
        	},
        	axis: {
		    x: {
		        type: 'category',
		        categories: [<?php echo $Label_Months;?>]
		    }
		}
        });
        //日報表
        var chart = c3.generate({
        	bindto: '#timeseriesChart',
        	data: {
        		x: 'x',
                xFormat: '%Y%m%d', // 'xFormat' can be used as custom format of 'x'
                columns: [
                ['x', <?php echo "'".date("Y-m-d",time())."'"; ?>],
                ['x', <?php echo "'".date("Ymd",time())."'"; ?>],
                ['下種', <?php echo getQuantity_Day(date("Y/m/d",time()))[0]['add_quantity']; ?>],
                ['出貨', <?php echo getQuantity_Day(date("Y/m/d",time()))[1]['elda_quantity']; ?>],
                ['耗損', <?php echo getQuantity_Day(date("Y/m/d",time()))[2]['ship_quantity']; ?>]
                ],
                colors: {
                	進貨: '#23b7e5',
                	出貨: '#BABABA',
                	耗損: '#26A69A'
                }
            },
            axis: {
            	x: {
            		type: 'timeseries',
            		tick: {
            			format: '%Y-%m-%d'
            		}
            	}
            }
        });

        // setTimeout(function () {
        // 	chart.load({
        // 		columns: [
        // 		['進貨', 30, 200],
        //         ['出貨', 130, 340],
        //         ['耗損', 400, 500]
        // 		]
        // 	});
        // }, 1000);
        //pie chart
        c3.generate({
        	bindto: '#pieChart',
        	data: {
        		columns: [
        		['已使用', <?php echo $UsedQuantity; ?>],
        		['未使用', <?php echo (getSpace()[0]['onsp_space']-(int)$UsedQuantity); ?>]
        		],
        		colors: {
        			Remains: '#F44336',
        			Used: '#50cdf4'
        		},
        		type: 'pie'
        	},
			  pie: {
			    label: {
			      format: function(value, ratio, id) {
			        return value;
			      }
			    }
			  }
        });
    });

	$('.i-checks').iCheck({
		checkboxClass: 'icheckbox_square-blue',
		radioClass: 'iradio_square-blue'
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

	$('#search_dayreport').click(function() {
		var day = $('#datetimepicker1').val();
		$.ajax({
			url: './index.php',
			type: 'post',
			dataType: 'json',
			data: {op:"search_dayreport", day:day},
			beforeSend: function(msg) {
				$("#ajax_loading").show();
			},
			complete: function(XMLHttpRequest, textStatus) {
				$("#ajax_loading").hide();
			},
			success: function(ret) {
				if(ret.code==1) {
			        var d = ret.data;
			        // console.log(day);
			        // console.log(day.slice(0,4)+day.slice(5,7)+day.slice(8,10));
			        // console.log(d[0].add_quantity);
			        // console.log(d[1].elda_quantity);
			        // console.log(d[2].ship_quantity);
			        var chart = c3.generate({
				    	bindto: '#timeseriesChart',
				    	data: {
				    		x: 'x',
				            xFormat: '%Y%m%d', // 'xFormat' can be used as custom format of 'x'
				            columns: [
				            ['x', day],
				            ['x', day.slice(0,4)+day.slice(5,7)+day.slice(8,10)],
				            ['下種', d[0].add_quantity],
				            ['出貨', d[1].elda_quantity],
				            ['耗損', d[2].ship_quantity]
				            ],
				            colors: {
				            	進貨: '#23b7e5',
				            	出貨: '#BABABA',
				            	耗損: '#26A69A'
				            }
				        },
				        axis: {
				        	x: {
				        		type: 'timeseries',
				        		tick: {
				        			format: '%Y-%m-%d'
				        		}
				        	}
				        }
				    });
				}
			    

			},
			error: function (xhr, ajaxOptions, thrownError) {
			    	// console.log('ajax error');
			    }
			});
	});


});
</script>>
</head>

<body>
	<?php	
	foreach ($user_list as $i=>$v) {
		$row = $i+5;

		$onadd_change_basin = $v['onadd_change_basin'];;
		$date1 = date ("m", $v['onadd_cycle']);
		$date12 = ($date1 - $onadd_change_basin);
		$onadd_part_no = $v['onadd_part_no'];
		$onadd_part_name = $v['onadd_part_name'];
		$onadd_quantity = $v['onadd_quantity'];
		$onadd_planting_date = date ("Y/m/d", $v['onadd_planting_date']);
		if($date12<=0){
			// echo "<script>$(document).ready(function(){ $('#myModal').modal('show'); });</script>";  
		}
	}
	?>
	<?php include('./../htmlModule/nav.php');?>
	<!--main content start-->
	<section class="main-content">



		<!--page header start-->
		<div class="page-header">
			<div class="row">
				<div class="col-sm-6">
					<h4>統計表圖表</h4>
				</div>
			</div>
		</div>

		<!--start page content-->

		<div class="row">
			<div class="col-lg-3 col-md-6 col-sm-12">
				<div class="widget bg-primary padding-0">
					<div class="row row-table">
						<div class="col-xs-4 text-center pv-15 bg-light-dark">
							<em class=" fa-3x">1.7寸</em>
						</div>
						<div class="col-xs-8 pv-15 text-center">
							<?php
							echo "<h2 class='mv-0'>"."<a style='text-decoration:none;color:white;' href='./../purchase/plant_purchase.php?onadd_growing=1'>".$sum17."</a>"."</h2>" ;
							?>
						</div>
					</div>
				</div><!--end widget-->
			</div><!--end col-->
			<div class="col-lg-3 col-md-6 col-sm-12">
				<div class="widget bg-teal padding-0">
					<div class="row row-table">
						<div class="col-xs-4 text-center pv-15 bg-light-dark">
							<em class="fa-3x">2.5寸</em>
						</div>
						<div class="col-xs-8 pv-15 text-center">
							<?php
							echo "<h2 class='mv-0'>"."<a style='text-decoration:none;color:white;' href='./../purchase/plant_purchase.php?onadd_growing=2'>".$sum25."</a>"."</h2>" ;
							?>
						</div>
					</div>
				</div><!--end widget-->
			</div><!--end col-->
			<div class="col-lg-3 col-md-6 col-sm-12">
				<div class="widget bg-success padding-0">
					<div class="row row-table">
						<div class="col-xs-4 text-center pv-15 bg-light-dark">
							<em class="fa-3x">3.5寸</em>
						</div>
						<div class="col-xs-8 pv-15 text-center">
							<?php
							if($sum35==''){
								echo "<h2 class='mv-0'>".'0'."</h2>" ;
							}else{
								echo "<h2 class='mv-0'>"."<a style='text-decoration:none;color:white;' href='./../purchase/plant_purchase.php?onadd_growing=5'>".$sum35."</a>"."</h2>" ;
							}
							?>
						</div>
					</div>
				</div><!--end widget-->
			</div><!--end col-->
			<div class="col-lg-3 col-md-6 col-sm-12">
				<div class="widget bg-indigo padding-0">
					<div class="row row-table">
						<div class="col-xs-4 text-center pv-15 bg-light-dark">
							<em class="fa-3x">其他</em>
						</div>
						<div class="col-xs-8 pv-15 text-center">
							<h2 class="mv-0">0</h2>
						</div>
					</div>
				</div><!--end widget-->
			</div><!--end col-->
		</div>

		<div class="row">
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="row">							
							<div class="col-lg-3 col-md-6 col-sm-12" style="width: 140px; padding-right: 10px;">
								<input type="text" name="fname" placeholder="在此輸入年份" />
							</div>
							<div class="col-lg-3 col-md-6 col-sm-12" style="    padding-left: 0px;    padding-right: 0px;    width: 50px;">
								<button type="submit" class="btn btn-info" op="search">搜尋</button>
							</div>
						</div>
					<div class="panel-heading">
						<div id="quantity_title" style="width: 150px;">出貨報表</div> 
					</div>
					<div class="panel-body">
						<div>
							<div id="stocked"></div>
						</div>
					</div>
				</div>
			</div><!--col-md-12-->
			<div class="col-md-6">
				<div class="panel panel-default">
						<div class="row">
							<div class="col-lg-3 col-md-6 col-sm-12" style="width: 140px;padding-right: 0px;">
								<input type="text" name="search_dayreport_start" id="datetimepicker1"  placeholder="輸入日期" />
							</div>

							<div class="col-lg-3 col-md-6 col-sm-12" style="    padding-left: 10px;    padding-right: 0px;    width: 50px;">
								<button id="search_dayreport" class="btn btn-info" >搜尋</button>
							</div>
						</div>
					<div class="panel-heading">
						日報表
					</div>
					<div class="panel-body">
						<div>
							<div id="timeseriesChart"></div>
						</div>

					</div>
				</div>
			</div><!--col-->
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						預留區
					</div>
					<div class="panel-body">
						<div class="scrollDiv">
							<ul class="sidebar-list projects-list">                           

							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						空間統計 <small class="text-muted">剩餘存放空間</small>
					</div>
					<div class="panel-body">
						<div>
							<div id="pieChart"></div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class='modal fade' id='myModal' role='dialog'>
			<div class='modal-dialog modal-lg'>
				<div class='modal-content'>
					<div class='modal-body'>
						<h4 class="modal-title">提醒事項</h4>
						<label>品號：</label>
						<?php
						echo "<label>".$onadd_part_no."</label>" ;
						?>
					</br>
					<label>品名：</label>
					<?php
					echo "<label>".$onadd_part_name."</label>" ;
					?>
				</br>
				<label>下種日：</label>
				<?php
				echo "<label>".$onadd_planting_date."</label>" ;
				?>
			</br>
			<label>數量：</label>
			<?php
			echo "<label>".$onadd_quantity."</label>" ;
			?>
		</br>
		<label>提醒事項：</label>
		<?php
		echo "<label>".'已經超過換盆日期'."</label>" ;
		?>
	</div>
	<div class='modal-footer'>
		<a href='index.php'>
			<button type='button' class='btn btn-default' data-dismiss='modal'>確認</button>
		</a>
	</div>
</div>
</div>
</div>
<div class="row">
	<div class="col-md-6">

	</div><!--end row-->

	<!--end page content-->


	<!--Start footer-->
	<footer class="footer">

	<?php
		// printr(getQuantity_Day("2018-04-01"));

	?> 


		<span>Copyright &copy; 2019. Online Plant</span>
	</footer>
	<!--end footer-->

</section>
<!--end main content-->

<!--Common plugins-->
<!-- <script src="./../../js1/jquery.min.js"></script> -->
<!-- <script src="./../../js1/bootstrap.min.js"></script> -->

</body>
</html>?>