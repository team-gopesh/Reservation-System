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
      // MySQLの準備
      require "./lib/MySQL_event_lib.php";

      // 現在登録されているレコードのidの最大値を取得
      $link = start_MySQL();
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
      quit_MySQL($link);

      // 前ページで入力したデータを格納
      $add_id = $id_last + 1;
      $add_title = $_POST['title'];
      $add_date = $_POST['date'];
      $add_kind = $_POST['kind'];
      $add_invite_people = $_POST['invite_people'];
      $add_least_people = $_POST['least_people'];
      $add_now_people = $_POST['now_people'];
      $add_deadline = $_POST['deadline'];
      $add_representative = $_POST['representative'];
      $add_email = $_POST['email'];
      $add_comment = $_POST['comment'];

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
          <div class="box12"><?php echo $add_title; ?></div>
        </div>
        <div class="box2">
          <div class="box21">日時</div>
          <div class="box22"><?php echo $add_date; ?></div>
        </div>
        <div class="box3">
          <div class="box31">種目</div>
          <div class="box32"><?php echo $add_kind; ?></div>
        </div>
        <div class="box4">
          <div class="box41">募集人数</div>
          <div class="box42"><?php echo $add_invite_people; ?>人</div>
        </div>
        <div class="box5">
          <div class="box51">現在の人数</div>
          <div class="box52"><?php echo $add_now_people; ?>人</div>
        </div>
        <div class="box6">
          <div class="box61">期限</div>
          <div class="box62"><?php echo $add_deadline; ?></div>
        </div>
        <div class="box7">
          <div class="box71">代表者</div>
          <div class="box72"><?php echo $add_representative; ?></div>
        </div>
        <div class="box8">
          <div class="box81">連絡先</div>
          <div class="box82"><?php echo $add_email; ?></div>
        </div>
        <div class="box9">
          <div class="box91">コメント</div>
          <div class="box92"><?php echo $add_comment; ?></div>
        </div>
      </div>

      <!-- 希望者用入力フォーム -->
      <div class="box_participant">
        <form action="save_and_mail.php" method="post">
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

          <!-- 前ページから渡された変数を隠しデータに格納($_POSTで渡すため) -->
          <input type="hidden" name="id" value=<?php echo $add_id; ?>>
          <input type="hidden" name="title" value=<?php echo $add_title; ?>>
          <input type="hidden" name="date" value=<?php echo $add_date; ?>>
          <input type="hidden" name="kind" value=<?php echo $add_kind; ?>>
          <input type="hidden" name="invite_people" value=<?php echo $add_invite_people; ?>>
          <input type="hidden" name="least_people" value=<?php echo $add_least_people; ?>>
          <input type="hidden" name="now_people" value=<?php echo $add_now_people; ?>>
          <input type="hidden" name="deadline" value=<?php echo $add_deadline; ?>>
          <input type="hidden" name="representative" value=<?php echo $add_representative; ?>>
          <input type="hidden" name="email" value=<?php echo $add_email; ?>>
          <input type="hidden" name="comment" value=<?php echo $add_comment; ?>>

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
        echo $event['email'].' | ';
        echo $event['comment'].'<br/>';
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
