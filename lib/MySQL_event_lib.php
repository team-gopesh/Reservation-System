<?php
/*
getDatabase_from_SQL($sql)
SQL文からデータを取得
*/



// MySQLへの接続
function start_MySQL(){
  $link = mysql_connect('localhost', 'user_reserve', 'gopesh');
  if (!$link){
    die('MySQLへの接続に失敗しました。'.mysql_error());
  }
  return $link;
}

// データベースの選択
function selectDatabase_event($link){
  $db_selected = mysql_select_db('reservation_system', $link);
  if(!$db_selected){
    die('データベースの選択に失敗しました。'.mysql_error());
  }
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


// 引数にSQL文を入れると、データベースから配列を返す
function getDatabase_from_SQL($sql){
  // MySQLへの接続
  $link = start_MySQL();
  // データベースの選択
  selectDatabase_event($link);

  // データの取得
  $event_data = mysql_query($sql);
  $event_array = eventData_to_array($event_data);

  // MySQLからの切断
  quit_MySQL($link);

  return $event_array;
}

// 引数にidを入れると、データベースから配列を返す
function getDatabase_from_id($id){
  // idを指定するSQL文の作成
  $sql = sprintf('select * from event where id = %d', $id);

  // データの取得
  $event_array = getDatabase_from_sql($sql);

  return $event_array;
}
?>
