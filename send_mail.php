<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>メール送信確認</title>
  </head>
  <body>
    <?php
    // 各入力フォームに正しく入力されているかの判定
    $is_name = ($_POST['name'] != '');
    $is_to = ($_POST['to'] != '');
    $is_password = ($_POST['password'] != '');

    // 必要事項全てに記入されていれかの判定
    $is_filled = ($is_name and $is_to and $is_password);

    // 入力フォームに必要事項が全て入力されていたときの処理
    if ($is_filled){
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

      echo '送信完了';
    }

    // 入力フォームに必要事項が入力されていないときの処理
    else {
      echo 'メールが正しく送信されませんでした';
    }
    ?>
    
    <a href="P2_Osugi.php">大杉担当ページへ</a>
  </body>
</html>
