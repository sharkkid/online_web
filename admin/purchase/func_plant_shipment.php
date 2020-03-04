<?php
include_once(dirname(__FILE__).'/../config.php');

function dateFormat($ctime, $format='Y-m-d H:i:s') {
	$now = time();
	if($now > $ctime) {
		return '<span style="color: red">' . date($format, $ctime) . '<span>';
	} else {
		return date($format, $ctime);
	}
}

//================================
// online_shipment_data.php
//================================
function getUser($where='', $offset=30, $rows=0) {
	$ret_data = array();
	$conn = getDB();
	if(empty($where))
	 	$sql="select * from online_shipment_data a where onshda_status>=0 order by onshda_add_date desc, onshda_sn desc limit $offset, $rows";
	else
	 	$sql="select * from online_shipment_data a where onshda_status>=0 and ( $where ) order by onshda_add_date desc, onshda_sn desc limit $offset, $rows";

	$qresult = $conn->query($sql);
	if ($qresult->num_rows > 0) {
	 	while($row = $qresult->fetch_assoc()) {
	  		$sql_getdata = "select distinct onproduct_isbought from onliine_product_data where onproduct_status>=0 and onproduct_part_no like '".$row['onadd_part_no']."'";
	  		$qresult2 = $conn->query($sql_getdata);
	  
	  		$row['onadd_plant_st'] = $qresult2->fetch_assoc()['onproduct_isbought'];
	  		$ret_data[] = $row;
	 	}
	}
	$conn->close();
	return $ret_data;
}

function getUser_forExcel($where='') {
	$ret_data = array();
	$conn = getDB();
	if(empty($where))
		$sql="SELECT a.onadd_sn,a.onadd_part_no,a.onadd_part_name,a.onshda_add_date,a.onshda_quantity,a.onshda_price,a.onshda_client,b.onadd_cur_size,b.onadd_cost_month,SUM(c.oncoda_cost) as oncoda_cost,floor((UNIX_TIMESTAMP()-b.onadd_planting_date)/60/60/24/30) as date_month 
			FROM `online_shipment_data` a 
			left join `onliine_add_data` b on a.onadd_sn = b.onadd_sn
			left join `online_cost_data` c on b.onadd_cur_size = c.oncoda_cost_size 
			where c.oncoda_cost_status = 0 and a.onshda_status = 1 GROUP by a.onadd_sn desc";
	else
		$sql="SELECT a.onadd_sn,a.onadd_part_no,a.onadd_part_name,a.onshda_add_date,a.onshda_quantity,a.onshda_price,a.onshda_client,b.onadd_cur_size,b.onadd_cost_month,SUM(c.oncoda_cost) as oncoda_cost,floor((UNIX_TIMESTAMP()-b.onadd_planting_date)/60/60/24/30) as date_month 
			FROM `online_shipment_data` a 
			left join `onliine_add_data` b on a.onadd_sn = b.onadd_sn
			left join `online_cost_data` c on b.onadd_cur_size = c.oncoda_cost_size 
			where c.oncoda_cost_status = 0 and a.onshda_status = 1 and ( $where ) GROUP by a.onadd_sn desc";

	$qresult = $conn->query($sql);
	if ($qresult->num_rows > 0) {
		while($row = $qresult->fetch_assoc()) {
			$sql_getdata = "select distinct onproduct_isbought from onliine_product_data where onproduct_status>=0 and onproduct_part_no like '".$row['onadd_part_no']."'";
			$qresult2 = $conn->query($sql_getdata);
			$row['onadd_plant_st'] = $qresult2->fetch_assoc()['onproduct_isbought'];

			$sql_onadd_cost_month = "SELECT c.oncoda_cost as onadd_cost_month FROM `online_shipment_data` a left join `onliine_add_data` b on a.onadd_sn = b.onadd_sn left join `online_cost_data` c on b.onadd_cur_size = c.oncoda_cost_size where c.oncoda_cost_status = 1 and a.onshda_status = 1 GROUP by a.onadd_sn desc";
			$qresult3 = $conn->query($sql_onadd_cost_month);	
			$row['onadd_cost_month'] = $qresult3->fetch_assoc()['onadd_cost_month'];
			$ret_data[] = $row;
		}
	}	

	if(empty($where))
		$sql="SELECT a.onadd_sn,a.onadd_part_no,a.onadd_part_name,a.onshda_add_date,a.onshda_quantity,a.onshda_price,a.onshda_client,b.onadd_cur_size,b.onadd_cost_month,SUM(c.oncoda_cost) as oncoda_cost,floor((UNIX_TIMESTAMP()-b.onadd_planting_date)/60/60/24/30)+1 as date_month 
			FROM `online_shipment_data` a 
			left join `onliine_add_data` b on a.onadd_sn = b.onadd_newpot_sn
			left join `online_cost_data` c on b.onadd_cur_size = c.oncoda_cost_size 
			where c.oncoda_cost_status = 0 and a.onshda_status = 1 GROUP by a.onadd_sn desc";
	else
		$sql="SELECT a.onadd_sn,a.onadd_part_no,a.onadd_part_name,a.onshda_add_date,a.onshda_quantity,a.onshda_price,a.onshda_client,b.onadd_cur_size,b.onadd_cost_month,SUM(c.oncoda_cost) as oncoda_cost,floor((UNIX_TIMESTAMP()-b.onadd_planting_date)/60/60/24/30)+1 as date_month 
			FROM `online_shipment_data` a 
			left join `onliine_add_data` b on a.onadd_sn = b.onadd_newpot_sn
			left join `online_cost_data` c on b.onadd_cur_size = c.oncoda_cost_size 
			where c.oncoda_cost_status = 0 and a.onshda_status = 1 and ( $where ) GROUP by a.onadd_sn desc";

	$qresult = $conn->query($sql);
	if ($qresult->num_rows > 0) {
		while($row = $qresult->fetch_assoc()) {
			$sql_getdata = "select distinct onproduct_isbought from onliine_product_data where onproduct_status>=0 and onproduct_part_no like '".$row['onadd_part_no']."'";
			$sql_onadd_cost_month = "SELECT c.oncoda_cost as onadd_cost_month FROM `online_shipment_data` a left join `onliine_add_data` b on a.onadd_sn = b.onadd_newpot_sn left join `online_cost_data` c on b.onadd_cur_size = c.oncoda_cost_size where c.oncoda_cost_status = 1 and a.onshda_status = 1 GROUP by a.onadd_sn desc";
			$qresult2 = $conn->query($sql_getdata);			
			$row['onadd_plant_st'] = $qresult2->fetch_assoc()['onproduct_isbought'];
			$qresult3 = $conn->query($sql_onadd_cost_month);
			$row['onadd_cost_month'] = $qresult3->fetch_assoc()['onadd_cost_month'];
			$ret_data2[] = $row;
		}
	}
	!isset($ret_data) ? $ret_data = array() : none;
	!isset($ret_data2) ? $ret_data2 = array() : none;
	$data = array_merge($ret_data, $ret_data2);
	// printr($where);
	// printr($sql);
	// printr($data);
	// exit;
	
	$conn->close();
	return $data;
}

function getUserQty($where='') {
	$ret_data = array();
	$conn = getDB();
	if(empty($where))
	 	$sql="select * from online_shipment_data a where onshda_status>=0 order by onshda_add_date desc, onshda_sn desc";
	else
	 	$sql="select * from online_shipment_data a where onshda_status>=0 and ( $where ) order by onshda_add_date desc, onshda_sn desc";

	$qresult = $conn->query($sql);
	if ($qresult->num_rows > 0) {
	 	while($row = $qresult->fetch_assoc()) {
	  		$sql_getdata = "select distinct onproduct_isbought from onliine_product_data where onproduct_status>=0 and onproduct_part_no like '".$row['onadd_part_no']."'";
	  		$qresult2 = $conn->query($sql_getdata);
	  
	  		$row['onadd_plant_st'] = $qresult2->fetch_assoc()['onproduct_isbought'];
	  		$ret_data[] = $row;
	 	}
	}
	$conn->close();
	return count($ret_data);
}

function getUserBySn($onshda_sn) {
	$ret_data = array();
	$conn = getDB();
	$sql="select * from online_shipment_data where onshda_sn='{$onshda_sn}'";

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
	$sql="select * from online_shipment_data where onadd_part_no='{$account}'";

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