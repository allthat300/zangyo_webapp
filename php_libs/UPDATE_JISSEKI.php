<?php



if(!empty($_POST['id'])){

  //print_r($_POST);

  foreach($_POST['id'] as $id => $result_time)
  {
    $count_jisseki = 0;
    if($result_time <> "")
    {
      try{
        $pdo->beginTransaction(); //トランザクション開始
        $sql = "UPDATE zangyo SET result_time = :result_time WHERE id = :id";
        $stmh = $pdo->prepare($sql);  //prepareメソッドで各テーブル名(date,case_id...)に対しパラメータ(:date,:case_id...)を与える。

        $stmh->bindValue(':id',$id,PDO::PARAM_INT);
        $stmh->bindValue(':result_time',mb_convert_kana($result_time,'a'),PDO::PARAM_STR);
        $stmh->execute(); //プリペアドステートメントの実行
        $pdo->commit(); //トランザクションをコミット
        //print substr($id,1)."のデータを登録しました。<br>";  //rowcount:SQL文を実行して検索結果や更新・削除された行数を返すメソッド
        $count_jisseki ++;
      }catch (PDOException $Exception){
        $pdo->rollBack(); //トランザクションをロールバック
        print "エラー：".$Exception->getMessage();
      }
    }
  }
}
