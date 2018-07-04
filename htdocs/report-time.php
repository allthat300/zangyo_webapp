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
        <li class="nav-item dropdown active">
          <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">レポート</a>
          <div class="dropdown-menu" aria-labelledby="dropdown04">
            <a class="dropdown-item" href="report-time.php">期間</a>
            <a class="dropdown-item" href="report-department.php">*****</a>
            <a class="dropdown-item" href="report-employee.php">*****</a>
          </div>
        </li>
      </ul>
    </div>
  </nav>

  <main role="main" class="container-fluid">

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
      <h1>残業実績集計</h1>
      <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group mr-2">
          <button class="btn btn-sm btn-outline-secondary">Share</button>
          <button class="btn btn-sm btn-outline-secondary">Export</button>
        </div>
        <button class="btn btn-sm btn-outline-secondary dropdown-toggle">
          <span data-feather="calendar"></span>
          This week
        </button>
      </div>
    </div>
    <div class="container">


      <canvas class="my-4 w-100 mx-auto" id="myChart" width="900" height="380"></canvas>


    </div>

    <div class="border-top border-bottom py-2 my-2">
      <h4>検索条件</h4>
      <table class="table table-striped table-bordered table-condensed">
        <thead>
          <tr>
            <th style="width: 200px" class="text-center">対象月</th>
            <th style="width: 250px" class="text-center">部署</th>
            <th style="width: 250px" class="text-center">グループ</th>
          </tr>
        </thead>
        <tbody>

          <form name="form1" method="post" action="#">
            <tr>
              <td class="m-0 p-0">
                <div id="year-month">
                  <div class="form-inline">
                    <div class="input-group date w-100">
                      <input type="text" class="form-control" placeholder="ex)2018-04" name="search_month" autocomplete="off" value="<?php if(!empty($_POST['search_month'])){echo $_POST['search_month'];}?>">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                    </div>
                  </div>
                </div>
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
  </div>

<?php
  print_r($_POST);

  if(!empty($_POST['search_month'])){
    $sql_month = " AND zangyo_date LIKE '" . $_POST['search_month'] ."%'";
  }else{
    $sql_month = "";
  }

  if(!empty($_POST['search_department_id'])){
    $sql_department = " AND department.department_id = '" . $_POST['search_department_id'] ."'";
  }else{
    $sql_department = "";
  }

  if(!empty($_POST['search_group_id'])){
    $sql_group = " AND work_group.group_id = '" . $_POST['search_group_id'] ."'";
  }else{
    $sql_group = "";
  }

  $sql_where = "WHERE 1 " . $sql_month . $sql_department . $sql_group;

    try{
      $sql="SELECT zangyo.id,zangyo.zangyo_date,zangyo.employee_id,employee.employee_name,zangyo.result_time,employee.department_id,department.department_name,employee.group_id,work_group.group_name
      from (((zangyo LEFT OUTER JOIN employee ON zangyo.employee_id = employee.employee_id)
      LEFT OUTER JOIN department ON employee.department_id = department.department_id)
      LEFT OUTER JOIN work_group ON employee.group_id = work_group.group_id)"
      . $sql_where .
      "ORDER BY zangyo_date DESC";
      //                        where id=(select max(id) from zangyo)";
      $stmh=$pdo->prepare($sql);
      $stmh->execute();
      $count=$stmh->rowCount();
    }catch(PDOException $Exception){
      print"エラー：".$Exception->getMessage();
    }

    ?>

          <table class="table table-striped table-bordered table-condensed">
          <thead>
            <tr>
              <th>実施日</th>
              <th>部署</th>
              <th>グループ</th>
              <th>実施時間</th>
              <th>月間累計</th>
              <th>年間累計</th>
            </tr>
          </thead>
          <tbody>

            <tr>
              <?php
              if($count>0){
                while($row=$stmh->fetch(PDO::FETCH_ASSOC)){
                  ?>
                  <td><!--実施日-->
                    <?php

                  $datetime = new DateTime($row['zangyo_date']);
                  $week = array("日", "月", "火", "水", "木", "金", "土");
                  $w = (int)$datetime->format('w');
                  echo htmlspecialchars(substr($row['zangyo_date'],0,10),ENT_QUOTES) . " (" . $week[$w] . ")";
                  ?></td> <!--<?php /* <? PHPの式 ?>は<? echo PHPの式 ?>の省略形 */ ?>-->
                  <td><?=htmlspecialchars($row['department_name'],ENT_QUOTES)?></td><!--部署-->
                  <td><?=htmlspecialchars($row['group_name'],ENT_QUOTES)?></td><!--グループ-->
                  <td><!--実施時間-->
                    <?php
                    if(Is_null($row['result_time']) == TRUE){
                      echo "";
                    }else {
                      echo htmlspecialchars(substr($row['result_time'],0,-3),ENT_QUOTES);
                    }
                    ?></td>
                    <td>


                      <?php
                    try{
                      $sql_sum_month="SELECT sec_to_time(sum(time_to_sec(IFNULL(result_time,'00:00:00')))) AS sum_month_time from zangyo
                      where zangyo_date BETWEEN '".substr($row['zangyo_date'],0,8)."01 00:00:00' AND '" . $row['zangyo_date'] . "' "
                      . "AND employee_id = '" . $row['employee_id'] . "' "
                      ."ORDER BY zangyo_date,case_id desc"; //""内の''や""はよくわからないので.で連結
                      $stmh_sum_month=$pdo->prepare($sql_sum_month);
                      $stmh_sum_month->execute();
                      $count_sum_month=$stmh_sum_month->rowCount();
                    }catch(PDOException $Exception_sum_month){
                      print"エラー：".$Exception_sum_month->getMessage();
                    }
                    //echo $sql_sum_month;
                    if($count_sum_month=0){
                      echo "0";
                    }else{
                      while($row_sum_month=$stmh_sum_month->fetch(PDO::FETCH_ASSOC)){
                        echo htmlspecialchars(substr($row_sum_month['sum_month_time'],0,5),ENT_QUOTES);
                      }
                    }
                    ?>
                  </td><!--月間累計-->
                    <td></td><!--年間累計-->
                  </tr>
                  <?php
                }
              }
              ?>
            </tbody>
          </table>

          <?php

          if(!empty($_POST['search_month'])){
            $first_date = date('Y-m-d', strtotime('first day of ' . $_POST['search_month']));
          }else{
            $first_date = date("Y-m-01");
          }

          if(!empty($_POST['search_month'])){
            $last_date = date('Y-m-d', strtotime('last day of ' . $_POST['search_month']));
          }else{
            $last_date = date("Y-m-t");
          }
          echo $first_date;
          echo $last_date;
          ?>

          <table class="table table-striped table-bordered table-condensed">
          <thead>
            <tr>
              <th>実施日</th>
              <th>実施時間</th>
              <th>月間累計</th>
              <th>年間累計</th>
            </tr>
          </thead>
          <tbody>

            <tr>
                  <td><!--実施日-->
                    <?php

                  $datetime = new DateTime($array_days[]);
                  $week = array("日", "月", "火", "水", "木", "金", "土");
                  $w = (int)$datetime->format('w');
                  echo htmlspecialchars(substr($row['zangyo_date'],0,10),ENT_QUOTES) . " (" . $week[$w] . ")";
                  ?></td> <!--<?php /* <? PHPの式 ?>は<? echo PHPの式 ?>の省略形 */ ?>-->
                  <td><!--実施時間-->
                    <?php
                    if(Is_null($row['result_time']) == TRUE){
                      echo "";
                    }else {
                      echo htmlspecialchars(substr($row['result_time'],0,-3),ENT_QUOTES);
                    }
                    ?></td>
                    <td>


                      <?php
                    try{
                      $sql_sum_month="SELECT sec_to_time(sum(time_to_sec(IFNULL(result_time,'00:00:00')))) AS sum_month_time from zangyo
                      where zangyo_date BETWEEN '".substr($row['zangyo_date'],0,8)."01 00:00:00' AND '" . $row['zangyo_date'] . "' "
                      . "AND employee_id = '" . $row['employee_id'] . "' "
                      ."ORDER BY zangyo_date,case_id desc"; //""内の''や""はよくわからないので.で連結
                      $stmh_sum_month=$pdo->prepare($sql_sum_month);
                      $stmh_sum_month->execute();
                      $count_sum_month=$stmh_sum_month->rowCount();
                    }catch(PDOException $Exception_sum_month){
                      print"エラー：".$Exception_sum_month->getMessage();
                    }
                    //echo $sql_sum_month;
                    if($count_sum_month=0){
                      echo "0";
                    }else{
                      while($row_sum_month=$stmh_sum_month->fetch(PDO::FETCH_ASSOC)){
                        echo htmlspecialchars(substr($row_sum_month['sum_month_time'],0,5),ENT_QUOTES);
                      }
                    }
                    ?>
                  </td><!--月間累計-->
                    <td></td><!--年間累計-->
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
      $('#year-month .date').datepicker({
        format: "yyyy-mm",
        language: 'ja',
        autoclose: true,
        todayBtn: 'linked',
        defaultDate: 0,
        minViewMode: 'months'
      });

    });
  </script>


  <!-- Graphs -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
  <script>
  window.onload = function() {
    ctx = document.getElementById("myChart").getContext("2d");
    window.myBar = new Chart(ctx, {
      type: 'bar',
      data: barChartData,
      options: complexChartOption
    });
  };
</script>



<script>
// とある4週間分のデータログ
var barChartData = {
  labels: ['8/26','8/27','8/28','8/29','8/30','8/31','9/1',
  '9/2','9/3','9/4','9/5','9/6','9/7','9/8',
  '9/9','9/10','9/11','9/12','9/13','9/14',
  '9/15','9/16','9/17','9/18','9/19','9/20','9/21','9/22'
],
datasets: [
  {
    type: 'line',
    label: 'sample-line',
    data: ['0.155','0.118','0.121','0.068','0.083','0.060','0.067',
    '0.121','0.121','0.150','0.118','0.097','0.078','0.127',
    '0.155','0.140','0.101','0.140','0.041','0.093','0.189',
    '0.146','0.134','0.127','0.116','0.111','0.125','0.116'
  ],
  borderColor : "rgba(254,97,132,0.8)",
  pointBackgroundColor    : "rgba(254,97,132,0.8)",
  fill: false,
  yAxisID: "y-axis-1",// 追加
  lineTension: 0,
},
{
  type: 'bar',
  label: 'sample-bar',
  data: ['0.3','0.1','0.1','0.3','0.4','0.2','0.0',
  '0.2','0.3','0.11','0.5','0.2','0.5','0.4',
  '0.0','0.3','0.7','0.3','0.6','0.4','0.9',
  '0.7','0.4','0.8','0.7','0.4','0.7','0.8'
],
borderColor : "rgba(54,164,235,0.8)",
backgroundColor : "rgba(54,164,235,0.5)",
yAxisID: "y-axis-2",
},
],
};
</script>

<script>
var complexChartOption = {
  responsive: true,
  scales: {
    yAxes: [{
      id: "y-axis-1",
      type: "linear",
      position: "left",
      ticks: {
        max: 0.2,
        min: 0,
        stepSize: 0.1
      },
    }, {
      id: "y-axis-2",
      type: "linear",
      position: "right",
      ticks: {
        max: 1.5,
        min: 0,
        stepSize: .5
      },
      gridLines: {
        drawOnChartArea: false,
      },
    }],
  }
};
</script>


</body>
</html>
