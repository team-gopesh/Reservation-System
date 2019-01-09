<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>データベースへの保存 & メール送信</title>
  </head>
  <body>
    <!--
      MySQL
      データベースの作成 (データベース名 reservation_system)：
        create database reservation_system;
      テーブルの作成 (テーブル名 event)：
        create table event(
          id int,
          title varchar(30),
          date int,
          kind varchar(30),
          invite_people int,
          least_people int,
          now_people int,
          deadline int,
          representative varchar(30),
          email varchar(30),
          primary key (id)
        );
      ユーザーの作成 (ユーザ名 user_reserve、パスワード gopesh)：
        create user 'user_reserve'@'localhost' identified by 'gopesh';
      権限の追加：
        grant all on reservation_system.* to 'user_reserve'@'localhost';
    -->

    <?php
    /* --- データベースへの保存 --- */

    // MySQLの準備
    require "./lib/MySQL_event_lib.php";
    $link = start_MySQL();

    // 現在登録されているレコードのidの最大値を取得
    $id_current = mysql_query('select id from event order by id desc limit 1');
    if(!$id_current){
      die('現在登録されているidの取得に失敗しました。'.mysql_error());
    }
    while($row = mysql_fetch_assoc($id_current)){
      if ($row == ''){   // データベースが空の場合
        $id_last = 0;
      } else {
        $id_last = $row['id'];
      }
    }

    // sql文で使う特殊文字をエスケープする関数
    function quote_smart($value){
      if (!is_numeric($value)){  //数値以外をクオートする
        $value = "'" . mysql_real_escape_string($value) . "'";
      }
      return $value;
    }

    // データベースへ保存する内容を整理
    $add_id = $id_last + 1;
    $add_title = quote_smart($_POST['title']);
    $add_date = quote_smart($_POST['date']);
    $add_kind = quote_smart($_POST['kind']);
    $add_invite_people = quote_smart($_POST['invite_people']);
    $add_least_people = quote_smart($_POST['least_people']);
    $add_now_people = quote_smart($_POST['now_people']);
    $add_deadline = quote_smart($_POST['deadline']);
    $add_representative = quote_smart($_POST['representative']);
    $add_email = quote_smart($_POST['email']);
    $add_comment = quote_smart($_POST['comment']);

    //INSERTするためのSQL文を作成
    $sql = sprintf("insert into event (
        id, title, date, kind, invite_people, least_people,
        now_people, deadline, representative, email, comment
      ) values (%d, %s, %d, %s, %d, %d, %d, %d, %s, %s, %s)",
      $add_id, $add_title, $add_date, $add_kind, $add_invite_people, $add_least_people,
      $add_now_people, $add_deadline, $add_representative, $add_email, $add_comment
    );
    // データベースへ保存
    $result_save = mysql_query($sql);
    if(!$result_save){
      die('INSERTクエリーが失敗しました。'.mysql_error());
    }

    //MySQLからの切断
    quit_MySQL($link);


    /* --- メール送信 --- */

    // 日本語メールを送るための設定
    mb_language("Japanese");
    mb_internal_encoding("UTF-8");
    //PEAR::Mailのインクルード
    require_once("Mail.php");

    // 変数の格納
    $name = $_POST['name'];
    $to = $_POST['to'];
    $password = $_POST['password'];
    echo $name . 'さんが<br/>';
    echo $to . 'へメールを送る<br/>';

    // GmailのSMTPサーバの情報を連想配列にセット
    $params = array(
      "host" => "smtp.gmail.com",
      "port" => 587,
      "auth" => true,
      "username" => "gopesh.test1@gmail.com",
      "password" => $password
    );

    // PEAR::Mailのオブジェクトを作成
    $mailObject = Mail::factory("smtp", $params);

    // メールヘッダ情報を連想配列としてセット
    $headers = array(
      "To" => $to,  // →ここで指定したアドレスには送信されない
      "From" => "gopesh.test1@gmail.com",
      "Subject" => mb_encode_mimeheader('イベント作成の連絡') // 日本語の件名を指定する場合、mb_encode_mimeheaderでエンコード
    );

    // メール本文
    $content = $name . ' さんへイベントご連絡';
    // 日本語なのでエンコード
    $content = mb_convert_encoding($content, "ISO-2022-JP", "UTF-8");

    // sendメソッドでメールを送信
    $mailObject->send($to, $headers, $content);

    // 確認ページにリダイレクト
    header('Location: check_save.php');
    exit();
    ?>
  </body>
</html>
