<?php
include_once("./func_plant_purchase.php");
$status_mapping = array(0=>'<font color="red">關閉</font>', 1=>'<font color="blue">啟用</font>');
$permissions_mapping = array(
    1=>'<font color="#666666">1.7</font>',
    2=>'<font color="#666666">2.5</font>',
    3=>'<font color="#666666">2.8</font>',
    4=>'<font color="#666666">3.0</font>',
    5=>'<font color="#666666">3.5</font>',
    6=>'<font color="#666666">3.6</font>',
    7=>'<font color="#666666">其他</font>' 
);

//------------------------------------------------------------data
function getSQL($qry) {
    $conn = getDB();
    $result = $conn->query($qry);
    $conn->close();
    return $result;
}


$onadd_part_no = GetParam('onadd_part_no');
$onadd_growing = GetParam('onadd_growing');
$onadd_quantity_del = GetParam('onadd_quantity_del');
// onadd_quantity_del
// $onadd_part_no = 'PP-0052';
// $onadd_growing = '1';
// $onadd_quantity_del ='2019';
$user_list = getDetails($onadd_part_no,$onadd_growing,$onadd_quantity_del);
// $onadd_part_name = $user_list['onadd_part_name'];
// printr($user_list);
// printr($onadd_part_name);
// exit;
//------------------------------------------------------------data

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
                    //  init search parm
            print "$('#search [name=onadd_status] option[value={$onadd_status}]').prop('selected','selected');";
            print "$('#search [name=onadd_growing] option[value={$onadd_growing}]').prop('selected','selected','selected','selected','selected','selected','selected');";
            ?>
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
                    <h4>產品明細＆可供量表</h4>
                </div>
            </div>
        </div>

        <!-- Page Content -->

    <div class="col-md-10">


        <?php
        foreach ($user_list as $row) {
            echo '<h3>'.$onadd_part_no.'</h3>';
            echo '<p>'. '品號(Part no.) : '. $row['onadd_part_name'].'</p>';
            echo '<p>'. '花色 (Flower Color) : '. $row['onadd_color'].'</p>';
            echo '<p>'. '花徑 (Flower Size) : '. $row['onadd_size'].'</p>';
            echo '<p>'. '高度 (Plant Height) : '. $row['onadd_height'].'</p>';
            echo '<p>'. '適合開花盆徑 (Suitable flowering pot size) : '. $row['onadd_pot_size'].'</p>';
        }
        ?> 
    </div>

    <!-- container -->
    <div  class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <?php
                $href = './details_table.php?onadd_part_no='.$onadd_part_no.'&onadd_growing='.$onadd_growing.'&onadd_quantity_del='.'2020'.'&end='.$end;
                ?>
                <!-- echo '<td><button type="button" class="btn btn-info btn-xs" onclick="location.href=\'./details_table.php?onadd_part_no='.$row['onadd_part_no'].'&onadd_growing='.$row['onadd_growing'].'&onadd_quantity_del='.$row['onadd_quantity_del'].'&start='.$start.'&end='.$end.'\'">查看</button></td>'; -->

                <!-- details_table.php?onadd_part_no=PP-0052&onadd_growing=1&onadd_quantity_del=2019 -->
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#home">2019</a></li>
                    <li><a data-toggle="tab" href="#menu1">2020</a></li>
                    <li><a data-toggle="tab" href="#menu2">2021</a></li>
                    <li><a data-toggle="tab" href="#menu3">2022</a></li>
                    <li><a data-toggle="tab" href="#menu3">2023</a></li>
                </ul>

                <!-- content 
                <table class="table table-striped table-hover table-condensed tablesorter">
                    <thead>
                        <tr>
                            <th>月份</th>
                            <th>規格</th>
                            <th>數量</th>
                        </tr>
                    </thead>
                    <tbody>
                        foreach ($user_list as $row) {
                            echo '<tr>';
                                    echo '<td>'.$row['onadd_quantity_shi'].'月'.'</td>';//品號
                                    echo '<td>'.$permissions_mapping[$row['onadd_growing']].'寸'.'</td>';
                                    echo '<td>'.$row['SUM(onadd_quantity)'].'</td>';//品號
                                    echo '</td></tr>';
                                }
                            </tbody>
                        </table>
                    -->
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <table id="table_summary" class="table table-striped table-hover table-condensed table-bordered">
                        <thead>
                            <tr>
                                <th rowspan="2">尺寸</th>
                                <th colspan="12" class="tableheader" align="center">月份</th>
                            </tr>
                            <tr>
                                <th>一月</th>
                                <th>二月</th>
                                <th>三月</th>
                                <th>四月</th>
                                <th>五月</th>
                                <th>六月</th>
                                <th>七月</th>
                                <th>八月</th>
                                <th>九月</th>
                                <th>十月</th>
                                <th>十一月</th>
                                <th>十二月</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            echo '<td>'.$permissions_mapping[$row['onadd_growing']].'寸'.'</td>';
                            foreach ($user_list as $row) {
                                if ($row['onadd_quantity_shi']==1 ){
                                    echo '<td>'.$row['SUM(onadd_quantity)'].'</td>';//品號
                                }else{
                                     echo '<td>'.''.'</td>';//品號
                                }
                            }
                            foreach ($user_list as $row) {
                                if ($row['onadd_quantity_shi']==2 ){
                                    echo '<td>'.$row['SUM(onadd_quantity)'].'</td>';//品號
                                }else{
                                     echo '<td>'.''.'</td>';//品號
                                }
                            }
                            foreach ($user_list as $row) {
                                if ($row['onadd_quantity_shi']==3 ){
                                    echo '<td>'.$row['SUM(onadd_quantity)'].'</td>';//品號
                                }else{
                                     echo '<td>'.''.'</td>';//品號
                                }
                            }
                            foreach ($user_list as $row) {
                                if ($row['onadd_quantity_shi']==4 ){
                                    echo '<td>'.$row['SUM(onadd_quantity)'].'</td>';//品號
                                }else{
                                     echo '<td>'.''.'</td>';//品號
                                }
                            }
                            foreach ($user_list as $row) {
                                if ($row['onadd_quantity_shi']==5 ){
                                    echo '<td>'.$row['SUM(onadd_quantity)'].'</td>';//品號
                                }else{
                                     echo '<td>'.''.'</td>';//品號
                                }
                            }
                            foreach ($user_list as $row) {
                                if ($row['onadd_quantity_shi']==6 ){
                                    echo '<td>'.$row['SUM(onadd_quantity)'].'</td>';//品號
                                }else{
                                     echo '<td>'.''.'</td>';//品號
                                }
                            }
                            foreach ($user_list as $row) {
                                if ($row['onadd_quantity_shi']==7 ){
                                    echo '<td>'.$row['SUM(onadd_quantity)'].'</td>';//品號
                                }else{
                                     echo '<td>'.''.'</td>';//品號
                                }
                            }
                            foreach ($user_list as $row) {
                                if ($row['onadd_quantity_shi']==8 ){
                                    echo '<td>'.$row['SUM(onadd_quantity)'].'</td>';//品號
                                }else{
                                     echo '<td>'.''.'</td>';//品號
                                }
                            }
                            foreach ($user_list as $row) {
                                if ($row['onadd_quantity_shi']==9 ){
                                    echo '<td>'.$row['SUM(onadd_quantity)'].'</td>';//品號
                                }else{
                                     echo '<td>'.''.'</td>';//品號
                                }
                            }
                            foreach ($user_list as $row) {
                                if ($row['onadd_quantity_shi']==10 ){
                                    echo '<td>'.$row['SUM(onadd_quantity)'].'</td>';//品號
                                }else{
                                     echo '<td>'.''.'</td>';//品號
                                }
                            }
                            foreach ($user_list as $row) {
                                if ($row['onadd_quantity_shi']==11 ){
                                    echo '<td>'.$row['SUM(onadd_quantity)'].'</td>';//品號
                                }else{
                                     echo '<td>'.''.'</td>';//品號
                                }
                            }
                            foreach ($user_list as $row) {
                                if ($row['onadd_quantity_shi']==12 ){
                                    echo '<td>'.$row['SUM(onadd_quantity)'].'</td>';//品號
                                }else{
                                     echo '<td>'.''.'</td>';//品號
                                }
                            }

                            ?>
                        </tbody>
                    </table>
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
