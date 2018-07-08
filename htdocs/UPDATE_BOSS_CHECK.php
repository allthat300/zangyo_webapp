<?php
//print_r($_POST['id']);

require_once("../php_libs/MYDB.php");
$pdo = db_connect();

foreach($_POST['id'] as $check_id)
{
    try{
      $pdo->beginTransaction(); //トランザクション開始
      $sql = "UPDATE zangyo SET  boss_check = 1 WHERE id = :id";
      $stmh = $pdo->prepare($sql);  //prepareメソッドで各テーブル名(date,case_id...)に対しパラメータ(:date,:case_id...)を与える。

      $stmh->bindValue(':id',$check_id,PDO::PARAM_INT);
      $stmh->execute(); //プリペアドステートメントの実行
      $pdo->commit(); //トランザクションをコミット
      //print substr($id,1)."のデータを登録しました。<br>";  //rowcount:SQL文を実行して検索結果や更新・削除された行数を返すメソッド
    }catch (PDOException $Exception){
      $pdo->rollBack(); //トランザクションをロールバック
      print "エラー：".$Exception->getMessage();
    }
}
header('Location: approval.php', true, 301); //searchにリダイレクト

// すべての出力を終了
exit;
