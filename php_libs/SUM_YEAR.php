<?php
try{
  $sql_sum_month="SELECT sec_to_time(sum(time_to_sec(IFNULL(result_time,'00:00:00')))) AS sum_year_time from zangyo
  where zangyo_date BETWEEN '" . change_to_Apr1($row['zangyo_date']) . "' AND '" . $row['zangyo_date'] . "' "
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
    $sum_year = htmlspecialchars(substr($row_sum_month['sum_year_time'],0,-3),ENT_QUOTES);
  }
}
