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
      <h1 class="h2">Dashboard</h1>
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

    <canvas class="my-4 w-100" id="myChart" width="900" height="380"></canvas>

    <h2>Section title</h2>
    <div class="table-responsive">
      <table class="table table-striped table-sm">
        <thead>
          <tr>
            <th>#</th>
            <th>Header</th>
            <th>Header</th>
            <th>Header</th>
            <th>Header</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1,001</td>
            <td>Lorem</td>
            <td>ipsum</td>
            <td>dolor</td>
            <td>sit</td>
          </tr>
          <tr>
            <td>1,002</td>
            <td>amet</td>
            <td>consectetur</td>
            <td>adipiscing</td>
            <td>elit</td>
          </tr>
          <tr>
            <td>1,003</td>
            <td>Integer</td>
            <td>nec</td>
            <td>odio</td>
            <td>Praesent</td>
          </tr>
          <tr>
            <td>1,003</td>
            <td>libero</td>
            <td>Sed</td>
            <td>cursus</td>
            <td>ante</td>
          </tr>
          <tr>
            <td>1,004</td>
            <td>dapibus</td>
            <td>diam</td>
            <td>Sed</td>
            <td>nisi</td>
          </tr>
          <tr>
            <td>1,005</td>
            <td>Nulla</td>
            <td>quis</td>
            <td>sem</td>
            <td>at</td>
          </tr>
          <tr>
            <td>1,006</td>
            <td>nibh</td>
            <td>elementum</td>
            <td>imperdiet</td>
            <td>Duis</td>
          </tr>
          <tr>
            <td>1,007</td>
            <td>sagittis</td>
            <td>ipsum</td>
            <td>Praesent</td>
            <td>mauris</td>
          </tr>
          <tr>
            <td>1,008</td>
            <td>Fusce</td>
            <td>nec</td>
            <td>tellus</td>
            <td>sed</td>
          </tr>
          <tr>
            <td>1,009</td>
            <td>augue</td>
            <td>semper</td>
            <td>porta</td>
            <td>Mauris</td>
          </tr>
          <tr>
            <td>1,010</td>
            <td>massa</td>
            <td>Vestibulum</td>
            <td>lacinia</td>
            <td>arcu</td>
          </tr>
          <tr>
            <td>1,011</td>
            <td>eget</td>
            <td>nulla</td>
            <td>Class</td>
            <td>aptent</td>
          </tr>
          <tr>
            <td>1,012</td>
            <td>taciti</td>
            <td>sociosqu</td>
            <td>ad</td>
            <td>litora</td>
          </tr>
          <tr>
            <td>1,013</td>
            <td>torquent</td>
            <td>per</td>
            <td>conubia</td>
            <td>nostra</td>
          </tr>
          <tr>
            <td>1,014</td>
            <td>per</td>
            <td>inceptos</td>
            <td>himenaeos</td>
            <td>Curabitur</td>
          </tr>
          <tr>
            <td>1,015</td>
            <td>sodales</td>
            <td>ligula</td>
            <td>in</td>
            <td>libero</td>
          </tr>
        </tbody>
      </table>
    </div>

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

<!-- Graphs -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
<script>
  var ctx = document.getElementById("myChart");
  var myChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
      datasets: [{
        data: [15339, 21345, 18483, 24003, 23489, 24092, 12034],
        lineTension: 0,
        backgroundColor: 'transparent',
        borderColor: '#007bff',
        borderWidth: 4,
        pointBackgroundColor: '#007bff'
      }]
    },
    options: {
      scales: {
        yAxes: [{
          ticks: {
            beginAtZero: false
          }
        }]
      },
      legend: {
        display: false,
      }
    }
  });
</script>

</body>
</html>
