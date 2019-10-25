<?php
include_once("./func_plant_purchase.php");
// printr(getWorkListByMonth());
// exit;
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

$size_mapping = array(
	1=>'<font color="#666666">1.7寸</font>',
	2=>'<font color="#666666">2.5寸</font>',
	3=>'<font color="#666666">2.8寸</font>',
	4=>'<font color="#666666">3.0寸</font>',
	5=>'<font color="#666666">3.5寸</font>',
	6=>'<font color="#666666">3.6寸</font>',
	7=>'<font color="#666666">其他</font>' 
);
// printr(getTotalQty());
// exit;

	// page
$pg_page = GetParam('pg_page', 1);
$pg_rows = 20;
$pg_total = GetParam('pg_total')=='' ? getUserQty($search_where) : GetParam('pg_total');
$pg_offset = $pg_rows * ($pg_page - 1);
$pg_pages = $pg_rows == 0 ? 0 : ( (int)(($pg_total + ($pg_rows - 1)) /$pg_rows) );

$product_list = getWorkListByMonth();
$week_list = getQuantity_Day(date("Y/m/d",time()));
$TotalQty = getTotalQty();
// printr($TotalQty);
// exit();
$sum17 = getDetails('1');//計算1.7
$sum25 = getDetails('2');//計算2.5
$sum28 = getDetails('3');//計算2.8
$sum30 = getDetails('4');//計算3.0
$sum35 = getDetails('5');//計算3.5
$sum36 = getDetails('6');//計算3.6
$sum37 = getDetails('7');//計算其他
$sum38 = getDetails('8');//計算瓶苗下種
$others = $sum28+$sum30+$sum36+$sum37+$sum38;
// printr($others);
// exit();

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
			case 'get_expected_data':
				// $ret_msg = "test";
				$ret_code = 1;
				$ret_data = getWorkListByMonth();
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
	<script src="./../../lib/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js" charset="UTF-8"></script>
    <script src="./../../lib/bootstrap-datetimepicker/bootstrap-datetimepicker.zh-TW.js" charset="UTF-8"></script>
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
			var count = 0;
			var flag = 1;
			$(function () {
				$.ajax({
				url: './index.php',
				type: 'post',
				dataType: 'json',
				data: {op:"get_expected_data"},
				beforeSend: function(msg) {
					$("#ajax_loading").show();
				},
				complete: function(XMLHttpRequest, textStatus) {
					$("#ajax_loading").hide();
				},
				success: function(ret) {					
					if(ret.code==1) {
				        var data = ret.data;	
						console.log(ret.data);
						var interval = setInterval(function write_numbers() {
						  if (count < ret.data.length) {
						  	$('#onadd_part_no').html(ret.data[count]['onadd_part_no']);
						  	$('#onadd_part_name').html(ret.data[count]['onadd_part_name']);
						  	$('#onadd_planting_date').html(ret.data[count]['onadd_planting_date']);
						  	$('#onadd_expected_date').html(ret.data[count]['expected_date']);
						  	$('#onadd_quantity').html(ret.data[count]['onadd_quantity']);
						  	// console.log('今日'+ret.data[count]['onadd_planting_date_unix']+'預計成長日'+ret.data[count]['expected_date_unix']);
						  	if(ret.data[count]['onadd_planting_date_unix'] > ret.data[count]['expected_date_unix']){
							  	$('#onadd_content').html("已經超過換盆日期");
							}
							else{
								$('#onadd_content').html("即將到達換盆日期");
							}
							if(flag == 1){
								if(!$('#myModal').hasClass('in')){
									$('#myModal').modal('show');
									flag = 0;
								}						    	
						    	
							}
						 //    console.log(ret.data);
							console.log(count);	
						  } else {
						  	$('#myModal').modal('toogle');
						    clearInterval(interval);							
						  }
						}, 2500)
					}
				},
				error: function (xhr, ajaxOptions, thrownError) {	
				    	// console.log('ajax error');
				    }
				});

		$("#btn_modal").click(function() {
			if($('#myModal').hasClass('in')){
				$('#myModal').modal('hide');
			}				
			count++;
			flag = 1;
		});

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
     //            	['x','2019-10-24','2019-10-23'],
     //            	['x','20191024','20191023'],
     //            	['下種','0','10']
     //            	,['出貨','0','20']
     //            	,['耗損','50','30']                	
 				// ],

                <?php 
                	for($i=0;$i<count($week_list);$i++){
                		if($i<count($week_list)-1){
                			$str1 .= "'".$week_list[$i]['date1']."',";
                			$str2 .= "'".$week_list[$i]['date2']."',";
                			$str3 .= "'".$week_list[$i][0]."',";
                			$str4 .= "'".$week_list[$i][2]."',";
                			$str5 .= "'".$week_list[$i][1]."',";
	                	}
                		else{
                			$str1 .= "'".$week_list[$i]['date1']."'";
                			$str2 .= "'".$week_list[$i]['date2']."'";
                			$str3 .= "'".$week_list[$i][0]."'";
                			$str4 .= "'".$week_list[$i][2]."'";
                			$str5 .= "'".$week_list[$i][1]."'";
                		}
                	}
                	echo "['x',".$str1."],";
	                echo "['x',".$str2."],";
	                echo "['下種',".$str3."],";
	                echo "['出貨',".$str4."],";
	                echo "['耗損',".$str5."],";
                ?>
                
                // ['x', <?php echo "'".date("Y-m-d",time())."'"; ?>],
                // ['x', <?php echo "'".date("Ymd",time())."'"; ?>],
                // ['下種', <?php echo getQuantity_Day(date("Y/m/d",time()))[0]['add_quantity']; ?>],
                // ['出貨', <?php echo getQuantity_Day(date("Y/m/d",time()))[1]['elda_quantity']; ?>],
                // ['耗損', <?php echo getQuantity_Day(date("Y/m/d",time()))[2]['ship_quantity']; ?>]
                // ],
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
			        var str1="";
			        var str2="";
					var str3="";
					var str4="";
					var str5="";
			        console.log(d);
			        for(var i=0;i<d.length;i++){
			        	if(i<d.length-1){
							str1 += "\'"+d[i]['date1']+"\',";
							str2 += "\'"+d[i]['date2']+"\',";
							str3 += "\'"+d[i][0]+"\',";
							str4 += "\'"+d[i][2]+"\',";
							str5 += "\'"+d[i][1]+"\',";
						}
						else{
							str1 += "\'"+d[i]['date1']+"\'";
							str2 += "\'"+d[i]['date2']+"\'";
							str3 += "\'"+d[i][0]+"\'";
							str4 += "\'"+d[i][2]+"\'";
							str5 += "\'"+d[i][1]+"\'";
						}
			        }
			        // str2 = str2.substring(1,str2.length-1);
			        console.log(str1);
			        console.log(str2);
			        console.log(str3);
			        console.log(str4);
			        console.log(str5);

			        var chart = c3.generate({
				    	bindto: '#timeseriesChart',
				    	data: {
				    		x: 'x',
				            xFormat: '%Y%m%d', // 'xFormat' can be used as custom format of 'x'
				            columns: [
				            ['x', str1],
				            ['x', str2],
				            ['下種',str3],
				            ['出貨',str4],
				            ['耗損',str5]
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
							<em class=" fa-2x">1.7寸</em>
						</div>
						<div class="col-xs-8 pv-15 text-center">
							<?php
							if(empty($sum17))
								echo "<h2 class='mv-0'>".'0'."</h2>" ;
							else
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
							<em class="fa-2x">2.5寸</em>
						</div>
						<div class="col-xs-8 pv-15 text-center">
							<?php
							if(empty($sum25))
								echo "<h2 class='mv-0'>".'0'."</h2>" ;
							else
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
							<em class="fa-2x">3.5寸</em>
						</div>
						<div class="col-xs-8 pv-15 text-center">
							<?php
							if(empty($sum35)){
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
							<em class="fa-2x">其他</em>
						</div>
						<div class="col-xs-8 pv-15 text-center">
							<h2 class="mv-0">
								<?php 
								// if(empty($sum35))
								// 	echo "<h2 class='mv-0'>0</h2>" ;								
								// else
									echo "<h2 class='mv-0'>".$others."</h2>" ;
								?>
									
								</h2>
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
						周報表
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
								<table class="table">
								  <thead>
								    <tr>
								      <th scope="col">位置</th>
								      <th scope="col">尺寸</th>
								      <th scope="col">目前數量</th>
								    </tr>
								  </thead>
								  <tbody>
								  	<?php 
								  		for($i=0;$i<count($TotalQty);$i++){
								  			for($j=1;$j<=6;$j++){
								  				if($j==1){
										  			echo "<tr>";
										  				echo "<td>".$TotalQty[$i]['location']."</td>";
										  				echo "<td>".$size_mapping[$j]."</td>";
										  				echo "<td><a href=\"".WT_SERVER."/admin/purchase/plant_purchase.php?onadd_part_no=&onadd_part_name=&onadd_location=".$TotalQty[$i]['location']."\">".$TotalQty[$i][$j]."</a></td>";
										  			echo "</tr>";
										  		}
										  		else{
										  			echo "<tr>";
										  				echo "<td></td>";
										  				echo "<td>".$size_mapping[$j]."</td>";
										  				echo "<td><a href=\"".WT_SERVER."/admin/purchase/plant_purchase.php?onadd_part_no=&onadd_part_name=&onadd_location=".$TotalQty[$i]['location']."\">".$TotalQty[$i][$j]."</a></td>";
										  			echo "</tr>";
										  		}
									  		}
								  		}

								  	?>								    
								  </tbody>
								</table>
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

		<div class='modal fade ' id='myModal' role='dialog'>
			<div class='modal-dialog modal-'>
				<div class='modal-content'>
					<div class='modal-body'>
						<div class="panel panel-info">
					    	<div class="panel-heading">
					    		<h4 class="modal-title">提醒事項</h4>
					    	</div>
					    	<div class="panel-body" style="font-size: 1.4rem">					    	
					    		<label>品號：</label><label id="onadd_part_no"></label>
								</br>
								<label>品名：</label><label id="onadd_part_name"></label>
								</br>
								<label>下種日：</label><label id="onadd_planting_date"></label>
								</br>
								<label>預計成長日：</label><label id="onadd_expected_date"></label>
								</br>
								<label>數量：</label><label id="onadd_quantity"></label>
								</br>
								<label>提醒事項：</label><label id="onadd_content"></label>
					    	</div>
					    </div>
					</div>
					<div class='modal-footer'>
						<button type='button' class='btn btn-default' id="btn_modal">確認</button>
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