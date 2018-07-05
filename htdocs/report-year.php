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
            <a class="dropdown-item" href="report-month.php">月間</a>
            <a class="dropdown-item" href="report-year.php">年間</a>
            <a class="dropdown-item" href="report-employee.php">*****</a>
          </div>
        </li>
      </ul>
    </div>
  </nav>

  <main role="main" class="container-fluid">

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
      <h1>残業実績集計(年間)</h1>
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
            <th style="width: 200px" class="text-center">対象年</th>
            <th style="width: 250px" class="text-center">部署</th>
            <th style="width: 250px" class="text-center">グループ</th>
          </tr>
        </thead>
        <tbody>

          <form name="form1" method="post" action="report-year.php">
            <tr>
              <td class="m-0 p-0">
                <div id="year-month">
                  <div class="form-inline">
                    <div class="input-group date w-100">
                      <input type="text" class="form-control" placeholder="ex)2018" name="report_year" autocomplete="off" value="<?php if(!empty($_POST['report_year'])){echo $_POST['report_year'];}?>">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </td>
              <td class="m-0 p-0">
                <select class="form-control" name="report_department_id">
                  <option value="" <?php if(empty($_POST['report_department_id'])){echo "selected";} ?>>(指定なし)</option>
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
                        if(!empty($_POST['report_department_id'])){
                          if($_POST['report_department_id'] == htmlspecialchars($row['department_id'],ENT_QUOTES)){
                            echo "selected";
                          }
                        }
                        ?>
                        >
                        <?php
                        echo htmlspecialchars($row['department_name'],ENT_QUOTES);
                        ?>
                      </option>
                      <?php
                    }
                  }
                  ?>
                </select>
              </td>
              <td class="m-0 p-0">
                <select class="form-control" name="report_group_id" value="
                <?php
                if(!empty($_POST['report_group_id'])){
                  echo $_POST['report_group_id'];
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
                      if(!empty($_POST['report_group_id'])){
                        if($_POST['report_group_id'] == htmlspecialchars($row['group_id'],ENT_QUOTES)){
                          echo "selected";
                        }
                      }
                      ?>
                      >
                      <?php
                      echo htmlspecialchars($row['group_name'],ENT_QUOTES);
                      ?>
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

  <?php
  // print_r($_POST);

  // if(!empty($_POST['report_year'])){
  //   $sql_year = " AND zangyo_date LIKE '" . $_POST['report_year'] ."%'";
  // }else{
  //   $sql_year = "";
  // }

  if(!empty($_POST['report_department_id'])){
    $sql_department = " AND department.department_id = '" . $_POST['report_department_id'] ."' ";
  }else{
    $sql_department = "";
  }

  if(!empty($_POST['report_group_id'])){
    $sql_group = " AND work_group.group_id = '" . $_POST['report_group_id'] ."' ";
  }else{
    $sql_group = "";
  }

  if(!empty($_POST['report_year'])){
    $first_date = (int)$_POST['report_year'] ."-04-01";
  }else{
    if(date("Y") == "1" || date("Y") == "2" || date("Y") == "3"){
      $first_date = (int)date("Y") - 1 ."-04-01";
    }else{
      $first_date = (int)date("Y") ."-04-01";
    }
  }


  $array_report[] = ["month" => substr($first_date,0,-6) . "-04" , "jisseki" =>"", "sum" =>""];
  $array_report[1]["month"] = substr($first_date,0,-6) . "-05";
  $array_report[2]["month"] = substr($first_date,0,-6) . "-06";
  $array_report[3]["month"] = substr($first_date,0,-6) . "-07";
  $array_report[4]["month"] = substr($first_date,0,-6) . "-08";
  $array_report[5]["month"] = substr($first_date,0,-6) . "-09";
  $array_report[6]["month"] = substr($first_date,0,-6) . "-10";
  $array_report[7]["month"] = substr($first_date,0,-6) . "-11";
  $array_report[8]["month"] = substr($first_date,0,-6) . "-12";
  $array_report[9]["month"] = (int)substr($first_date,0,-6) + 1 . "-01";
  $array_report[10]["month"] = (int)substr($first_date,0,-6) + 1 . "-02";
  $array_report[11]["month"] = (int)substr($first_date,0,-6) + 1 . "-03";

  // print_r($array_report);

  ?>
  <h4>残業実績</h4>
  <table class="table table-striped table-bordered table-condensed">
    <thead>
      <tr>
        <th class="text-center">実施月</th>
        <th class="text-center">実施時間</th>
        <th class="text-center">年間累計</th>
      </tr>
    </thead>
    <tbody>
      <?php
      for($j = 0 ; $j < 12 ; $j++){

        //各月の実施時間を配列にいれる

        $year_start = array(
          substr($first_date,0,-6) . "-04-01 00:00:00" ,
          substr($first_date,0,-6) . "-05-01 00:00:00" ,
          substr($first_date,0,-6) . "-06-01 00:00:00" ,
          substr($first_date,0,-6) . "-07-01 00:00:00" ,
          substr($first_date,0,-6) . "-08-01 00:00:00" ,
          substr($first_date,0,-6) . "-09-01 00:00:00" ,
          substr($first_date,0,-6) . "-10-01 00:00:00" ,
          substr($first_date,0,-6) . "-11-01 00:00:00" ,
          substr($first_date,0,-6) . "-12-01 00:00:00" ,
          (int)substr($first_date,0,-6) + 1 . "-01-01 00:00:00" ,
          (int)substr($first_date,0,-6) + 1 . "-02-01 00:00:00" ,
          (int)substr($first_date,0,-6) + 1 . "-03-01 00:00:00" ,
          (int)substr($first_date,0,-6) + 1 . "-04-01 00:00:00");

          $sql_year_start = "AND zangyo.zangyo_date >= '" . $year_start[$j] . "' ";
          $sql_year_end = "AND zangyo.zangyo_date < '" . $year_start[$j + 1] . "' ";

          try{
            $sql_report_jisseki="SELECT sec_to_time(sum(time_to_sec(IFNULL(zangyo.result_time,'00:00:00'))))
            AS report_year
            from (((zangyo LEFT OUTER JOIN employee ON zangyo.employee_id = employee.employee_id)
            LEFT OUTER JOIN department ON employee.department_id = department.department_id)
            LEFT OUTER JOIN work_group ON employee.group_id = work_group.group_id)
            WHERE 1 "
            . $sql_department
            . $sql_group
            . $sql_year_start
            . $sql_year_end
            . "ORDER BY zangyo_date DESC";
            $stmh_report_jisseki=$pdo->prepare($sql_report_jisseki);
            $stmh_report_jisseki->execute();
            $count_report_jisseki=$stmh_report_jisseki->rowCount();
          }catch(PDOException $Exception_report_jisseki){
            print"エラー：".$Exception_report_jisseki->getMessage();
          }
          if($count_report_jisseki>0){
            while($row_report_jisseki=$stmh_report_jisseki->fetch(PDO::FETCH_ASSOC)){
              if($row_report_jisseki['report_year'] == ""){
                $array_report[$j]["jisseki"] = "00:00:00";
              }else{
                $array_report[$j]["jisseki"] = htmlspecialchars($row_report_jisseki['report_year'],ENT_QUOTES);
              }
            }
          }


          //年間累計を配列にいれる

          $sql_year_sum_start = "AND zangyo.zangyo_date >= '" . $year_start[0] . "' ";
          $sql_year_sum_end = "AND zangyo.zangyo_date < '" . $year_start[$j+1] . "' ";

          try{
            $sql_report_sum="SELECT sec_to_time(sum(time_to_sec(IFNULL(zangyo.result_time,'00:00:00'))))
            AS report_sum
            from (((zangyo LEFT OUTER JOIN employee ON zangyo.employee_id = employee.employee_id)
            LEFT OUTER JOIN department ON employee.department_id = department.department_id)
            LEFT OUTER JOIN work_group ON employee.group_id = work_group.group_id)
            WHERE 1 "
            . $sql_department
            . $sql_group
            . $sql_year_sum_start
            . $sql_year_sum_end
            . "ORDER BY zangyo_date DESC";
            $stmh_report_sum=$pdo->prepare($sql_report_sum);
            $stmh_report_sum->execute();
            $count_report_sum=$stmh_report_sum->rowCount();
          }catch(PDOException $Exception_report_sum){
            print"エラー：".$Exception_report_sum->getMessage();
          }
          if($count_report_sum>0){
            while($row_report_sum=$stmh_report_sum->fetch(PDO::FETCH_ASSOC)){
              if($row_report_sum['report_sum'] == ""){
                $array_report[$j]["sum"] = "00:00:00";
              }else{
                $array_report[$j]["sum"] = htmlspecialchars($row_report_sum['report_sum'],ENT_QUOTES);
              }
            }
          }


          ?>
          <tr>
            <td><!--実施月-->
              <?php
              echo htmlspecialchars($array_report[$j]['month'],ENT_QUOTES);
              ?>
            </td>
            <td><!--実施時間-->
              <?php
              echo htmlspecialchars(substr($array_report[$j]['jisseki'],0,-3),ENT_QUOTES);
              ?>
            </td>
            <td>
              <?php
              echo htmlspecialchars(substr($array_report[$j]['sum'],0,-3),ENT_QUOTES);
              ?>
            </td><!--年間間累計-->
          </tr>
          <?php
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
    format: "yyyy",
    language: 'ja',
    autoclose: true,
    todayBtn: 'linked',
    defaultDate: 0,
    minViewMode: 'years'
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
  labels: [
    '04','05','06','07','08','09','10',
    '11','12','01','02','03'
  ],
  datasets: [
    {
      type: 'bar',
      label: '実施時間',
      data: [
        <?php
        for($l = 0 ; $l < 12 ; $l++){
          echo "'";
          echo (int)substr($array_report[$l]["jisseki"],0,-6) + (int)substr($array_report[$l]["jisseki"],-5,-3)/60;
          echo "',";
        }
        ?>
      ],
      borderColor : "rgba(54,164,235,0.8)",
      backgroundColor : "rgba(54,164,235,0.5)",
      yAxisID: "y-axis-1",
    },
    {
      type: 'line',
      label: '累計時間',
      data: [
        <?php
        for($l = 0 ; $l < 12 ; $l++){
          echo "'";
          echo (int)substr($array_report[$l]["sum"],0,-6) + (int)substr($array_report[$l]["sum"],-5,-3)/60;
          echo "',";
        }
        ?>
      ],
      borderColor : "rgba(254,97,132,0.8)",
      pointBackgroundColor    : "rgba(254,97,132,0.8)",
      fill: false,
      yAxisID: "y-axis-2",// 追加
      lineTension: 0,
    },

  ],
};
</script>

<script>
var complexChartOption = {
  responsive: true,
  legend: {                          //凡例設定
    display: true,                 //表示設定
    fontSize:18
  },
  title: {                           //タイトル設定
    display: true,                 //表示設定
    fontSize: 20,                  //フォントサイズ
    text: '年間実績'                //ラベル
  },
  scales: {
    yAxes: [{
      id: "y-axis-1",
      type: "linear",
      position: "left",
      scaleLabel: {              //軸ラベル設定
        display: true,          //表示設定
        labelString: '実施時間',  //ラベル
        fontSize: 18               //フォントサイズ
      },
      ticks: {
        fontSize:18
        // max: 0.2,
        // min: 0,
        // stepSize: 0.1
      },
      gridLines: {
        drawOnChartArea: false,
      },
    },{
      id: "y-axis-2",
      type: "linear",
      position: "right",
      scaleLabel: {              //軸ラベル設定
        display: true,          //表示設定
        labelString: '累計時間',  //ラベル
        fontSize: 18               //フォントサイズ
      },
      ticks: {
        fontSize:18
        // max: 0.2,
        // min: 0,
        // stepSize: 0.1
      },
    }],
    xAxes: [{                         //x軸設定
      display: true,                //表示設定
      // barPercentage: 0.4,           //棒グラフ幅
      // categoryPercentage: 0.4,      //棒グラフ幅
      scaleLabel: {                 //軸ラベル設定
        display: true,             //表示設定
        labelString:
        '<?php echo substr($first_date,0,-6) ?>年度',  //ラベル
        fontSize: 18               //フォントサイズ
      },
      ticks: {
        fontSize: 18             //フォントサイズ
      },
    }],
  }
};
</script>


</body>
</html>
