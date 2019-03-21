<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>メール送信確認</title>
  </head>
  <body>
    <!-- このページでは、別のページにリダイレクトはしていないが、
    　　　戻るボタンを押してもなぜか再度メールが送られない -->

    <?php
    // 日本語メールを送るための設定
    mb_language("Japanese");
    mb_internal_encoding("UTF-8");
    //PEAR::Mailのインクルード
    require_once("Mail.php");

    // 変数の格納
    // イベントおよび代表者情報
    $id = $_POST['id'];
    $title = $_POST['title'];
    $date = $_POST['date'];
    $kind = $_POST['kind'];
    $invite_people = $_POST['invite_people'];
    $least_people = $_POST['least_people'];
    $now_people = $_POST['now_people'] + 1;  // この参加者の分を増やしておく
    $deadline = $_POST['deadline'];
    $representative = $_POST['representative'];
    $email = $_POST['email'];
    $comment = $_POST['comment'];
    // ここから参加者情報
    $name = $_POST['name'];
    $to = $_POST['to'];
    $password = $_POST['password'];

    // 日程に関する情報から年月日を取り出す
    require "./lib/util.php";
    list($date_year, $date_month, $date_date) = get_date($date);
    list($deadline_year, $deadline_month, $deadline_date) = get_date($deadline);

    // 更新された現在の人数をデータベースに保存
    require "./lib/MySQL_event_lib.php";
    $link = start_MySQL();  // MySQLの開始
    $sql = sprintf("update event set now_people = %d where id = %d",
                    $now_people, $id);  //INSERTするためのSQL文を作成
    $result = mysql_query($sql);  // データベースの変更
    if(!$result){
      die('UPDATEクエリーが失敗しました。'.mysql_error());
    }
    quit_MySQL($link);

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
      "To" => $email,  // →ここで指定したアドレスには送信されない
      "From" => "gopesh.test1@gmail.com",
      "Subject" => mb_encode_mimeheader('イベント作成の連絡') // 日本語の件名を指定する場合、mb_encode_mimeheaderでエンコード
    );

    // メール本文
    $content = $representative . " 様\n\n"
                . "京都大学イベント予約システムです。\n"
                . $representative . " 様が作成したイベントに、新たな参加者が来ましたのでご連絡します。\n\n"
                . "++++++++++++++++++++++\n"
                . "イベント内容\n"
                . "・タイトル：　" . $title . "\n"
                . "・日時：　" . $date_year . "年" . $date_month . "月" . $date_date . "日\n"
                . "・種目：　" . $kind . "\n"
                . "・募集人数：　" . $invite_people . "人\n"
                . "・最低人数：　" . $least_people . "人\n"
                . "・現在の人数：　" . $now_people . "人\n"
                . "・期限：　" . $deadline_year . "年" . $deadline_month . "月" . $deadline_date . "日\n"
                . "・代表者：　" . $representative . "\n"
                . "・代表者連絡先：　" . $email . "\n"
                . "・コメント：　" . $comment . "\n\n"
                . "参加者情報\n"
                . "・名前：　" . $name . "\n"
                . "・連絡先：　" . $to . "\n"
                . "++++++++++++++++++++++\n\n"
                . "参加者と連絡を取り合って、集合場所などを決めてください。\n"
                . "ご質問等がございましたら、このメールに返信をください。\n\n"
                . "----------------------\n"
                . "京都大学イベント予約システム（個人運営）\n"
                . "メールアドレス： gopesh.test1@gmail.com\n"
                . "----------------------";

    // 日本語なのでエンコード
    $content = mb_convert_encoding($content, "ISO-2022-JP", "UTF-8");

    // sendメソッドでメールを送信
    $mailObject->send($email, $headers, $content);

    echo '送信完了';
    ?>

    <a href="nakatomi.php">トップページへ</a>
  </body>
</html>
