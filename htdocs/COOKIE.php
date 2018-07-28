<?php

require_once("../php_libs/MYDB.php");
$pdo = db_connect();


if(!empty($_POST['log'])){
	if($_POST['log'] == "login"){
		if(!empty($_POST['cookie_employee_id'])){

			try{
				$sql_cookie="SELECT * from employee WHERE employee_id = :employee_id_cookie";
				$stmh_cookie=$pdo->prepare($sql_cookie);
				$stmh_cookie->bindValue(':employee_id_cookie',mb_convert_kana($_POST['cookie_employee_id'],'a'),PDO::PARAM_STR);
				$stmh_cookie->execute();
				$count_cookie=$stmh_cookie->rowCount();
			}catch(PDOException $Exception_cookie){
				print"エラー：".$Exception_cookie->getMessage();
			}
			if($count_cookie == 0){
				echo "<h2>社員番号が登録されていません</h2>";
				exit;
			}
			while($row=$stmh_cookie->fetch(PDO::FETCH_ASSOC)){


				setcookie('employee_name',htmlspecialchars($row['employee_name'],ENT_QUOTES),time()+60*60*24*30);
				setcookie('employee_id',htmlspecialchars($row['employee_id'],ENT_QUOTES),time()+60*60*24*30);
				setcookie('department_id',htmlspecialchars($row['department_id'],ENT_QUOTES),time()+60*60*24*30);
				setcookie('group_id',htmlspecialchars($row['group_id'],ENT_QUOTES),time()+60*60*24*30);
			}
		}
	}elseif($_POST['log'] == "logout"){
		setcookie('employee_name','',time() - 1);
		setcookie('employee_id','',time() - 1);
		setcookie('department_id','',time() - 1);
		setcookie('group_id','',time() - 1);
	}
}

header("Location: " . $_SERVER['HTTP_REFERER'], true, 301); //indexにリダイレクト

// すべての出力を終了
exit;
