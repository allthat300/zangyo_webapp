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
  <link href="/dist/css/dashboard.css" rel="stylesheet">
</head>

<body>
  <nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">  <!--トップナビゲーションバー-->
    <a class="navbar-brand col-sm-1 col-md-1 mr-0" href="index.html">残業管理</a>  <!--ページ最上部へ移動-->
  </nav>

  <div class="container-fluid"> <!-- コンテナ：フルサイズ -->
    <div class="row">　<!-- 行 -->
      <nav class="col-md-1 d-none d-md-block bg-light sidebar"> <!-- 列。サイドバーに該当。md(768px)以下では非表示。背景は白。 -->
        <div class="sidebar-sticky">
          <ul class="nav flex-column">
            <li class="nav-item">
              <a class="nav-link active" href="zangyo-application.html">
                <span data-feather="upload"></span>
                申請 <span class="sr-only">(current)</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="jisseki-input.html">
                <span data-feather="file"></span>
                実績入力
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="boss-check.html">
                <span data-feather="check-square"></span>
                承認
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">
                <span data-feather="users"></span>
                社員追加
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">
                <span data-feather="edit"></span>
                部署変更
              </a>
            </li>
          </ul>

          <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>レポート</span>
            <!-- <a class="d-flex align-items-center text-muted" href="#">
            <span data-feather="plus-circle"></span>
          </a> -->
        </h6>
        <ul class="nav flex-column mb-2">
          <li class="nav-item">
            <a class="nav-link" href="#">
              <span data-feather="file-text"></span>
              期間
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
              <span data-feather="file-text"></span>
              個人
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
              <span data-feather="file-text"></span>
              部署
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
              <span data-feather="file-text"></span>
              カスタム
            </a>
          </li>
        </ul>
      </div>
    </nav>

    <main role="main" class="col-md-11 ml-sm-auto col-lg-11 px-4">



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
              <form name="form1" method="post" action="">
                <tr>
                  <td class="m-0 p-0"><input type="text" class="form-control" placeholder="ex)2018-06-06" name="zangyo_date"></td>
                  <td class="m-0 p-0">
                    <select class="form-control" name="zangyo_category">
                      <option value="1">残業</option>
                      <option value="2">早出</option>
                      <option value="3">休出</option>
                      <option value="4">代休</option>
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
      <?php require("../php_libs/INSERT.php"); ?>
      <hr>


      <h4>最新情報</h4>
      <table class="table table-striped table-bordered table-condensed">
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
              $sql="SELECT zangyo.zangyo_date,zangyo.app_time,employee.employee_name,case_id.category,zangyo.project,zangyo.project_detail,zangyo.remarks,zangyo.result_time
              from ((zangyo LEFT OUTER JOIN employee ON zangyo.employee_id = employee.employee_id)
              LEFT OUTER JOIN case_id ON zangyo.case_id = case_id.case_id)
              ORDER BY id DESC
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
                <td><?php

                $datetime = new DateTime($row['zangyo_date']);
                $week = array("日", "月", "火", "水", "木", "金", "土");
                $w = (int)$datetime->format('w');
                echo htmlspecialchars($row['zangyo_date'],ENT_QUOTES) . " (" . $week[$w] . ")"; 
                  ?></td> <!--<?php /* <? PHPの式 ?>は<? echo PHPの式 ?>の省略形 */ ?>-->
                <td><?=htmlspecialchars($row['category'],ENT_QUOTES)?></td>
                <td><?=htmlspecialchars($row['employee_name'],ENT_QUOTES)?></td>
                <td></td>
                <td></td>
                <td><?=htmlspecialchars(substr($row['app_time'],0,-3),ENT_QUOTES)?></td>
                <td><?php
                if($row['result_time'] == "00:00:00"){
                  echo "";
                }else {
                  echo htmlspecialchars($row['result_time'],ENT_QUOTES);
                }
                ?></td>
                <td></td>
                <td></td>
                <td></td>
                <td><?=htmlspecialchars($row['project'],ENT_QUOTES)?></td>
                <td><?=htmlspecialchars($row['project_detail'],ENT_QUOTES)?></td>
                <td><?=htmlspecialchars($row['remarks'],ENT_QUOTES)?></td>
              </tr>
              <?php
            }
          }
          ?>
        </tbody>
      </table>

    </main>
    </div>
  </div>

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
