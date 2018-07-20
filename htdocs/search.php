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
        <li class="nav-item">
          <a class="nav-link" href="jisseki.php">実績入力</a>
        </li>
        <li class="nav-item active">
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

    <h1 class="h1 my-3">検索・編集・削除</h1>
    <h4>検索条件</h4>
    <table class="table table-striped table-bordered table-condensed">
      <thead>
        <tr>
          <th style="width: 200px" class="text-center">開始日</th>
          <th style="width: 200px" class="text-center">終了日</th>
          <th style="width: 200px" class="text-center">名前</th>
          <th style="width: 250px" class="text-center">部署</th>
          <th style="width: 250px" class="text-center">グループ</th>
        </tr>
      </thead>
      <tbody>

        <form name="form1" method="post" action="search.php">
          <tr>
            <td class="m-0 p-0">
              <div id="datepicker-default">
                <div class="form-inline">
                  <div class="input-group date w-100">
                    <input type="text" class="form-control" placeholder="ex)2018-04-01" name="search_start_date" autocomplete="off" value="<?php
										if(!isset($_POST['search_start_date'])){
											echo date('Y-m-d', strtotime('-1 day'));
										}else{
											echo $_POST['search_start_date'];
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
                    <input type="text" class="form-control" placeholder="ex)2019-03-31" name="search_end_date" autocomplete="off" value="<?php
										 if(!empty($_POST['search_end_date'])){
											 echo $_POST['search_end_date'];
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
              <select class="form-control" name="search_employee_id">
                <option value="" <?php if(empty($_POST['search_employee_id'])){echo "selected";} ?>>(指定なし)</option>
                <?php

								if(!empty($_POST['seartch_department_id'])){
									$sql_department = " AND employee.department_id = '" . $_POST['seartch_department_id'] ."' ";
								}else{
									$sql_department = "";
								}

								if(!empty($_POST['search_group_id'])){
									$sql_group = " AND employee.group_id = '" . $_POST['search_group_id'] ."' ";
								}else{
									$sql_group = "";
								}

                try{
                  $sql="SELECT * from employee
									WHERE 1"
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
                      if(!empty($_POST['search_employee_id'])){
                        if($_POST['search_employee_id'] == htmlspecialchars($row['employee_id'],ENT_QUOTES)){
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
              <select class="form-control" name="search_department_id">
                <option value="" <?php if(empty($_POST['search_department_id'])){echo "selected";} ?>>(指定なし)</option>
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
                      if(!empty($_POST['search_department_id'])){
                        if($_POST['search_department_id'] == htmlspecialchars($row['department_id'],ENT_QUOTES)){
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
              <select class="form-control" name="search_group_id" value="
              <?php
              if(!empty($_POST['search_group_id'])){
                echo $_POST['search_group_id'];
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
                    if(!empty($_POST['search_group_id'])){
                      if($_POST['search_group_id'] == htmlspecialchars($row['group_id'],ENT_QUOTES)){
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
    <button class="btn btn-lg btn-primary" type="submit" name='action' value='search'>検索</button>
  </form>


  <?php

  if(!isset($_POST['search_start_date'])){ //変数が未定義またはNULLのとき
    $sql_search_start_date = " AND zangyo_date >= '" . date('Y-m-d', strtotime('-1 day')) ." 00:00:00'";
  }else{
    $sql_search_start_date = " AND zangyo_date >= '" . $_POST['search_start_date'] ." 00:00:00'";
  }
  if(!empty($_POST['search_end_date'])){
    $sql_search_end_date = " AND zangyo_date <= '" . $_POST['search_end_date'] ." 23:59:59'";
  }else{
    $sql_search_end_date = "";
  }
  if(!empty($_POST['search_employee_id'])){
    $sql_search_employee_id = " AND employee.employee_id = " . $_POST['search_employee_id'];
  }else{
    $sql_search_employee_id = "";
  }
  if(!empty($_POST['search_department_id'])){
    $sql_search_department_id = " AND department.department_id = " . $_POST['search_department_id'];
  }else{
    $sql_search_department_id = "";
  }
  if(!empty($_POST['search_group_id'])){
    $sql_search_group_id = " AND work_group.group_id = " . $_POST['search_group_id'];
  }else{
    $sql_search_group_id = "";
  }
  $sql_where = " WHERE 1 ". $sql_search_start_date . $sql_search_end_date . $sql_search_employee_id . $sql_search_department_id . $sql_search_group_id;
  try{
    $sql="SELECT zangyo.id,zangyo.zangyo_date,zangyo.app_time,zangyo.employee_id,employee.employee_name,case_id.category,zangyo.project,zangyo.project_detail,zangyo.boss_check,zangyo.remarks,zangyo.result_time,employee.department_id,department.department_name,employee.group_id,work_group.group_name
    from ((((zangyo LEFT OUTER JOIN employee ON zangyo.employee_id = employee.employee_id)
    LEFT OUTER JOIN case_id ON zangyo.case_id = case_id.case_id)
    LEFT OUTER JOIN department ON employee.department_id = department.department_id)
    LEFT OUTER JOIN work_group ON employee.group_id = work_group.group_id)"
    . $sql_where .
    " ORDER BY zangyo_date DESC";
    //                        where id=(select max(id) from zangyo)";
    $stmh=$pdo->prepare($sql);
    $stmh->execute();
    $count=$stmh->rowCount();
  }catch(PDOException $Exception){
    print"エラー：".$Exception->getMessage();
  }
  ?>
  <div class="border-top border-bottom my-2 py-2">
    <form method="get" action="zangyo_check.php">
      <button class="btn btn-lg btn-primary" type="submit" name='action' value='edit'>編集</button>
      <button class="btn btn-lg btn-primary" type="submit" name='action' value='delete'>削除</button>
    </div>

    <div class="table-responsive">
      <table class="table table-striped table-bordered table-condensed table-responsive text-center" style="table-layout:fixed;">
        <thead>
          <tr>
            <th style="width:50px;"></th>
            <th style="width:200px;">実施日</th>
            <th style="width:100px;">種別</th>
            <th style="width:150px;">名前</th>
            <th style="width:150px;">部署</th>
            <th style="width:150px;">グループ</th>
            <th style="width:150px;">申請時間</th>
            <th style="width:150px;">実施時間</th>
            <th style="width:150px;">月間累計</th>
            <th style="width:150px;">年間累計</th>
            <th style="width:100px;">確認</th>
            <th style="width:200px;">機種</th>
            <th style="width:400px;">内容</th>
            <th style="width:600px;">備考</th>
          </tr>
        </thead>
        <tbody>

          <tr>
            <?php
            if($count>0){
              while($row=$stmh->fetch(PDO::FETCH_ASSOC)){
                ?>
                <td><input type="checkbox" name="id[]" value="<?=htmlspecialchars($row['id'],ENT_QUOTES)?>"></td>
                <td><!--実施日--><?php
                $datetime = new DateTime($row['zangyo_date']);
                $week = array("日", "月", "火", "水", "木", "金", "土");
                $w = (int)$datetime->format('w');
                echo htmlspecialchars(substr($row['zangyo_date'],0,10),ENT_QUOTES) . " (" . $week[$w] . ")";
                ?></td> <!--<?php /* <? PHPの式 ?>は<? echo PHPの式 ?>の省略形 */ ?>-->
                <td><?=htmlspecialchars($row['category'],ENT_QUOTES)?></td><!--種別-->
                <td><?=htmlspecialchars($row['employee_name'],ENT_QUOTES)?></td><!--名前-->
                <td><?=htmlspecialchars($row['department_name'],ENT_QUOTES)?></td><!--部署-->
                <td><?=htmlspecialchars($row['group_name'],ENT_QUOTES)?></td><!--グループ-->
                <td><?=htmlspecialchars(substr($row['app_time'],0,-3),ENT_QUOTES)?></td><!--申請時間-->
                <td><!--実施時間-->
                  <?php
                  if(Is_null($row['result_time']) == TRUE){
                    echo "";
                  }else {
                    echo htmlspecialchars(substr($row['result_time'],0,-3),ENT_QUOTES);
                  }
                  ?></td>
                  <?php require("../php_libs/SUM_MONTH.php"); ?>
                  <td class="<?php require("../php_libs/ALERT_MONTH.php"); ?>"><?= $sum_month; ?></td><!--月間累計-->

                  <?php
                  require_once("../php_libs/FUNC_CHANGE_TO_APR1.php");
                  require("../php_libs/SUM_YEAR.php");
                   ?>
                  <td class="<?php require("../php_libs/ALERT_YEAR.php"); ?>"><?= $sum_year; ?></td><!--年間累計-->
                  <td>
                    <?php
                    if(htmlspecialchars($row['boss_check'],ENT_QUOTES) == "1")
                    {
                      echo "済";
                    }else{
                      echo "";
                    }
                    ?>
                  </td><!--確認-->
                  <td><?=htmlspecialchars($row['project'],ENT_QUOTES)?></td><!--機種-->
                  <td><?=htmlspecialchars($row['project_detail'],ENT_QUOTES)?></td><!--内容-->
                  <td><?=htmlspecialchars($row['remarks'],ENT_QUOTES)?></td><!--備考-->
                </tr>
                <?php
              }
            }
            ?>
          </tbody>
        </table>
      </div>
      <hr>
      <form method="get" action="zangyo_check.php">
        <button class="btn btn-lg btn-primary" type="submit" name='action' value='edit'>編集</button>
        <button class="btn btn-lg btn-primary" type="submit" name='action' value='delete'>削除</button>
        <hr>

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
