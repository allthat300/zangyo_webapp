<?php
try{
  $sql_alert_month="SELECT * FROM alert WHERE alert_type = 'month' ORDER BY alert_id";
  $stmh_alert_month=$pdo->prepare($sql_alert_month);
  $stmh_alert_month->execute();
  $count_alert_month=$stmh_alert_month->rowCount();
}catch(PDOException $Exception_alert_month){
  print"エラー：".$Exception_alert_month->getMessage();
}
//echo $sql_sum_month;

$sum_month_int = (int)substr($sum_month,0,-3) + (int)substr($sum_month,-2,0)/60;
$alert_month_int = array();

if($count_alert_month=0){
}else{
  while($row_alert_month=$stmh_alert_month->fetch(PDO::FETCH_ASSOC)){
    $alert_month_int[] = (int)$row_alert_month['alert_hour'];
  }
}
// print_r($alert_month_int);
if($sum_month_int < $alert_month_int[0]){
  echo "";
}elseif($sum_month_int < $alert_month_int[1]){
    echo "table-warning text-danger";
}else{
    echo "table-danger text-danger";
}
?>
