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
// onliine_add_data.php
//================================
function getUser($where='', $offset=30, $rows=0) {
	$ret_data = array();
	$conn = getDB();
	if(empty($where))
		$sql="select * from onliine_add_data where onadd_status>=0 and onadd_plant_st=1 order by onadd_add_date desc, onadd_sn desc limit $offset, $rows";
	else
		$sql="select * from onliine_add_data where onadd_status>=0 and onadd_plant_st=1 and ( $where ) order by onadd_add_date desc, onadd_sn desc limit $offset, $rows";

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

function getUseradd($where='', $offset=30, $rows=0) {
	$ret_data = array();
	$conn = getDB();
	if(empty($where))
		$sql="select * from onliine_add_data where onadd_status>=0 and onadd_plant_st=1 GROUP BY onadd_part_no";
	else
		$sql="select * from onliine_add_data where onadd_status>=0 and onadd_plant_st=1 GROUP BY onadd_part_no";

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

function getProducts($where='', $offset=30, $rows=0) {
	$ret_data = array();
	$conn = getDB();
	if(empty($where))
		$sql="select * from  onliine_product_data where onproduct_status>=0 and onproduct_plant_st = 1 GROUP BY onproduct_part_no";
	else
		$sql="select * from  onliine_product_data where onproduct_status>=0 and onproduct_plant_st = 1 and $where GROUP BY onproduct_part_no";

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

function getProductsQty($where='') {
	$ret_data = 0;
	$conn = getDB();
	if(empty($where))
		$sql="select count(*) from onliine_product_data where onproduct_status>=0";
	else
		$sql="select count(*) from onliine_product_data where onproduct_status>=0 AND $where";

	$qresult = $conn->query($sql);
	// echo $sql;
	if ($qresult->num_rows > 0) {
		while($row = $qresult->fetch_assoc()) {
			$ret_data = $row['count(*)'];
		}
		$qresult->free();
	}
	$conn->close();
	return $ret_data;
}

//flag 0:下種 1:出貨 2:汰除 3:換盆
function getHistory_List($onadd_part_no) {
	$ret_data = array();
	$conn = getDB();

	$sql="select onadd_add_date as add_date,onadd_planting_date as mod_date,onadd_quantity as quantity,onadd_quantity_cha from onliine_add_data where onadd_status>=0 and onadd_part_no like '$onadd_part_no' order by onadd_add_date";

	$qresult = $conn->query($sql);

	if ($qresult->num_rows > 0) {
		while($row = $qresult->fetch_assoc()) {
			if(!empty($row['onadd_quantity_cha'])){
				$row['flag'] = 3;	
			}
			else{
				$row['flag'] = 0;			
			}
			$ret_data[] = $row;
		}
		$qresult->free();
	}

	$sql="select onshda_add_date as add_date,onshda_mod_date as mod_date,onshda_quantity as quantity from online_shipment_data where onshda_status>=0 and onadd_part_no like '$onadd_part_no'";

	$qresult = $conn->query($sql);
	
	if ($qresult->num_rows > 0) {
		while($row = $qresult->fetch_assoc()) {
			$row['flag'] = 1;
			$ret_data[] = $row;
		}
		$qresult->free();
	}

	$sql="select onelda_add_date as add_date,onelda_mod_date as mod_date,onelda_quantity as quantity from online_elimination_data where onelda_status>=0 and onadd_part_no like '$onadd_part_no'";

		$qresult = $conn->query($sql);
		
		if ($qresult->num_rows > 0) {
			while($row = $qresult->fetch_assoc()) {
				$row['flag'] = 2;
				$ret_data[] = $row;
			}
			$qresult->free();
		}

	//排序日期
	$num = count($ret_data);
    //只是做迴圈
    for($i = 0 ; $i < $num ; $i++){
        //從最後一個數字往上比較，如果比較小就交換
        for($j = $num-1 ; $j > $k ; $j--){
            if($ret_data[$j]['add_date'] < $ret_data[$j-1]['add_date']){
                //交換兩個數值的小技巧，用list+each
	            list($ret_data[$j]['add_date'] , $ret_data[$j-1]['add_date']) = array($ret_data[$j-1]['add_date'] , $ret_data[$j]['add_date']);
	            list($ret_data[$j]['mod_date'] , $ret_data[$j-1]['mod_date']) = array($ret_data[$j-1]['mod_date'] , $ret_data[$j]['mod_date']);
	            list($ret_data[$j]['quantity'] , $ret_data[$j-1]['quantity']) = array($ret_data[$j-1]['quantity'] , $ret_data[$j]['quantity']);
	            list($ret_data[$j]['flag'] , $ret_data[$j-1]['flag']) = array($ret_data[$j-1]['flag'] , $ret_data[$j]['flag']);
            }
        }
    }
    for($i = 0 ; $i < $num ; $i++){
        //從最後一個數字往上比較，如果比較小就交換    
        $ret_data[$i]['add_date'] = date('Y-m-d',$ret_data[$i]['add_date']);
        $ret_data[$i]['mod_date'] = date('Y-m-d',$ret_data[$i]['mod_date']);
    }


	$conn->close();
	return $ret_data;
}

function getUserQty($where='') {
	$ret_data = 0;
	$conn = getDB();
	if(empty($where))
		$sql="select count(*) from onliine_add_data where onadd_plant_st=1 and onadd_status>=0";
	else
		$sql="select count(*) from onliine_add_data where onadd_plant_st=1 and onadd_status>=0 and ( $where )";

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
function getUserQtyadd($where='') {
	$ret_data = 0;
	$conn = getDB();
	if(empty($where))
		$sql="select count(*) from onliine_add_data where onadd_status>=0 and onadd_plant_st=1 GROUP BY onadd_part_no";
	else
		$sql="select count(*) from onliine_add_data where onadd_status>=0 and onadd_plant_st=1 GROUP BY onadd_part_no";

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

function getProductFirstQty($onfp_part_no) {
	$ret_data = 0;
	$conn = getDB();	
	$sql="select onfp_plant_amount from onliine_firstplant_data where onfp_status>=0 and onfp_part_no like '$onfp_part_no'";
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

function getUserBySn($onadd_sn) {
	$ret_data = array();
	$conn = getDB();
	$sql="select * from onliine_add_data where onadd_sn='{$onadd_sn}'";

	$qresult = $conn->query($sql);
	if ($qresult->num_rows > 0) {
		if ($row = $qresult->fetch_assoc()) {
			$ret_data = $row;
			$ret_data['onadd_planting_date'] = date('Y-m-d',$ret_data['onadd_planting_date']);
		}
		$qresult->free();
	}
	$conn->close();
	return $ret_data;
}

function getProductBySn($onproduct_sn) {
	$ret_data = array();
	$conn = getDB();
	$sql="select * from onliine_product_data where onproduct_sn='{$onproduct_sn}'";

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
	$sql="select * from online_change_basin where onchba_size='{$onchba_size}'";

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

function getBusinessData($onbuda_part_no,$onbuda_size,$onbuda_year) {
	$ret_data = array();
	$conn = getDB();

	$sql="select * , SUM(onbuda_quantity) as quantity from onliine_business_data where onbuda_part_no='$onbuda_part_no' AND onbuda_size='$onbuda_size' AND onbuda_year='$onbuda_year' group by onbuda_day";
	

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

function getProductData($onproduct_sn) {
	$ret_data = array();
	$conn = getDB();
	
	$sql="select * from onliine_product_data where onproduct_sn='$onproduct_sn'";	

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

function getDataDetails($onproduct_part_no,$onproduct_growing) {
	$ret_data = array();
	$conn = getDB();
	if(empty($where))
		$sql="select * from onliine_product_data where onproduct_part_no='$onproduct_part_no' AND onproduct_growing='$onproduct_growing'";
	else
		$sql="select * from onliine_product_data where onproduct_part_no='$onproduct_part_no' AND onproduct_growing='$onproduct_growing'";

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

function getExpectedList($onbuda_part_no,$year,$month) {
	$ret_data = array();
	$conn = getDB();
	$sql="SELECT * FROM `onliine_business_data` WHERE onbuda_part_no = '$onbuda_part_no' AND onbuda_day = $month AND onbuda_year = $year";
	$qresult = $conn->query($sql);
	if ($qresult->num_rows > 0) {
		while($row = $qresult->fetch_assoc()) {
			$row['onbuda_date'] = date('Y-m-d',$row['onbuda_date']);
			$row['onbuda_add_date'] = date('Y-m-d',$row['onbuda_add_date']);
			$ret_data[] = $row;
		}
		$qresult->free();
	}
	$conn->close();
	return $ret_data;
}

function getWorkListByMonth() {
	$list_setting1 = getSettingBySn(1.7);
	$list_setting2 = getSettingBySn(2.5);
	$list_setting5 = getSettingBySn(3.5);
	$ret_data = array();
	$conn = getDB();
	$sql="select * from onliine_add_data where onadd_status>=0 and onadd_schedule!=1";
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
        		case 5:
        			$onchba_cycle = $list_setting5['onchba_cycle'];
        			break;
        	}
        	if($row['onadd_plant_st']==2){
        		$onchba_cycle=1;
        		$test = date("Y/m/d", strtotime("+$onchba_cycle months", $row['onadd_planting_date']));
        	}else{
        		$test = date("Y/m/d", strtotime("+$onchba_cycle months", $row['onadd_planting_date']));
        	}
        	if(date('M',strtotime($test)) == date('M')){
				$ret_data[] = $row;
        	}
		}
		$qresult->free();
	}
	$conn->close();
	return $ret_data;
}

function getExpectedShipByMonth($year,$onadd_part_no,$onadd_growing) {
	$year_start = strtotime($year."/1/1");
    $year_end = strtotime(($year)."/12/31");

	$list_setting1 = getSettingBySn(1.7);
	$list_setting2 = getSettingBySn(2.5);
	$list_setting5 = getSettingBySn(3.5);
	$ret_data = array();
	$conn = getDB();
	$sql="select onadd_planting_date,onadd_quantity,onadd_growing from onliine_add_data where onadd_part_no='$onadd_part_no' AND onadd_growing='$onadd_growing' AND onadd_planting_date";
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
        		case 5:
        			$onchba_cycle = $list_setting5['onchba_cycle'];
        			break;
        	}

        	if($row['onadd_plant_st']==2){
        		$onchba_cycle=1;
        		$test = date("Y/m/d", strtotime("+$onchba_cycle days", $row['onadd_planting_date']));
        	}else{
        		$test = date("Y/m/d", strtotime("+$onchba_cycle days", $row['onadd_planting_date']));
        	}

			
        	if(strtotime($test) > $year_start && strtotime($test) < $year_end){
        		// echo 'original date = '.$row['onadd_planting_date'].'('.date("Y/m/d",$row['onadd_planting_date']).'),';
        		// echo strtotime("+$onchba_cycle days", $row['onadd_planting_date']).'('.date("Y/m/d",strtotime("+$onchba_cycle days", $row['onadd_planting_date'])).')<br>';
        		$row['month'] = date("m", strtotime("+$onchba_cycle days", $row['onadd_planting_date']));
        		$row['count'] = $row['onadd_quantity'];
        		$ret_data[] = $row;
        	}
		}
		$qresult->free();
	}
	$conn->close();

	$expected_number = array();
	for($i=1;$i<=12;$i++)
		$expected_number[$i] = 0;

	for($i=0;$i<count($ret_data);$i++){
		switch ($ret_data[$i]['month']) {
			case '01':
				$expected_number['1'] += $ret_data[$i]['count'];
				break;
			case '02':
				$expected_number['2'] += $ret_data[$i]['count'];
				break;
			case '03':
				$expected_number['3'] += $ret_data[$i]['count'];
				break;
			case '04':
				$expected_number['4'] += $ret_data[$i]['count'];
				break;
			case '05':
				$expected_number['5'] += $ret_data[$i]['count'];
				break;
			case '06':
				$expected_number['6'] += $ret_data[$i]['count'];
				break;
			case '07':
				$expected_number['7'] += $ret_data[$i]['count'];
				break;
			case '08':
				$expected_number['8'] += $ret_data[$i]['count'];
				break;
			case '09':
				$expected_number['9'] += $ret_data[$i]['count'];
				break;
			case '10':
				$expected_number['10'] += $ret_data[$i]['count'];
				break;
			case '11':
				$expected_number['11'] += $ret_data[$i]['count'];
				break;
			case '12':
				$expected_number['12'] += $ret_data[$i]['count'];
				break;
		}
	}
	$ret_data = $expected_number;
	return $ret_data;
}


?>