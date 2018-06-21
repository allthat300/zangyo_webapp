
<?php

try{
  $pdo->beginTransaction(); //トランザクション開始
  $sql = "INSERT INTO employee (employee_id,employee_name,department_id,group_id)
  values (:employee_id,:employee_name,:department_id,:group_id)";
  $stmh = $pdo->prepare($sql);  //prepareメソッドで各テーブル名(date,case_id...)に対しパラメータ(:date,:case_id...)を与える。

  if(isset($_POST['employee_id']) || isset($_POST['employee_name']) || isset($_POST['department_id']) || isset($_POST['group_id'])){

    $stmh->bindValue(':employee_id',$_POST['employee_id'],PDO::PARAM_INT); //prepareメソッドの:dateに外部からのdata_formを結びつける。データ型は文字列。
    $stmh->bindValue(':employee_name',$_POST['employee_name'],PDO::PARAM_STR);
    $stmh->bindValue(':department_id',$_POST['department_id'],PDO::PARAM_INT);
    $stmh->bindValue(':group_id',$_POST['group_id'],PDO::PARAM_INT);
    $stmh->execute(); //プリペアドステートメントの実行
    $pdo->commit(); //トランザクションをコミット
    print "データを登録しました。<br>";  //rowcount:SQL文を実行して検索結果や更新・削除された行数を返すメソッド
  }
}catch (PDOException $Exception){
  $pdo->rollBack(); //トランザクションをロールバック
  print "エラー：".$Exception->getMessage();
}
