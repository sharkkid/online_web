<?php
include_once(dirname(__FILE__).'/../config.php');
$onchba_cycle = '0';
function dateFormat($ctime, $format='Y-m-d H:i:s') {
	$now = time();
	if($now > $ctime) {
		return '<span style="color: red">' . date($format, $ctime) . '<span>';
	} else {
		return date($format, $ctime);
	}
}

//===============================
//plant_re_schedule.php
//===============================
function getUser_re($where='', $offset=30, $rows=0) {
	$ret_data = array();
	$conn = getDB();
	if(empty($where))
		$sql="select * from onliine_add_data where onadd_status>=0 and onadd_schedule=1 order by onadd_planting_date,onadd_growing, onadd_sn desc limit $offset, $rows";
	else
		$sql="select * from onliine_add_data where onadd_status>=0 and onadd_schedule=1 and ( $where ) order by onadd_planting_date,onadd_growing, onadd_sn desc limit $offset, $rows";

	$qresult = $conn->query($sql);
	if ($qresult->num_rows > 0) {
		while($row = $qresult->fetch_assoc()) {
			$ret_data[] = $row;
		}
		$qresult->free();
	}
	$conn->close();
	return $ret_data;
}

function getProductFirstQty($onadd_sn) {
	$ret_data = 0;
	$conn = getDB();	
	$sql="select onfp_plant_amount from onliine_firstplant_data where onfp_status>=1 and onadd_sn like '$onadd_sn'";
	// echo $sql;
	$qresult = $conn->query($sql);
	if ($qresult->num_rows > 0) {
		while($row = $qresult->fetch_assoc()) {
			$ret_data = $row['onfp_plant_amount'];
		}
		$qresult->free();
	}
	else{
		$ret_data = 1;
	}
	$conn->close();
	return $ret_data;
}

function getUserQty_re($where='') {
	$ret_data = 0;
	$conn = getDB();
	if(empty($where))
		$sql="select count(*) from onliine_add_data where onadd_status>=0 and onadd_schedule=1";
	else
		$sql="select count(*) from onliine_add_data where onadd_status>=0 and onadd_schedule=1 and ( $where )";

	$qresult = $conn->query($sql);
	if ($qresult->num_rows > 0) {
		while($row = $qresult->fetch_assoc()) {
			$ret_data = $row['count(*)'];
		}
		$qresult->free();
	}
	$conn->close();
	return $ret_data;
}

//================================
// onliine_add_data.php
//================================
function getUser($where='', $offset=30, $rows=0) {
	$ret_data = array();
	$conn = getDB();
	if(empty($where))
		$sql="select * from onliine_add_data where onadd_status>=0 and onadd_schedule!=1 order by onadd_planting_date,onadd_growing, onadd_sn desc limit $offset, $rows";
	else
		$sql="select * from onliine_add_data where onadd_status>=0 and onadd_schedule!=1 and ( $where ) order by onadd_planting_date,onadd_growing, onadd_sn desc limit $offset, $rows";

	$qresult = $conn->query($sql);
	if ($qresult->num_rows > 0) {
		while($row = $qresult->fetch_assoc()) {
			$ret_data[] = $row;
		}
		$qresult->free();
	}
	$conn->close();
	return $ret_data;
}

function getWorkListByMonth($where='', $offset=30, $rows=0) {
	$list_setting1 = getSettingBySn('1.7');
	$list_setting2 = getSettingBySn('2.5');
	$list_setting3 = getSettingBySn('2.8');
	$list_setting4 = getSettingBySn('3.0');
	$list_setting5 = getSettingBySn('3.5');	
	$list_setting6 = getSettingBySn('3.6');
	$list_setting7 = getSettingBySn('其他');
	$list_setting8 = getSettingBySn('瓶苗下種');
	$ret_data = array();
	$conn = getDB();
	if(empty($where))
		$sql="select * from onliine_add_data where onadd_status>=0 and onadd_schedule!=1";
	else
		$sql="select * from onliine_add_data where onadd_status>=0 and onadd_schedule!=1 and ( $where )";
	
	$qresult = $conn->query($sql);
	if ($qresult->num_rows > 0) {
		while($row = $qresult->fetch_assoc()) {
			switch ($row['onadd_growing']) {
        		case '1':
        			$GLOBALS['onchba_cycle'] = $list_setting1['onchba_cycle'];
        			break;
        		case '2':
        			$GLOBALS['onchba_cycle'] = $list_setting2['onchba_cycle'];
        			break;
        		case '3':
        			$GLOBALS['onchba_cycle'] = $list_setting3['onchba_cycle'];
        			break;
        		case '4':
        			$GLOBALS['onchba_cycle'] = $list_setting4['onchba_cycle'];
        			break;
        		case '5':        			
        			$GLOBALS['onchba_cycle'] = $list_setting5['onchba_cycle'];
        			break;
        		case '6':
        			$GLOBALS['onchba_cycle'] = $list_setting6['onchba_cycle'];
        			break;
        		case '7':
        			$GLOBALS['onchba_cycle'] = $list_setting7['onchba_cycle'];
        			break;
        		case '8':
        			$GLOBALS['onchba_cycle'] = $list_setting8['onchba_cycle'];
        			break;
        	}
        	$row['onchba_cycle'] = $GLOBALS['onchba_cycle'];
        	$test = date("Y/m/d", strtotime("+".$GLOBALS['onchba_cycle']." days", $row['onadd_planting_date']));
        	$o_y = date('Y',strtotime($test));        	
        	$c_y = date('Y');
        	$o_m = date('m',strtotime($test));
        	$c_m = date('m');
        	$row['o_y'] = $o_y;
        	$row['c_y'] = $c_y;
        	$row['o_m'] = $o_m;
        	$row['c_m'] = $c_m;
        	if($o_y <= $c_y){
        		if($o_y == $c_y && $o_m <= $c_m){
					$row['onadd_planting_date'] = date('Y/m/d',$row['onadd_planting_date']);        		
        			$row['expected_date'] = date('Y/m/d',strtotime($test));
					$ret_data[] = $row;
        		}
        		else if($o_y < $c_y){
					$row['onadd_planting_date'] = date('Y/m/d',$row['onadd_planting_date']);        		
        			$row['expected_date'] = date('Y/m/d',strtotime($test));
					$ret_data[] = $row;
        		}        		
        	}
        	// $ret_data[] = $row;
		}
		$qresult->free();
	}
	$conn->close();
	return $ret_data;
}

function getUseradd($where='', $offset=30, $rows=0) {
	$ret_data = array();
	$conn = getDB();
	if(empty($where))
		$sql="select * from onliine_add_data where onadd_status>=0 GROUP BY onadd_part_no";
	else
		$sql="select * from onliine_add_data where onadd_status>=0 GROUP BY onadd_part_no";

	$qresult = $conn->query($sql);
	if ($qresult->num_rows > 0) {
		while($row = $qresult->fetch_assoc()) {
			$ret_data[] = $row;
		}
		$qresult->free();
	}
	$conn->close();
	return $ret_data;
}

function getUserQty($where='') {
	$list_setting1 = getSettingBySn('1.7');
	$list_setting2 = getSettingBySn('2.5');
	$list_setting3 = getSettingBySn('2.8');
	$list_setting4 = getSettingBySn('3.5');
	$list_setting5 = getSettingBySn('3.0');
	$list_setting6 = getSettingBySn('3.5');
	$list_setting7 = getSettingBySn('3.6');
	$list_setting7 = getSettingBySn('瓶苗下種');
	$ret_data = 0;
	$conn = getDB();
	if(empty($where))
		$sql="select * from onliine_add_data where onadd_status>=0 and onadd_schedule!=1";
	else
		$sql="select * from onliine_add_data where onadd_status>=0 and onadd_schedule!=1 and ( $where )";
	// echo $sql;
	$qresult = $conn->query($sql);
	if ($qresult->num_rows > 0) {
		while($row = $qresult->fetch_assoc()) {
			switch ($row['onadd_growing']) {
        		case 1:
        			$onchba_cycle = $list_setting1['onchba_cycle'];
        			break;
        		case 2:
        			$onchba_cycle = $list_setting2['onchba_cycle'];
        			break;
        		case 3:
        			$onchba_cycle = $list_setting5['onchba_cycle'];
        			break;
        		case 4:
        			$onchba_cycle = $list_setting1['onchba_cycle'];
        			break;
        		case 5:
        			$onchba_cycle = $list_setting2['onchba_cycle'];
        			break;
        		case 6:
        			$onchba_cycle = $list_setting5['onchba_cycle'];
        			break;
        		case 7:
        			$onchba_cycle = $list_setting5['onchba_cycle'];
        			break;
        		case 8:
        			$onchba_cycle = $list_setting5['onchba_cycle'];
        			break;
        	}
        	// echo $onchba_cycle.",";
        	if($row['onadd_plant_st']==2){
        		$test = date("Y/m/d", strtotime("+".$onchba_cycle." days", $row['onadd_planting_date']));
        	}else{
        		$test = date("Y/m/d", strtotime("+$onchba_cycle days", $row['onadd_planting_date']));
        	}
        	$o_y = date('Y',strtotime($test));        	
        	$c_y = date('Y');
        	$o_m = date('m',strtotime($test));
        	$c_m = date('m');
        	$row['o_y'] = $o_y;
        	$row['c_y'] = $c_y;
        	$row['o_m'] = $o_m;
        	$row['c_m'] = $c_m;
        	if($o_y <= $c_y && $o_m <= $c_m){
        		$ret_data++;
			}
		}
		$qresult->free();
	}
	$conn->close();
	return $ret_data;
}
function getUserQtyadd($where='') {
	$ret_data = 0;
	$conn = getDB();
	if(empty($where))
		$sql="select count(*) from onliine_add_data where onadd_status>=0 GROUP BY onadd_part_no";
	else
		$sql="select count(*) from onliine_add_data where onadd_status>=0 GROUP BY onadd_part_no";

	$qresult = $conn->query($sql);
	if ($qresult->num_rows > 0) {
		while($row = $qresult->fetch_assoc()) {
			$ret_data = $row['count(*)'];
		}
		$qresult->free();
	}
	$conn->close();
	return $ret_data;
}

function getUserBySn($onadd_sn) {
	$ret_data = array();
	$conn = getDB();
	$sql="select * from onliine_add_data where onadd_sn='{$onadd_sn}'";

	$qresult = $conn->query($sql);
	if ($qresult->num_rows > 0) {
		if ($row = $qresult->fetch_assoc()) {
			$ret_data = $row;
		}
		$qresult->free();
	}
	$conn->close();
	return $ret_data;
}
function getSettingBySn($onchba_size) {
	$ret_data = array();
	$conn = getDB();
	$sql="select * from online_change_basin where onchba_size like '{$onchba_size}'";
	// echo $sql;
	$qresult = $conn->query($sql);
	if ($qresult->num_rows > 0) {
		if ($row = $qresult->fetch_assoc()) {
			$ret_data = $row;
		}
		$qresult->free();
	}
	$conn->close();
	return $ret_data;
}
function getUserByAccount($account) {
	$ret_data = array();
	$conn = getDB();
	$sql="select * from onliine_add_data where onadd_part_no='{$account}'";

	$qresult = $conn->query($sql);
	if ($qresult->num_rows > 0) {
		if ($row = $qresult->fetch_assoc()) {
			$ret_data = $row;
		}
		$qresult->free();
	}
	$conn->close();
	return $ret_data;
}

function getsetting() {
	$ret_data = array();
	$conn = getDB();
		$sql="select * from online_change_basin where onchba_status>=0";

	$qresult = $conn->query($sql);
	if ($qresult->num_rows > 0) {
		while($row = $qresult->fetch_assoc()) {
			$ret_data[] = $row;
		}
		$qresult->free();
	}
	$conn->close();
	return $ret_data;
}

//================================
// onliine_details.php
//================================
// SELECT onadd_part_no, SUM(onadd_quantity) FROM onliine_add_data WHERE onadd_part_no='PP-0052' AND onadd_growing='1' GROUP BY onadd_quantity_shi
function getDetails($onadd_part_no,$onadd_growing,$onadd_quantity_del) {
	$ret_data = array();
	$conn = getDB();
	if(empty($where))
		$sql="select * , SUM(onadd_quantity) from onliine_add_data where onadd_part_no='$onadd_part_no' AND onadd_growing='$onadd_growing' AND onadd_quantity_del='$onadd_quantity_del' GROUP BY onadd_quantity_shi";
	else
		$sql="select * , SUM(onadd_quantity) from onliine_add_data where onadd_part_no='$onadd_part_no' AND onadd_growing='$onadd_growing' AND onadd_quantity_del='$onadd_quantity_del' GROUP BY onadd_quantity_shi";

	$qresult = $conn->query($sql);
	if ($qresult->num_rows > 0) {
		while($row = $qresult->fetch_assoc()) {
			$ret_data[] = $row;
		}
		$qresult->free();
	}
	$conn->close();
	return $ret_data;
}

function getDetailsQty($where='') {
	$ret_data = 0;
	$conn = getDB();
	if(empty($where))
		$sql="select count(*) from onliine_add_data where onadd_status>=0";
	else
		$sql="select count(*) from onliine_add_data where onadd_status>=0 and ( $where )";

	$qresult = $conn->query($sql);
	if ($qresult->num_rows > 0) {
		while($row = $qresult->fetch_assoc()) {
			$ret_data = $row['count(*)'];
		}
		$qresult->free();
	}
	$conn->close();
	return $ret_data;
}

function getDetailsBySn($onadd_sn) {
	$ret_data = array();
	$conn = getDB();
	$sql="select * from onliine_add_data where onadd_sn='{$onadd_sn}'";

	$qresult = $conn->query($sql);
	if ($qresult->num_rows > 0) {
		if ($row = $qresult->fetch_assoc()) {
			$ret_data = $row;
		}
		$qresult->free();
	}
	$conn->close();
	return $ret_data;
}
function getDetailsByAccount($account) {
	$ret_data = array();
	$conn = getDB();
	$sql="select * from onliine_add_data where onadd_part_no='{$account}'";

	$qresult = $conn->query($sql);
	if ($qresult->num_rows > 0) {
		if ($row = $qresult->fetch_assoc()) {
			$ret_data = $row;
		}
		$qresult->free();
	}
	$conn->close();
	return $ret_data;
}
//================================

//================================
// sys_history.php
//================================
function getHistory($where='', $offset=30, $rows=0) {
	$ret_data = array();
	$conn = getDB();
	if(empty($where))
		$sql="select * from js_history a left join device_manage b on a.jshist_user=b.dema_sn order by jshist_add_date desc, jshist_sn desc limit $offset, $rows";
	else
		$sql="select * from js_history a left join device_manage b on a.jshist_user=b.dema_sn where 1=1 and ( $where ) order by jshist_add_date desc, jshist_sn desc limit $offset, $rows";

	$qresult = $conn->query($sql);
	if ($qresult->num_rows > 0) {
		while($row = $qresult->fetch_assoc()) {
			$ret_data[] = $row;
		}
		$qresult->free();
	}
	$conn->close();
	return $ret_data;
}

function getHistoryQty($where='') {
	$ret_data = 0;
	$conn = getDB();
	if(empty($where))
		$sql="select count(*) from js_history a left join device_manage b on a.jshist_user=b.dema_sn";
	else
		$sql="select count(*) from js_history a left join device_manage b on a.jshist_user=b.dema_sn where 1=1 and ( $where )";

	$qresult = $conn->query($sql);
	if ($qresult->num_rows > 0) {
		while($row = $qresult->fetch_assoc()) {
			$ret_data = $row['count(*)'];
		}
		$qresult->free();
	}
	$conn->close();
	return $ret_data;
}

//================================
// sys_history_online.php
//================================
function getHistoryOnline($where='', $offset=30, $rows=0) {
	$ret_data = array();
	$conn = getDB();
	if(empty($where))
		$sql="select a.dema_sn, dema_device_name, sum(jsol_count) as count from js_online a left join device_manage b on a.dema_sn=b.dema_sn group by a.dema_sn order by count desc limit $offset, $rows";
	else
		$sql="select a.dema_sn, dema_device_name, sum(jsol_count) as count from js_online a left join device_manage b on a.dema_sn=b.dema_sn where 1=1 and ( $where ) group by a.dema_sn order by count desc limit $offset, $rows";

	$qresult = $conn->query($sql);
	if ($qresult->num_rows > 0) {
		while($row = $qresult->fetch_assoc()) {
			$ret_data[] = $row;
		}
		$qresult->free();
	}
	$conn->close();
	return $ret_data;
}

function getHistoryOnlineQty($where='') {
	$ret_data = 0;
	$conn = getDB();
	if(empty($where))
		$sql="select a.dema_sn, dema_device_name, sum(jsol_count) as count from js_online a left join device_manage b on a.dema_sn=b.dema_sn group by a.dema_sn";
	else
		$sql="select a.dema_sn, dema_device_name, sum(jsol_count) as count from js_online a left join device_manage b on a.dema_sn=b.dema_sn where 1=1 and ( $where ) group by a.dema_sn";

	$qresult = $conn->query($sql);
	if ($qresult->num_rows > 0) {
		$ret_data = $qresult->num_rows;
		$qresult->free();
	}
	$conn->close();
	return $ret_data;
}

//================================
// sys_history_edit.php
//================================
function getHistoryEdit($where='', $offset=30, $rows=0) {
	$ret_data = array();
	$conn = getDB();
	if(empty($where))
		$sql="select dema_sn, dema_device_name, count(*) as count from js_history a left join device_manage b on a.jshist_user=b.dema_sn where jshist_op_type=2 group by dema_sn order by count desc limit $offset, $rows";
	else
		$sql="select dema_sn, dema_device_name, count(*) as count from js_history a left join device_manage b on a.jshist_user=b.dema_sn where jshist_op_type=2 and ( $where ) group by dema_sn order by count desc limit $offset, $rows";

	$qresult = $conn->query($sql);
	if ($qresult->num_rows > 0) {
		while($row = $qresult->fetch_assoc()) {
			$ret_data[] = $row;
		}
		$qresult->free();
	}
	$conn->close();
	return $ret_data;
}

function getHistoryEditQty($where='') {
	$ret_data = 0;
	$conn = getDB();
	if(empty($where))
		$sql="select dema_sn, dema_device_name, count(*) as count from js_history a left join device_manage b on a.jshist_user=b.dema_sn where jshist_op_type=2 group by dema_sn order by dema_sn";
	else
		$sql="select dema_sn, dema_device_name, count(*) as count from js_history a left join device_manage b on a.jshist_user=b.dema_sn where jshist_op_type=2 and ( $where ) group by dema_sn order by dema_sn";

	$qresult = $conn->query($sql);
	if ($qresult->num_rows > 0) {
		$ret_data = $qresult->num_rows;
		$qresult->free();
	}
	$conn->close();
	return $ret_data;
}
?>