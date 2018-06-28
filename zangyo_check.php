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

  <!-- for bootstrap-datepicker -->
  <!-- <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet"> -->
  <link rel="stylesheet" type="text/css" href="/dist/css/bootstrap-datepicker.min.css">

</head>

<body>
  <nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">  <!--トップナビゲーションバー-->
    <a class="navbar-brand col-sm-1 col-md-1 mr-0" href="index.php">残業管理</a>  <!--ページ最上部へ移動-->
  </nav>

  <div class="container-fluid"> <!-- コンテナ：フルサイズ -->
    <div class="row px-2">　<!-- 行 -->


      <main role="main">

        <h1 class="h1 my-3">
          <?php
          if($_GET['action'] == "edit")
          {
            echo "申請内容編集";
          }elseif($_GET['action'] == "delete")
          {
            echo "削除";
          }elseif($_GET['action'] == "getURL")
          {
            echo "確認用URL";
          }
          ?>
        </h1>
        <?php

        // echo $_GET['action'];
        // echo "<BR>";
        // print_r($_GET['id']);
        // echo "<BR>";
        // echo $_GET['id'][0];

        if($_GET['action'] == "edit")

        //********************編集の処理********************
        {

        }
        elseif($_GET['action'] == "delete")
        //********************削除の処理********************
        {
          foreach($_GET['id'] as $delete_id)
          {
            try{
              $pdo->beginTransaction(); //トランザクション開始
              $sql = "DELETE FROM zangyo WHERE id IN (:id)";
              $stmh = $pdo->prepare($sql);  //prepareメソッドで各テーブル名(date,case_id...)に対しパラメータ(:date,:case_id...)を与える。

              $stmh->bindValue(':id',$delete_id,PDO::PARAM_STR); //prepareメソッドの:dateに外部からのdata_formを結びつける。データ型は文字列。

              $stmh->execute(); //プリペアドステートメントの実行
              $pdo->commit(); //トランザクションをコミット

            }catch (PDOException $Exception){
              $pdo->rollBack(); //トランザクションをロールバック
              print "エラー：".$Exception->getMessage();
            }
          }
          header('Location: boss-check.php', true, 301);

          // すべての出力を終了
          exit;
        }
        elseif($_GET['action'] == "getURL")
        //********************確認URL生成の処理********************
        {
          ?>
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-condensed table-responsive text-center" style="table-layout:fixed;">
              <thead>
                <tr>
                  <th style="width:50px;">実施日</th>
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
                  try{
                    $sql="SELECT zangyo.id,zangyo.zangyo_date,zangyo.app_time,zangyo.employee_id,employee.employee_name,case_id.category,zangyo.project,zangyo.project_detail,zangyo.remarks,zangyo.result_time,department.department_name,work_group.group_name
                    from ((((zangyo LEFT OUTER JOIN employee ON zangyo.employee_id = employee.employee_id)
                    LEFT OUTER JOIN case_id ON zangyo.case_id = case_id.case_id)
                    LEFT OUTER JOIN department ON employee.department_id = department.department_id)
                    LEFT OUTER JOIN work_group ON employee.group_id = work_group.group_id)
                    WHERE id IN(" . implode(",",$_GET['id']) . ")
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
                        ?>
                      </td>
                      <td><?php require("../php_libs/SUM_MONTH.php"); ?></td><!--月間累計-->
                      <td><!--年間累計-->
                        <?php
                        require_once("../php_libs/FUNC_CHANGE_TO_APR1.php");
                        require("../php_libs/SUM_YEAR.php");
                        ?>
                      </td>
                      <td></td><!--確認-->
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
          <?php
        }
        ?>




      </main>
    </div>
  </div>

  <!-- Bootstrap core JavaScript
  ================================================== -->
  <!-- Placed at the end of the document so the pages load faster -->
  <script src="https://code.jquery.com/jquery-1.10.2.min.js" type="text/javascript" language="javascript"></script>
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
      defaultDate: 0
    });

  });
  </script>

</body>
</html>
