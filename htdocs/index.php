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
        <li class="nav-item active">
          <a class="nav-link" href="index.php">申請</a>
        </li>
        <li class="nav-item">
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



    <div>
      <h1 class="h1 my-3">残業申請</h1>

      <div class="table-responsive">

        <table class="table table-striped table-bordered table-condensed">
          <thead>
            <tr>
              <th style="width: 150px" class="text-center">実施日</th>
              <th style="width: 100px" class="text-center">種別</th>
              <th style="width: 100px" class="text-center">社員番号</th>
              <th class="text-center">申請時間</th>
              <th class="text-center">機種</th>
              <th class="text-center">内容</th>
              <th class="text-center">備考</th>
            </tr>
          </thead>
          <tbody>
            <form name="form1" method="post" action="INSERT.php">
              <tr>
                <td class="m-0 p-0">
                  <!-- <input type="text" class="form-control" placeholder="ex)2018-06-06" name="zangyo_date"> -->
                  <div id="datepicker-default">
                    <div class="form-inline">
                      <div class="input-group date">
                        <input type="text" class="form-control" placeholder="ex)2018-06-06" name="zangyo_date" autocomplete="off">
                        <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                </td>
                <td class="m-0 p-0">
                  <select class="form-control" name="zangyo_category">
                    <option value="1">残業</option>
                    <option value="2">早出</option>
                    <option value="3">FLEX</option>
                    <option value="4">休出</option>
                    <option value="5">代休</option>
                  </select>
                </td>
                <td class="m-0 p-0"><input type="text" class="form-control" placeholder="社員番号" name="employee_id"></td>
                <td class="m-0 p-0"><input type="text" class="form-control" placeholder="ex)1時間→1:00" name="zangyo_time"></td>
                <td class="m-0 p-0"><input type="text" class="form-control" placeholder="EX-****" name="model_name"></td>
                <td class="m-0 p-0"><input type="text" class="form-control" placeholder="内容" name="zangyo_detail"></td>
                <td class="m-0 p-0"><input type="text" class="form-control" placeholder="備考" name="zangyo_remarks"></td>
              </tr>

            </tbody>
          </table>

        </div>
        <button class="btn btn-primary btn-lg btn-block" type="submit" name='application' value='send'>送信</button>
      </form>
    </div>
    <hr>


    <h4>最新情報</h4>
    <table class="table table-striped table-bordered table-condensed text-center">
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
          <th>確認</th>
          <th>機種</th>
          <th>内容</th>
          <th>備考</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <?php
          try{
            $sql="SELECT zangyo.zangyo_date,zangyo.app_time,zangyo.employee_id,employee.employee_name,case_id.category,zangyo.project,zangyo.project_detail,zangyo.boss_check,zangyo.remarks,zangyo.result_time,department.department_name,work_group.group_name
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
          if($count>0){
            while($row=$stmh->fetch(PDO::FETCH_ASSOC)){
              ?>
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
    </main>

    <footer class="footer">
      <div class="container text-center">
        <span class="text-muted">残業管理システム 2018 Yusuke.Kishi</span>
      </div>
    </footer>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script> -->

    <script src="https://code.jquery.com/jquery-1.10.2.min.js" type="text/javascript" language="javascript"></script>
    <script>window.jQuery || document.write('<script src="/dist/js/jquery-slim.min.js"><\/script>')</script>
    <script src="/dist/js/popper.min.js"></script>
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
