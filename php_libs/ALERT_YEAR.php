<?php
try{
  $sql_alert_year="SELECT * FROM alert WHERE alert_type = 'year' ORDER BY alert_id";
  $stmh_alert_year=$pdo->prepare($sql_alert_year);
  $stmh_alert_year->execute();
  $count_alert_year=$stmh_alert_year->rowCount();
}catch(PDOException $Exception_alert_year){
  print"エラー：".$Exception_alert_year->getMessage();
}
//echo $sql_sum_year;

$sum_year_int = (int)substr($sum_year,0,-3) + (int)substr($sum_year,-2,0)/60;
$alert_year_int = array();

if($count_alert_year=0){
}else{
  while($row_alert_year=$stmh_alert_year->fetch(PDO::FETCH_ASSOC)){
    $alert_year_int[] = (int)$row_alert_year['alert_hour'];
  }
}
// print_r($alert_year_int);
if($sum_year_int < $alert_year_int[0]){
  echo "";
}elseif($sum_year_int < $alert_year_int[1]){
    echo "table-warning text-danger";
}else{
    echo "table-danger text-danger";
}
?>
