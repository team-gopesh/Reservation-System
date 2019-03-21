<?php
// 日程に関する情報から年月日を取り出す
function get_date($date_info){
  $year = floor($date_info / 10000);
  $month = floor($date_info / 100) - $year * 100;
  $date = $date_info - $year * 10000 - $month * 100;
  return array($year, $month, $date);
}
?>
