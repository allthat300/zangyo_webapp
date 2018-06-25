<?php
  //PHPに接続する。
  //関数db_connect()を定義する。
  //$pdoに新しいインスタンスを生成し、DB接続処理をし、$pdoをreturn文で返す。

  function db_connect(){
    $db_user = "kishi";
    $db_pass = "gosumosu10";
    $db_host = "localhost";
    $db_name = "zangyo";
    $db_type  = "mysql";

    $dsn = "$db_type:host=$db_host;dbname=$db_name;charset=utf8";

    try{
      $pdo = new PDO($dsn,$db_user,$db_pass);
      $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION); //PDOクラスの属性設定,属性名：PDO::ATTR_ERRMODE,属性値：PDO::ERRMODE_EXCEPTION(エラーを検知して例外を投げる)
      $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,false); //プリペアドステートメントを利用して安全にSQLを実行する。
      //print "接続しました...<br>";
    }catch(PDOException $Exception){  //PDOExceptionクラス(例外クラス)とオブジェクトを格納する変数$Exceptionを指定
      die('エラー:'.$Exception->getMessage()); //dieがプログラムを停止して引数のメッセージを表示。getMessageメソッドでエラーメッセージを取得して表示。
    }
    return $pdo;
  }
