<?php
// print_r($_POST);
// echo "<br>";
// print_r(array_keys($_POST['zangyo']));
// echo "<br>";
// echo "<br>";
// echo array_keys($_POST['zangyo'])[0];
// echo array_keys($_POST['zangyo'])[1];

require_once("../php_libs/MYDB.php");
$pdo = db_connect();

foreach($_POST['zangyo'] as $id1 => $value1)
{
  // echo $id1;
  // echo "<br>";

  // echo $id2;
  // echo "<br>";
  // echo $value2;
  // echo "<br>";


  try{
    $pdo->beginTransaction(); //トランザクション開始
    $sql = "UPDATE zangyo
    SET zangyo_date = :zangyo_date,
    case_id = :case_id,
    app_time = :app_time,
    result_time = :result_time,
    project = :project,
    project_detail = :project_detail,
    boss_check = :boss_check,
    remarks = :remarks
    WHERE id = :id";

    if($value1['case_id'] == 1)
    {
      $zangyo_date = $value1['zangyo_date']." 18:00:00";
    }
    else
    {
      $zangyo_date = $value1['zangyo_date']." 00:00:00";
    }

    $stmh = $pdo->prepare($sql);  //prepareメソッドで各テーブル名(date,case_id...)に対しパラメータ(:date,:case_id...)を与える。
    $stmh->bindValue(':zangyo_date',$zangyo_date,PDO::PARAM_STR);
    $stmh->bindValue(':case_id',$value1['case_id'],PDO::PARAM_INT);
    $stmh->bindValue(':app_time',$value1['app_time'],PDO::PARAM_STR);
    $stmh->bindValue(':result_time',$value1['result_time'],PDO::PARAM_STR);
    $stmh->bindValue(':project',$value1['project'],PDO::PARAM_STR);
    $stmh->bindValue(':project_detail',$value1['project_detail'],PDO::PARAM_STR);
    $stmh->bindValue(':boss_check',$value1['boss_check'],PDO::PARAM_INT);
    $stmh->bindValue(':remarks',$value1['remarks'],PDO::PARAM_STR);
    $stmh->bindValue(':id',$id1,PDO::PARAM_INT);
    $stmh->execute(); //プリペアドステートメントの実行
    $pdo->commit(); //トランザクションをコミット
    //print substr($id,1)."のデータを登録しました。<br>";  //rowcount:SQL文を実行して検索結果や更新・削除された行数を返すメソッド
  }catch (PDOException $Exception){
    $pdo->rollBack(); //トランザクションをロールバック
    print "エラー：".$Exception->getMessage();

  }

}
header('Location: search.php', true, 301); //searchにリダイレクト

// すべての出力を終了
exit;



//     try{
//       $pdo->beginTransaction(); //トランザクション開始
//       $sql = "UPDATE zangyo SET  boss_check = 1 WHERE id = :id";
//       $stmh = $pdo->prepare($sql);  //prepareメソッドで各テーブル名(date,case_id...)に対しパラメータ(:date,:case_id...)を与える。
//
//       $stmh->bindValue(':id',$check_id,PDO::PARAM_INT);
//       $stmh->execute(); //プリペアドステートメントの実行
//       $pdo->commit(); //トランザクションをコミット
//       //print substr($id,1)."のデータを登録しました。<br>";  //rowcount:SQL文を実行して検索結果や更新・削除された行数を返すメソッド
//     }catch (PDOException $Exception){
//       $pdo->rollBack(); //トランザクションをロールバック
//       print "エラー：".$Exception->getMessage();
//     }
// }
// header('Location: search.php', true, 301); //searchにリダイレクト
//
// // すべての出力を終了
// exit;

?>
