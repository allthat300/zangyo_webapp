<?php
try{
  $sql_sum_month="SELECT sec_to_time(sum(time_to_sec(IFNULL(result_time,'00:00:00')))) AS sum_month_time from zangyo
  where zangyo_date BETWEEN '".substr($row['zangyo_date'],0,8)."01 00:00:00' AND '" . $row['zangyo_date'] . "' "
  . "AND employee_id = '" . $row['employee_id'] . "' "
  ."ORDER BY zangyo_date,case_id desc"; //""内の''や""はよくわからないので.で連結
  $stmh_sum_month=$pdo->prepare($sql_sum_month);
  $stmh_sum_month->execute();
  $count_sum_month=$stmh_sum_month->rowCount();
}catch(PDOException $Exception_sum_month){
  print"エラー：".$Exception_sum_month->getMessage();
}
//echo $sql_sum_month;
if($count_sum_month=0){
  echo "0";
}else{
  while($row_sum_month=$stmh_sum_month->fetch(PDO::FETCH_ASSOC)){
    echo htmlspecialchars(substr($row_sum_month['sum_month_time'],0,5),ENT_QUOTES);
  }
}
?>
