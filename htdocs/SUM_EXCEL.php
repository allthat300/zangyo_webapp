<?php
require_once("../php_libs/MYDB.php");
$pdo = db_connect();

// echo $_POST['zangyo_date'];
// echo date('Y-m-d',strtotime($_POST['zangyo_date']));

try{
	$sql_sum_month="SELECT sec_to_time(sum(time_to_sec(IFNULL(result_time,'00:00:00')))) AS sum_month_time from zangyo
	where zangyo_date BETWEEN '".substr(date('Y-m-d',strtotime($_POST['zangyo_date'])),0,8)."01 00:00:00' AND '" . date('Y-m-d',strtotime($_POST['zangyo_date']))
	. " 23:59:59' " . "AND employee_id = '" . $_POST['employee_id'] . "' "
	."ORDER BY zangyo_date,case_id desc"; //""内の''や""はよくわからないので.で連結
	$stmh_sum_month=$pdo->prepare($sql_sum_month);
	$stmh_sum_month->execute();
	$count_sum_month=$stmh_sum_month->rowCount();
}catch(PDOException $Exception_sum_month){
	print"エラー：".$Exception_sum_month->getMessage();
}

//echo $sql_sum_month;
if($count_sum_month == 0){
	echo "0";
}else{
	while($row_sum_month=$stmh_sum_month->fetch(PDO::FETCH_ASSOC)){
		$sum_month = htmlspecialchars(substr($row_sum_month['sum_month_time'],0,-3),ENT_QUOTES);
	}
	if ($sum_month == ""){
		echo "残業していません。";
	} else{
		echo $sum_month;
	}
}
