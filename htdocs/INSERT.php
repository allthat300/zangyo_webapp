<?php
require_once("../php_libs/MYDB.php");
$pdo = db_connect();

if(!empty($_POST['employee_id'])){
	try{
		$sql_check="SELECT * from employee WHERE employee_id = :employee_id_check";
		$stmh_check=$pdo->prepare($sql_check);
		$stmh_check->bindValue(':employee_id_check',mb_convert_kana($_POST['employee_id'],'a'),PDO::PARAM_STR);
		$stmh_check->execute();
		$count_check=$stmh_check->rowCount();
	}catch(PDOException $Exception_check){
		print"エラー：".$Exception_check->getMessage();
	}
	if($count_check == 0){
		echo "<h2>社員番号が登録されていません</h2>";
		exit;
	}
}else{
	echo "<h2>社員番号が入力されていません</h2>";
	exit;
}

try{
	$pdo->beginTransaction(); //トランザクション開始
	$sql = "INSERT INTO zangyo (zangyo_date,case_id,employee_id,app_time,project,project_detail,remarks)
	values (:zangyo_date,:case_id,:employee_id,:app_time,:project,:project_deteil,:remarks)";
	$stmh = $pdo->prepare($sql);  //prepareメソッドで各テーブル名(date,case_id...)に対しパラメータ(:date,:case_id...)を与える。

	if(isset($_POST['zangyo_date']) || isset($_POST['zangyo_category'])){

		if($_POST['zangyo_category'] == 1){
			$zangyo_adjust = " 18:00:00";
		}else{
			$zangyo_adjust = " 00:00:00";
		};

		$stmh->bindValue(':zangyo_date',substr($_POST['zangyo_date'],0,10).$zangyo_adjust,PDO::PARAM_STR); //prepareメソッドの:dateに外部からのdata_formを結びつける。データ型は文字列。
		$stmh->bindValue(':case_id',$_POST['zangyo_category'],PDO::PARAM_INT);
		$stmh->bindValue(':employee_id',sprintf('%06d',mb_convert_kana($_POST['employee_id'], 'a')),PDO::PARAM_INT); //6桁で0埋め
		// $stmh->bindValue(':employee_id',mb_convert_kana($_POST['employee_id'], 'a'),PDO::PARAM_INT);
		$stmh->bindValue(':app_time',mb_convert_kana($_POST['zangyo_time'], 'a'),PDO::PARAM_STR);
		$stmh->bindValue(':project',$_POST['model_name'],PDO::PARAM_STR);
		$stmh->bindValue(':project_deteil',$_POST['zangyo_detail'],PDO::PARAM_STR);
		$stmh->bindValue(':remarks',$_POST['zangyo_remarks'],PDO::PARAM_STR);
		$stmh->execute(); //プリペアドステートメントの実行
		$pdo->commit(); //トランザクションをコミット


		header('Location: index.php', true, 301); //indexにリダイレクト

		// すべての出力を終了
		exit;
	}
}catch (PDOException $Exception){
	$pdo->rollBack(); //トランザクションをロールバック
	print "エラー：".$Exception->getMessage();
}
