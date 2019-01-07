<?php
/*
※利用できる関数の説明

・getDatabase_from_SQL($sql)
  SQL文からデータを取得
  使用例：
    $data_array = getDatabase_from_SQL("select * from event");

・getDatabase_from_id($id)
  eventテーブルのidを指定してデータを取得
  使用例：
    $data_array = getDatabase_from_id(3);

・getDatabase_from_date($year, $month)
  eventテーブルの開催する年月を指定してデータを取得
  使用例：
    $data_array = getDatabase_from_date(2019, 4);   //2019年4月に開催するイベントを取得

※この関数によって取得できる $data_array の形
  array(
    1 => array('title' =>「タイトル」, 'date' =>「日付」, 'kind' =>「種目」, ...),
    2 => array('title' =>「タイトル」, 'date' =>「日付」, 'kind' =>「種目」, ...),
    ...
  )
*/

// MySQLの準備
function start_MySQL(){
  // MySQLへの接続
  $link = mysql_connect('localhost', 'user_reserve', 'gopesh');
  if (!$link){
    die('MySQLへの接続に失敗しました。'.mysql_error());
  }

  // データベースの選択
  $db_selected = mysql_select_db('reservation_system', $link);
  if(!$db_selected){
    die('データベースの選択に失敗しました。'.mysql_error());
  }
  return $link;
}

// SQL文から得られたデータを配列にして返す
function eventData_to_array($data){
  $data_array = array();
  while($row = mysql_fetch_assoc($data)){
    $data_array[$row['id']] = array(
      "title" => $row['title'],
      "date" => $row['date'],
      "kind" => $row['kind'],
      "invite_people" => $row['invite_people'],
      "least_people" => $row['least_people'],
      "now_people" => $row['now_people'],
      "deadline" => $row['deadline'],
      "representative" => $row['representative'],
      "email" => $row['email'],
    );
  }
  return $data_array;
}

// MySQLからの切断
function quit_MySQL($link){
  $close_flag = mysql_close($link);
  if(!$close_flag){
    print('<p>切断に失敗しました。</p>');
  }
}


// 引数にSQL文を入れると、データベースから配列を返す関数
function getDatabase_from_SQL($sql){
  // MySQLの準備
  $link = start_MySQL();

  // データの取得
  $event_data = mysql_query($sql);
  $event_array = eventData_to_array($event_data);

  // MySQLからの切断
  quit_MySQL($link);

  return $event_array;
}

// 引数にidを入れると、データベースから対応するデータの配列を返す関数
function getDatabase_from_id($id){
  // idを指定するSQL文の作成
  $sql = sprintf('select * from event where id = %d', $id);

  // データの取得
  $event_array = getDatabase_from_sql($sql);

  return $event_array;
}

// 引数に開催年月を入れると、データベースからある月のデータ配列を返す関数
function getDatabase_from_date($year, $month){
  // 適切な引数か確認
  if (!($month >= 1 and $month <= 12)){
    die('getDatabase_from_id の引数 $month が正しくありません。');
  }

  $year_month_8digit = $year * 10000 + $month * 100;
  if ($month == 12){
    $year_next_month_8digit = ($year+1) * 10000 + 1 * 100;
  } else {
    $year_next_month_8digit = $year * 10000 + ($month+1) * 100;
  }

  // SQL文の作成
  $sql = sprintf('select * from event where (date >= %d) and (date < %d)',
                        $year_month_8digit, $year_next_month_8digit);
  // データの取得
  $event_array = getDatabase_from_sql($sql);

  return $event_array;
}
?>
