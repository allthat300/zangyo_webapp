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

        //****************************************編集の処理(開始)****************************************
        {
          ?>

          <?php
          try{
            $sql="SELECT zangyo.id,zangyo.zangyo_date,zangyo.case_id,zangyo.app_time,zangyo.employee_id,employee.employee_name,case_id.category,zangyo.project,zangyo.project_detail,zangyo.boss_check,zangyo.remarks,zangyo.result_time,department.department_name,work_group.group_name
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
            $count_edit=1;
            while($row=$stmh->fetch(PDO::FETCH_ASSOC)){
              ?>

              <!-- **********テーブル1段目(開始)********** -->
              <h5><?= $count_edit ?>件目</h5>
              <table class="table table-striped table-bordered table-condensed text-center">
                <thead>
                  <tr>
                    <th style="width:150px;">実施日</th>
                    <th style="width:100px;">種別</th>
                    <th style="width:150px;">名前</th>
                    <th style="width:150px;">部署</th>
                    <th style="width:150px;">グループ</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <form method="post" action="UPDATE_ALL.php">
                      <td class="m-0 p-0">
                        <input type="text" class="form-control" name="zangyo_date-<?=$row['id']?>" value="<?=htmlspecialchars(substr($row['zangyo_date'],0,10),ENT_QUOTES)?>">
                      </td>
                      <!--実施日-->

                      <td class="m-0 p-0">
                        <select class="form-control" name="case_id-<?=$row['id']?>">
                          <option value="1"<?php if($row['case_id'] == 1){echo "selected";} ?>>残業</option>
                          <option value="2"<?php if($row['case_id'] == 2){echo "selected";} ?>>早出</option>
                          <option value="3"<?php if($row['case_id'] == 3){echo "selected";} ?>>FLEX</option>
                          <option value="4"<?php if($row['case_id'] == 4){echo "selected";} ?>>休出</option>
                          <option value="5"<?php if($row['case_id'] == 5){echo "selected";} ?>>代休</option>
                        </select>
                      </td><!--種別-->


                      <td class="m-0 p-2"><?=htmlspecialchars($row['employee_name'],ENT_QUOTES)?></td><!--名前-->
                      <td class="m-0 p-2"><?=htmlspecialchars($row['department_name'],ENT_QUOTES)?></td><!--部署-->
                      <td class="m-0 p-2"><?=htmlspecialchars($row['group_name'],ENT_QUOTES)?></td><!--グループ-->
                    </tr>
                  </tbody>
                </table>

                <!-- **********テーブル1段目(終了)********** -->

                <!-- **********テーブル2段目(開始)********** -->

                <table class="table table-striped table-bordered table-condensed text-center">
                  <thead>
                    <tr>
                      <th style="width:150px;">申請時間</th>
                      <th style="width:150px;">実施時間</th>
                      <th style="width:150px;">月間累計</th>
                      <th style="width:150px;">年間累計</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="m-0 p-0">
                        <input type="text" class="form-control" name="app_time-<?=$row['id']?>" value="<?=htmlspecialchars(substr($row['app_time'],0,5),ENT_QUOTES)?>">
                      </td>
                      <!--申請時間-->


                      <td class="m-0 p-0">
                        <input type="text" class="form-control" name="result_time-<?=$row['id']?>" value="<?=htmlspecialchars(substr($row['result_time'],0,5),ENT_QUOTES)?>">
                      </td>
                      <!--実施時間-->
                      <td class="m-0 p-2"><?php require("../php_libs/SUM_MONTH.php"); ?></td><!--月間累計-->
                      <td class="m-0 p-2"><!--年間累計-->
                        <?php
                        require_once("../php_libs/FUNC_CHANGE_TO_APR1.php");
                        require("../php_libs/SUM_YEAR.php");
                        ?>
                      </tr>
                    </tbody>
                  </table>

                  <!-- **********テーブル2段目(終了)********** -->

                  <!-- **********テーブル3段目(開始)********** -->

                  <table class="table table-striped table-bordered table-condensed text-center">
                    <thead>
                      <tr>
                        <th style="width:100px;">確認</th>
                        <th style="width:200px;">機種</th>
                        <th style="width:400px;">内容</th>
                        <th style="width:600px;">備考</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td class="m-0 p-0">
                          <select class="form-control" name="boss_check-<?=$row['id']?>">
                            <option value="1"<?php if($row['boss_check'] == 0){echo "selected";} ?>></option>
                            <option value="2"<?php if($row['boss_check'] == 1){echo "selected";} ?>>済</option>
                          </select>
                        </td><!--確認-->


                        <td class="m-0 p-0">
                          <input type="text" class="form-control" name="project-<?=$row['id']?>" value="<?=htmlspecialchars($row['project'],ENT_QUOTES)?>">
                        </td>
                        <!--機種-->


                        <td class="m-0 p-0">
                          <input type="text" class="form-control" name="project_detail-<?=$row['id']?>" value="<?=htmlspecialchars($row['project_detail'],ENT_QUOTES)?>">
                        </td>
                        <!--内容-->


                        <td class="m-0 p-0">
                          <input type="text" class="form-control" name="remarks-<?=$row['id']?>" value="<?=htmlspecialchars($row['remarks'],ENT_QUOTES)?>">
                        </td>
                        <!--備考-->


                      </tr>
                    </tbody>
                  </table>

                  <!-- **********テーブル3段目(終了)********** -->
                  <br>
                  <hr>
                  <?php
                  $count_edit++;
                }
              }
              ?>
              <button class="btn btn-primary" type="submit" name='action' value='edit'>送信</button>
            </form>
            <br>
            <br>
            <?php
          }
          //****************************************編集の処理()終了)****************************************
          elseif($_GET['action'] == "delete")
          //****************************************削除の処理(開始)****************************************
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
            header('Location: search.php', true, 301); //searchにリダイレクト
            exit;//すべての出力を終了
          }
          //****************************************編集の処理(終了)****************************************
          elseif($_GET['action'] == "getURL")
          //****************************************URL生成の処理(開始)****************************************
          {
            ?>
            <?php
            if(isset($_GET['id']) == False)
            {
              echo "勤怠が選択されていません。";
            }else {
              ?>

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
                      try{
                        $sql="SELECT zangyo.id,zangyo.zangyo_date,zangyo.app_time,zangyo.employee_id,employee.employee_name,case_id.category,zangyo.project,zangyo.project_detail,zangyo.boss_check,zangyo.remarks,zangyo.result_time,department.department_name,work_group.group_name
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
                          <form method="post" action="UPDATE_BOSS_CHECK.php">
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
                <button class="btn btn-primary" type="submit" name='action' value='approve'>承認</button>
              </form>
              <?php
            }
            ?>
            <?php
            ///  require("../php_libs/UPDATE_BOSS_CHECK.php");
          }
          //****************************************編集の処理(終了)****************************************
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
