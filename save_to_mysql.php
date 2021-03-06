<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>MySQLへの保存</title>
  </head>
  <body>
    <!--
      保存してすぐにリダイレクトしない場合、
      保存ページで更新したり次のページから戻るボタンを押したりすると、
      $_POSTが残っているため、二重に保存される可能性がある。
      このページを作る問題点は、
      前のページにリダイレクトされたときに、入力した内容は初期化されてしまう。

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
    // sql文で使う特殊文字をエスケープする関数
    function quote_smart($value){
      if (!is_numeric($value)){  //数値以外をクオートする
        $value = "'" . mysql_real_escape_string($value) . "'";
      }
      return $value;
    }

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

    // 保存確認ページにリダイレクト
    header('Location: check_save.php');
    exit();

    ?>
  </body>
</html>
