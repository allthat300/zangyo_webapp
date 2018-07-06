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
        <li class="nav-item dropdown active">
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
          </div>
        </li>
      </ul>
    </div>
  </nav>

  <main role="main" class="container-fluid">

    <h1 class="h1 my-3">社員追加</h1>

    <div class="table-responsive">
      <table class="table table-striped table-bordered table-condensed">
        <thead>
          <tr>
            <th style="width: 150px" class="text-center">社員番号</th>
            <th style="width: 100px" class="text-center">名前</th>
            <th style="width: 100px" class="text-center">部署</th>
            <th style="width: 100px" class="text-center">グループ</th>
          </tr>
        </thead>
        <tbody>
          <form name="form1" method="post" action="EMPLOYEE_INSERT.php">
            <tr>
              <td class="m-0 p-0"><input type="text" class="form-control" placeholder="社員番号" name="employee_id"></td>
              <td class="m-0 p-0"><input type="text" class="form-control" placeholder="社員名" name="employee_name"></td>
              <td class="m-0 p-0">
                <select class="form-control" name="department_id">
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
                      <option value="<?=htmlspecialchars($row['department_id'],ENT_QUOTES)?>"><?=htmlspecialchars($row['department_name'],ENT_QUOTES)?></option>
                      <?php
                    }
                  }
                  ?>
                </select>
              </td>
              <td class="m-0 p-0">
                <select class="form-control" name="group_id">
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
                      <option value="<?=htmlspecialchars($row['group_id'],ENT_QUOTES)?>"><?=htmlspecialchars($row['group_name'],ENT_QUOTES)?></option>
                      <?php
                    }
                  }
                  ?>
                </select>
              </td>
            </tr>

          </tbody>
        </table>
        <button class="btn btn-primary btn-lg" type="submit" name='add-employee' value='send'>登録</button>
      </form>
    </div>
    <hr>
    <?php
    //require("../php_libs/EMPLOYEE_INSERT.php");
    ?>
    <hr>
    <h4>メンバー一覧</h4>
    <table class="table table-striped table-bordered table-condensed">
      <thead>
        <tr>
          <th>社員番号</th>
          <th>名前</th>
          <th>部署</th>
          <th>グループ</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <?php
          try{
            $sql="SELECT employee.employee_id,employee.employee_name,department.department_name,work_group.group_name
            from ((employee LEFT OUTER JOIN department ON employee.department_id = department.department_id)
            LEFT OUTER JOIN work_group ON employee.group_id = work_group.group_id)
            ORDER BY employee_id
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
              <td><?=htmlspecialchars($row['employee_id'],ENT_QUOTES)?></td>
              <td><?=htmlspecialchars($row['employee_name'],ENT_QUOTES)?></td>
              <td><?=htmlspecialchars($row['department_name'],ENT_QUOTES)?></td>
              <td><?=htmlspecialchars($row['group_name'],ENT_QUOTES)?></td>
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


</body>
</html>
