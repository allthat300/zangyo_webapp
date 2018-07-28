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
            <a class="dropdown-item" href="report-month.php">月間(部署)</a>
            <a class="dropdown-item" href="report-year.php">年間(部署)</a>
            <a class="dropdown-item" href="report-personal-month.php">月間(個人)</a>
						<a class="dropdown-item" href="report-personal-year.php">年間(個人)</a>
						<a class="dropdown-item" href="report-each-person-month.php">個人別</a>
          </div>
        </li>
      </ul>
			<?php
			if(!isset($_COOKIE['employee_id'])){
				?>
				<form class="form-inline mt-2 mt-md-0" method="post" action="COOKIE.php">
					<input class="form-control mr-sm-2" type="text" placeholder="社員番号" name="cookie_employee_id">
					<button class="btn btn-outline-info my-2 my-sm-0" type="submit" name="log" value="login">ログイン</button>
				</form>
				<?php
			}else{
				?>
				<form class="form-inline mt-2 mt-md-0" method="post" action="COOKIE.php">
					<ul class="navbar-nav mr-auto"><li class="nav-item mr-sm-2 text-light"><?= $_COOKIE['employee_name'] . " さん" ?></li></ul>
					<button class="btn btn-outline-info my-2 my-sm-0" type="submit" name="log" value="logout">ログアウト</button>
				</form>
				<?php
			}
			?>
    </div>
  </nav>

  <main role="main" class="container-fluid">

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
      <h1>残業実績集計(月間/個人)</h1>
      <!-- <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group mr-2">
          <button class="btn btn-sm btn-outline-secondary">Share</button>
          <button class="btn btn-sm btn-outline-secondary">Export</button>
        </div>
        <button class="btn btn-sm btn-outline-secondary dropdown-toggle">
          <span data-feather="calendar"></span>
          This week
        </button>
      </div> -->
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
            <th style="width: 250px" class="text-center">名前</th>
						<th style="width: 250px" class="text-center">名前を部署で絞り込み</th>
						<th style="width: 250px" class="text-center">名前をグループで絞り込み</th>
          </tr>
        </thead>
        <tbody>

          <form name="form1" method="post" action="report-personal-month.php">
            <tr>
              <td class="m-0 p-0">
                <div id="year-month">
                  <div class="form-inline">
                    <div class="input-group date w-100">
                      <input type="text" class="form-control" placeholder="ex)2018-04" name="report_month" autocomplete="off" value="<?php
											if(!empty($_POST['report_month'])){
												echo $_POST['report_month'];
											}else{
												echo date('Y-m');
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
                <select class="form-control" name="report_employee_id">
                  <option value="" <?php if(empty($_POST['report_employee_id'])){echo "selected";} ?>>(指定なし)</option>
                  <?php

									if(!empty($_POST['report_department_id'])){
								    $sql_department = " AND employee.department_id = '" . $_POST['report_department_id'] ."' ";
								  }else{
								    $sql_department = "";
								  }

								  if(!empty($_POST['report_group_id'])){
								    $sql_group = " AND employee.group_id = '" . $_POST['report_group_id'] ."' ";
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
                        if(!empty($_POST['report_employee_id'])){
                          if($_POST['report_employee_id'] == htmlspecialchars($row['employee_id'],ENT_QUOTES)){
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
                        <?=htmlspecialchars($row['department_name'],ENT_QUOTES)?>
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

  <?php
  // print_r($_POST);

  // if(!empty($_POST['report_month'])){
  //   $sql_month = " AND zangyo_date LIKE '" . $_POST['report_month'] ."%'";
  // }else{
  //   $sql_month = "";
  // }

  if(!empty($_POST['report_employee_id'])){
    $sql_employee = " AND employee.employee_id = '" . $_POST['report_employee_id'] ."' ";
  }else{
    $sql_employee = "AND 0 ";
  }

  if(!empty($_POST['report_month'])){
    $first_date = date('Y-m-d', strtotime('first day of ' . $_POST['report_month']));
  }else{
    $first_date = date("Y-m-01");
  }

  if(!empty($_POST['report_month'])){
    $num_date = date('t', strtotime($_POST['report_month']));
  }else{
    $num_date = date("t");
  }

  $array_report[] = ["day" => $first_date, "jisseki" =>"", "sum" =>""];
  for ($i = 1 ; $i < $num_date ; $i++){
    $array_report[]["day"] = date('Y-m-d', strtotime("$first_date + $i days"));
  }

  //print_r($array_report)

  ?>
  <h4>残業実績</h4>
  <table class="table table-striped table-bordered table-condensed">
    <thead>
      <tr>
        <th class="text-center">実施日</th>
        <th class="text-center">実施時間</th>
        <th class="text-center">月間累計</th>
      </tr>
    </thead>
    <tbody>
      <?php
      for($j = 0 ; $j < $num_date ; $j++){

        //各日の実施時間を配列にいれる

        $sql_date_jisseki_start = "AND zangyo.zangyo_date >= '" . $array_report[$j]["day"] . " 00:00:00' ";
        $sql_date_end = "AND zangyo.zangyo_date <= '" . $array_report[$j]["day"] . " 23:59:59' ";

        try{
          $sql_report_jisseki="SELECT sec_to_time(sum(time_to_sec(IFNULL(zangyo.result_time,'00:00:00'))))
          AS report_month
          from (zangyo LEFT OUTER JOIN employee ON zangyo.employee_id = employee.employee_id)
          WHERE 1 "
          . $sql_employee
          . $sql_date_jisseki_start
          . $sql_date_end
          . "ORDER BY zangyo_date DESC";
          $stmh_report_jisseki=$pdo->prepare($sql_report_jisseki);
          $stmh_report_jisseki->execute();
          $count_report_jisseki=$stmh_report_jisseki->rowCount();
        }catch(PDOException $Exception_report_jisseki){
          print"エラー：".$Exception_report_jisseki->getMessage();
        }
        if($count_report_jisseki>0){
          while($row_report_jisseki=$stmh_report_jisseki->fetch(PDO::FETCH_ASSOC)){
            if($row_report_jisseki['report_month'] == ""){
              $array_report[$j]["jisseki"] = "00:00:00";
            }else{
              $array_report[$j]["jisseki"] = htmlspecialchars($row_report_jisseki['report_month'],ENT_QUOTES);
            }
          }
        }


        //月間累計を配列にいれる

        $sql_date_sum_start = "AND zangyo.zangyo_date >= '" . $first_date . " 00:00:00' ";
        $sql_date_end = "AND zangyo.zangyo_date <= '" . $array_report[$j]["day"] . " 23:59:59' ";

        try{
          $sql_report_sum="SELECT sec_to_time(sum(time_to_sec(IFNULL(zangyo.result_time,'00:00:00'))))
          AS report_sum
          from (zangyo LEFT OUTER JOIN employee ON zangyo.employee_id = employee.employee_id)
          WHERE 1 "
          . $sql_employee
          . $sql_date_sum_start
          . $sql_date_end
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
          <td><!--実施日-->
            <?php
            $datetime = new DateTime($array_report[$j]['day']);
            $week = array("日", "月", "火", "水", "木", "金", "土");
            $w = (int)$datetime->format('w');
            echo htmlspecialchars(substr($array_report[$j]['day'],0,10),ENT_QUOTES) . " (" . $week[$w] . ")";
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
          </td><!--月間累計-->
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
  labels: [
    <?php
    for($k = 0 ; $k < $num_date ; $k++){
      echo "'";
      echo date('d',strtotime($array_report[$k]["day"]));
      echo "',";
    }
    ?>
    //   '8/26','8/27','8/28','8/29','8/30','8/31','9/1',
    // '9/2','9/3','9/4','9/5','9/6','9/7','9/8',
    // '9/9','9/10','9/11','9/12','9/13','9/14',
    // '9/15','9/16','9/17','9/18','9/19','9/20','9/21','9/22'
  ],
  datasets: [
    {
      type: 'bar',
      label: '実施時間',
      data: [
        <?php
        for($l = 0 ; $l < $num_date ; $l++){
          echo "'";
          echo (int)substr($array_report[$l]["jisseki"],0,-6) + (int)substr($array_report[$l]["jisseki"],-5,-3)/60;
          echo "',";
        }
        ?>
        //   '0.3','0.1','0.1','0.3','0.4','0.2','0.0',
        // '0.2','0.3','0.11','0.5','0.2','0.5','0.4',
        // '0.0','0.3','0.7','0.3','0.6','0.4','0.9',
        // '0.7','0.4','0.8','0.7','0.4','0.7','0.8'
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
        for($l = 0 ; $l < $num_date ; $l++){
          echo "'";
          echo (int)substr($array_report[$l]["sum"],0,-6) + (int)substr($array_report[$l]["sum"],-5,-3)/60;
          echo "',";
        }
        ?>
        //   '0.155','0.118','0.121','0.068','0.083','0.060','0.067',
        // '0.121','0.121','0.150','0.118','0.097','0.078','0.127',
        // '0.155','0.140','0.101','0.140','0.041','0.093','0.189',
        // '0.146','0.134','0.127','0.116','0.111','0.125','0.116'
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
    text: '月間実績'                //ラベル
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
        '<?=htmlspecialchars(substr($array_report[1]['day'],0,4),ENT_QUOTES)?>年<?=htmlspecialchars(substr($array_report[1]['day'],5,2),ENT_QUOTES)?>月度',  //ラベル
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
