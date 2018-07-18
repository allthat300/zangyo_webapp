<?php
require_once("../php_libs/MYDB.php");
$pdo = db_connect();
?>

<!doctype html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" href="/img/favicon.ico">

  <title>残業管理</title>

  <!-- Bootstrap core CSS -->
  <link href="/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <!-- <link href="/dist/css/navbar-top-fixed.css" rel="stylesheet"> -->

  <!-- Custom styles for this template -->
  <link href="/dist/css/sticky-footer-navbar.css" rel="stylesheet">

  <!-- for bootstrap-datepicker -->
  <!-- <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet"> -->
  <link rel="stylesheet" type="text/css" href="/dist/css/bootstrap-datepicker.min.css">

</head>

<body>

  <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
    <a class="navbar-brand" href="index.php">残業管理</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
          <a class="nav-link" href="index.php">申請</a>
        </li>
        <li class="nav-item active">
          <a class="nav-link" href="jisseki.php">実績入力</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="search.php">編集・削除</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="approval.php">承認</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">設定</a>
          <div class="dropdown-menu" aria-labelledby="dropdown04">
            <a class="dropdown-item" href="add-employee.php">社員追加</a>
            <a class="dropdown-item" href="edit-department.php">部署変更</a>
            <a class="dropdown-item" href="#">*****</a>
          </div>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">レポート</a>
          <div class="dropdown-menu" aria-labelledby="dropdown04">
						<a class="dropdown-item" href="report-month.php">月間(部署)</a>
            <a class="dropdown-item" href="report-year.php">年間(部署)</a>
            <a class="dropdown-item" href="report-personal-month.php">月間(個人)</a>
						<a class="dropdown-item" href="report-personal-year.php">年間(個人)</a>
						<a class="dropdown-item" href="report-each-person-month.php">個人別</a>
          </div>
        </li>
      </ul>
    </div>
  </nav>

  <main role="main" class="container-fluid">
    <div class="border-bottom pb-2 mb-2">
      <h1 class="h1 my-3">実績入力</h1>

			<h4>検索条件</h4>
      <table class="table table-striped table-bordered table-condensed">
        <thead>
          <tr>
            <th style="width: 200px" class="text-center">開始日</th>
						<th style="width: 200px" class="text-center">終了日</th>
            <th style="width: 250px" class="text-center">名前</th>
						<th style="width: 250px" class="text-center">名前を部署で絞り込み</th>
						<th style="width: 250px" class="text-center">名前をグループで絞り込み</th>
          </tr>
        </thead>
        <tbody>

          <form name="form1" method="post" action="jisseki.php">
            <tr>
              <td class="m-0 p-0">
                <div id="datepicker-default">
                  <div class="form-inline">
                    <div class="input-group date w-100">
                      <input type="text" class="form-control" name="start_date" autocomplete="off" value="<?php
											if(!empty($_POST['start_date'])){
												echo $_POST['start_date'];
											}else{
												echo date('Y-m-d', strtotime('-1 day'));
											}
											?>">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </td>
							<td class="m-0 p-0">
                <div id="datepicker-default">
                  <div class="form-inline">
                    <div class="input-group date w-100">
                      <input type="text" class="form-control" name="end_date" autocomplete="off" value="<?php
											if(!empty($_POST['end_date'])){
												echo $_POST['end_date'];
											}else{
												echo date('Y-m-d', strtotime('+1 day'));
											}
											?>">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </td>
              <td class="m-0 p-0">
                <select class="form-control" name="employee_id">
                  <option value="" <?php if(empty($_POST['employee_id'])){echo "selected";} ?>>(指定なし)</option>
                  <?php

									if(!empty($_POST['department_id'])){
								    $sql_department = " AND employee.department_id = '" . $_POST['department_id'] ."' ";
								  }else{
								    $sql_department = "";
								  }

								  if(!empty($_POST['group_id'])){
								    $sql_group = " AND employee.group_id = '" . $_POST['group_id'] ."' ";
								  }else{
								    $sql_group = "";
								  }

                  try{
                    $sql="SELECT * from employee
										WHERE 1 "
										.$sql_department
										.$sql_group;

                    $stmh=$pdo->prepare($sql);
                    $stmh->execute();
                    $count=$stmh->rowCount();
                  }catch(PDOException $Exception){
                    print"エラー：".$Exception->getMessage();
                  }
                  if($count>0){
                    while($row=$stmh->fetch(PDO::FETCH_ASSOC)){
                      ?>
                      <option value="<?=htmlspecialchars($row['employee_id'],ENT_QUOTES)?>"
                        <?php
                        if(!empty($_POST['employee_id'])){
                          if($_POST['employee_id'] == htmlspecialchars($row['employee_id'],ENT_QUOTES)){
                            echo "selected";
                          }
                        }
                        ?>
                        >
                        <?=htmlspecialchars($row['employee_name'],ENT_QUOTES)?>
                      </option>
                      <?php
                    }
                  }
                  ?>
                </select>
              </td>
							<td class="m-0 p-0">
                <select class="form-control" name="department_id">
                  <option value="" <?php if(empty($_POST['department_id'])){echo "selected";} ?>>(指定なし)</option>
                  <?php
                  try{
                    $sql="SELECT * from department";
                    $stmh=$pdo->prepare($sql);
                    $stmh->execute();
                    $count=$stmh->rowCount();
                  }catch(PDOException $Exception){
                    print"エラー：".$Exception->getMessage();
                  }
                  if($count>0){
                    while($row=$stmh->fetch(PDO::FETCH_ASSOC)){
                      ?>
                      <option value="<?=htmlspecialchars($row['department_id'],ENT_QUOTES)?>"
                        <?php
                        if(!empty($_POST['department_id'])){
                          if($_POST['department_id'] == htmlspecialchars($row['department_id'],ENT_QUOTES)){
                            echo "selected";
                          }
                        }
                        ?>
                        >
                        <?=htmlspecialchars($row['department_name'],ENT_QUOTES)?>
                      </option>
                      <?php
                    }
                  }
                  ?>
                </select>
              </td>
              <td class="m-0 p-0">
                <select class="form-control" name="group_id" value="
                <?php
                if(!empty($_POST['group_id'])){
                  echo $_POST['group_id'];
                }
                ?>
                ">
                <option value="">(指定なし)</option>
                <?php
                try{
                  $sql="SELECT * from work_group";
                  $stmh=$pdo->prepare($sql);
                  $stmh->execute();
                  $count=$stmh->rowCount();
                }catch(PDOException $Exception){
                  print"エラー：".$Exception->getMessage();
                }
                if($count>0){
                  while($row=$stmh->fetch(PDO::FETCH_ASSOC)){
                    ?>
                    <option value="<?=htmlspecialchars($row['group_id'],ENT_QUOTES)?>"
                      <?php
                      if(!empty($_POST['group_id'])){
                        if($_POST['group_id'] == htmlspecialchars($row['group_id'],ENT_QUOTES)){
                          echo "selected";
                        }
                      }
                      ?>
                      >
                      <?=htmlspecialchars($row['group_name'],ENT_QUOTES)?>
                    </option>
                    <?php
                  }
                }
                ?>
              </select>
            </td>
          </tr>

        </tbody>
      </table>
      <button class="btn btn-lg btn-primary" type="submit" name='action' value='report'>検索</button>
    </form>

</div>
      <form name="form2" method="post" action="jisseki.php">
        <button class="btn btn-primary btn-lg" type="submit">送信</button>
        <?php require("../php_libs/UPDATE_JISSEKI.php"); ?>

      <table class="table table-striped table-bordered table-condensed my-2">
        <thead>
          <tr>
            <th>実施日</th>
            <th>種別</th>
            <th>名前</th>
            <th>部署</th>
            <th>グループ</th>
            <th>申請時間</th>
            <th>実施時間</th>
            <th>月間累計</th>
            <th>年間累計</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <?php

						if(!empty($_POST['employee_id'])){
							$sql_employee = " AND zangyo.employee_id = '" . $_POST['employee_id'] . "' ";
						}else{
							$sql_employee = "";
						}
						if(isset($_POST['start_date'])){
							$sql_start_date = " AND zangyo.zangyo_date >= '" . $_POST['start_date'] . " 00:00:00' ";
						}else{
							$sql_start_date = " AND zangyo.zangyo_date >= '" . date('Y-m-d', strtotime('-1 day')) ." 00:00:00'";
						}
						if(isset($_POST['end_date'])){
							$sql_end_date = " AND zangyo.zangyo_date <= '" . $_POST['end_date'] . " 23:59:59' ";
						}else{
							$sql_end_date = " AND zangyo.zangyo_date <= '" . date('Y-m-d', strtotime('+1 day')) . " 23:59:59' ";
						}

						$sql_where = " WHERE 1 " . $sql_employee . $sql_start_date . $sql_end_date . $sql_department . $sql_group;

            try{
              $sql="SELECT zangyo.id,zangyo.zangyo_date,zangyo.app_time,zangyo.employee_id,employee.employee_name,case_id.category,zangyo.project,zangyo.project_detail,zangyo.remarks,zangyo.result_time,department.department_name,work_group.group_name
              from ((((zangyo LEFT OUTER JOIN employee ON zangyo.employee_id = employee.employee_id)
              LEFT OUTER JOIN case_id ON zangyo.case_id = case_id.case_id)
              LEFT OUTER JOIN department ON employee.department_id = department.department_id)
              LEFT OUTER JOIN work_group ON employee.group_id = work_group.group_id) "
							. $sql_where
              . "ORDER BY zangyo_date DESC";
              $stmh=$pdo->prepare($sql);
              $stmh->execute();
              $count=$stmh->rowCount();
            }catch(PDOException $Exception){
              print"エラー：".$Exception->getMessage();
            }

            if($count>0){
              while($row=$stmh->fetch(PDO::FETCH_ASSOC)){
                ?>
                <td class="align-middle m-0 p-0"><!--実施日--><?php

                $datetime = new DateTime($row['zangyo_date']);
                $week = array("日", "月", "火", "水", "木", "金", "土");
                $w = (int)$datetime->format('w');
                echo htmlspecialchars(substr($row['zangyo_date'],0,10),ENT_QUOTES) . " (" . $week[$w] . ")";
                ?></td> <!--<?php /* <? PHPの式 ?>は<? echo PHPの式 ?>の省略形 */ ?>-->
                <td class="align-middle m-0 p-0"><?=htmlspecialchars($row['category'],ENT_QUOTES)?></td><!--種別-->
                <td class="align-middle m-0 p-0"><?=htmlspecialchars($row['employee_name'],ENT_QUOTES)?></td><!--名前-->
                <td class="align-middle m-0 p-0"><?=htmlspecialchars($row['department_name'],ENT_QUOTES)?></td><!--部署-->
                <td class="align-middle m-0 p-0"><?=htmlspecialchars($row['group_name'],ENT_QUOTES)?></td></td><!--グループ-->
                <td class="align-middle m-0 p-0"><?=htmlspecialchars(substr($row['app_time'],0,-3),ENT_QUOTES)?></td><!--申請時間-->

                <!--実施時間-->

                <?php
                if(Is_null($row['result_time']) == TRUE){
                  ?>
                  <td class="align-middle m-0 p-0">
                    <input type="text" class="form-control" placeholder="実施時間 ex)1:30" name="id[<?=$row['id']?>]">
                  </td>
                  <?php
                }else {
                  ?>
                  <td class="align-middle m-0 p-2"><?=htmlspecialchars(substr($row['result_time'],0,-3),ENT_QUOTES)?></td>
                  <?php
                }
                ?>
                <?php require("../php_libs/SUM_MONTH.php"); ?>
                <td class="align-middle m-0 p-0 <?php require("../php_libs/ALERT_MONTH.php"); ?>"><?= $sum_month; ?></td><!--月間累計-->

                <?php
                require_once("../php_libs/FUNC_CHANGE_TO_APR1.php");
                require("../php_libs/SUM_YEAR.php");
                 ?>
                <td class="align-middle m-0 p-0 <?php require("../php_libs/ALERT_YEAR.php"); ?>"><?= $sum_year; ?></td><!--年間累計-->
              </tr>
              <?php
            }
          }
          ?>
        </tbody>
      </table>
    </form>
  </main>
  <footer class="footer">
    <div class="container text-center">
      <span class="text-muted">残業管理システム 2018 Yusuke.Kishi</span>
    </div>
  </footer>

  <!-- Bootstrap core JavaScript
  ================================================== -->
  <!-- Placed at the end of the document so the pages load faster -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script>window.jQuery || document.write('<script src="../../../../assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
  <script src="/dist/js/vendor/popper.min.js"></script>
  <script src="/dist/js/bootstrap.min.js"></script>

  <!-- Icons -->
  <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
  <script>
  feather.replace()
</script>

<!-- Datepicker -->
<script type="text/javascript" src="/dist/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="/dist/js/bootstrap-datepicker.ja.js"></script>

<script>
$(function(){
	//Default
	$('#datepicker-default .date').datepicker({
		format: "yyyy-mm-dd",
		language: 'ja',
		autoclose: true,
		todayBtn: 'linked',
		defaultDate: 0
	});

});
</script>


</body>
</html>
