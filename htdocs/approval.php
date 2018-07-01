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
        <li class="nav-item">
          <a class="nav-link" href="search.php">編集・削除</a>
        </li>
        <li class="nav-item active">
          <a class="nav-link" href="approval.php">承認</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">設定</a>
          <div class="dropdown-menu" aria-labelledby="dropdown04">
            <a class="dropdown-item" href="add-employee.php">社員追加</a>
            <a class="dropdown-item" href="#">部署変更</a>
            <a class="dropdown-item" href="#">*****</a>
          </div>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">レポート</a>
          <div class="dropdown-menu" aria-labelledby="dropdown04">
            <a class="dropdown-item" href="#">期間</a>
            <a class="dropdown-item" href="#">部署</a>
            <a class="dropdown-item" href="#">個人</a>
          </div>
        </li>
      </ul>
  </div>
</nav>

<main role="main" class="container-fluid">

      <h1 class="h1 my-3">部長申請・承認</h1>
      <h4>検索条件</h4>
      <button class="btn btn-primary" type="submit" name='action' value='search'>検索</button>
      <?php
      try{
        $sql="SELECT zangyo.id,zangyo.zangyo_date,zangyo.app_time,zangyo.employee_id,employee.employee_name,case_id.category,zangyo.project,zangyo.project_detail,zangyo.boss_check,zangyo.remarks,zangyo.result_time,department.department_name,work_group.group_name
        from ((((zangyo LEFT OUTER JOIN employee ON zangyo.employee_id = employee.employee_id)
        LEFT OUTER JOIN case_id ON zangyo.case_id = case_id.case_id)
        LEFT OUTER JOIN department ON employee.department_id = department.department_id)
        LEFT OUTER JOIN work_group ON employee.group_id = work_group.group_id)
        ORDER BY zangyo_date DESC
        ";
        //                        where id=(select max(id) from zangyo)";
        $stmh=$pdo->prepare($sql);
        $stmh->execute();
        $count=$stmh->rowCount();
      }catch(PDOException $Exception){
        print"エラー：".$Exception->getMessage();
      }
      ?>
      <hr>
      <form method="get" action="zangyo_check.php">
        <button class="btn btn-primary" type="submit" name='action' value='getURL'>URL生成</button>
        <hr>

        <div class="table-responsive">
          <table class="table table-striped table-bordered table-condensed table-responsive text-center" style="table-layout:fixed;">
            <thead>
              <tr>
                <th style="width:50px;"></th>
                <th style="width:150px;">実施日</th>
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
                      <td><?php require("../php_libs/SUM_MONTH.php"); ?></td><!--月間累計-->
                      <td><!--年間累計-->
                        <?php
                        require_once("../php_libs/FUNC_CHANGE_TO_APR1.php");
                        require("../php_libs/SUM_YEAR.php");
                        ?>
                      </td>
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
            <button class="btn btn-primary" type="submit" name='action' value='getURL'>URL生成</button>
            <hr>

          </main>
          <footer class="footer">
            <div class="container">
              <span class="text-muted">Place sticky footer content here.</span>
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


    </body>
    </html>
