<!DOCTYPE html>
<!-- http://localhost/Reservation-System/P2_Osugi.php -->
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>ページ２</title>
    <link rel="stylesheet" type="text/css" href="css/P2_style.css">
  </head>
  <div id="wrap">
    <!-- ======= ヘッダー ======= -->
    <header>
      <h1, class='header-logo'>イベント告知掲示板　　　　～イベント内容の表示～</h1>
    </header>

    <!-- ======= コンテンツ ======= -->
    <div id="contents">

      <!-- MySQLからイベント情報の取得 -->
      <?php
      // データベースを取得するライブラリのインポート
      require "./lib/MySQL_event_lib.php";

      // データの取得
      $get_id = 1;   // 取得するレコードのidを選択
      $event_array = getDatabase_from_id($get_id);

      // テスト用に、データベースの全情報を取得
      $all_data = getDatabase_from_SQL('select * from event');
      ?>

      <!-- イベント情報の表示 -->
      <div class="box_event">
        <div class="box0">
          イベント内容
        </div>
        <div class="box1">
          <div class="box11">タイトル</div>
          <div class="box12"><?php echo $event_array[$get_id]["title"]; ?></div>
        </div>
        <div class="box2">
          <div class="box21">日時</div>
          <div class="box22"><?php echo $event_array[$get_id]["date"]; ?></div>
        </div>
        <div class="box3">
          <div class="box31">種目</div>
          <div class="box32"><?php echo $event_array[$get_id]["kind"]; ?></div>
        </div>
        <div class="box4">
          <div class="box41">募集人数</div>
          <div class="box42"><?php echo $event_array[$get_id]["invite_people"]; ?>人</div>
        </div>
        <div class="box5">
          <div class="box51">現在の人数</div>
          <div class="box52"><?php echo $event_array[$get_id]["now_people"]; ?>人</div>
        </div>
        <div class="box6">
          <div class="box61">期限</div>
          <div class="box62"><?php echo $event_array[$get_id]["deadline"]; ?></div>
        </div>
        <div class="box7">
          <div class="box71">代表者</div>
          <div class="box72"><?php echo $event_array[$get_id]["representative"]; ?></div>
        </div>
        <div class="box8">
          <div class="box81">連絡先</div>
          <div class="box82"><?php echo $event_array[$get_id]["email"]; ?></div>
        </div>
        <div class="box9">
          <div class="box91">コメント</div>
          <div class="box92">あああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああ</div>
        </div>
      </div>

      <!-- 希望者用入力フォーム -->
      <div class="box_participant">
        <form action="send_mail.php" method="post">
          <div class="box0_">
            入力フォーム
          </div>
          <div class="box1_">
            <div class="index_name">名前</div>
            <div class="content_name">
              <input type="text" name="name">
            </div>
          </div>
          <div class="box2_">
            <div class="index_mail">メールアドレス</div>
            <div class="content_mail">
              <input type="text" name="to" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$">
            </div>
          </div>

          <!-- 送信ボタン -->
          <input type="text" name="password" value="password">
          <input class="button_submit" type="submit" value="送信">
        </form>
      </div>

      <!-- テスト用にデータベースの全情報を表示 -->
      <p>テスト用に「selelct * from event」で表示されるeventテーブルの情報を表示</p>
      <?php
      foreach ($all_data as $id => $event){
        echo $id . ' | ';
        echo $event['title'].' | ';
        echo $event['date'].' | ';
        echo $event['kind'].' | ';
        echo $event['invite_people'].' | ';
        echo $event['least_people'].' | ';
        echo $event['now_people'].' | ';
        echo $event['deadline'].' | ';
        echo $event['representative'].' | ';
        echo $event['email'].'<br/>';
      }
      ?>

    </div>

    <!-- ======= フッター ======= -->
    <footer>
      <h2>フッター</h2>

      <!-- テスト用のリンク -->
      <a href="page2_tatehata_1120.php">建畠担当ページへ</a>

    </footer>
  </div>
  </body>
</html>
