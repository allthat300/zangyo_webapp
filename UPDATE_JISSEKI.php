<?php

try{
  $pdo->beginTransaction(); //トランザクション開始
  $sql = "UPDATE zangyo SET result_time = :result_time WHERE id = :id";
  $stmh = $pdo->prepare($sql);  //prepareメソッドで各テーブル名(date,case_id...)に対しパラメータ(:date,:case_id...)を与える。

    $stmh->bindValue(':id',$_POST['id'],PDO::PARAM_INT);
    $stmh->bindValue(':result_time',$_POST['result_time'],PDO::PARAM_STR);
    $stmh->execute(); //プリペアドステートメントの実行
    $pdo->commit(); //トランザクションをコミット
    print "データを登録しました。<br>";  //rowcount:SQL文を実行して検索結果や更新・削除された行数を返すメソッド
  }
}catch (PDOException $Exception){
  $pdo->rollBack(); //トランザクションをロールバック
  print "エラー：".$Exception->getMessage();
}
