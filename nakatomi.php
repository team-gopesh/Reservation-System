<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>nakatomi</title>
    <link rel="stylesheet" href="css/nakatomi.css">
  </head>
  <body>
    <?php require('MySQL_event_lib.php'); ?>
    <?php
      $year = date('Y');
      $month = date('n');
      $last_day = date('j', mktime(0,0,0, $month + 1, 0, $year));
      $calendar = array();
      $j = 0;
      //
      for ($i = 1;$i < $last_day + 1; $i++){
        $week = date('w', mktime(0,0,0, $month, $i, $year));
        if($i == 1){
          for($s =1;$s <= $week; $s++){
            $calendar[$j]['day'] = '';
            $j++;
          }
        }
        $calendar[$j]['day'] = $i;
        $j++;

        if($i == $last_day){
          for($e =1;$e <= 6 - $week;$e++){
            $calendar[$j]['day'] = '';
            $j++;
          }
        }
      }
    ?>
	<?php 
	  $event_array = array();
	  $event_array = getDatabase_from_date($year,$month); 
	?> 
    <br>
    <form action="nakatomi.php" method="get">
      <input type="text" name="select_month"><br>
      <input type="submit" value="送信" >
    </form>
    <br>
    <br>
    <table>
      <tr>
        <th>日</th>
        <th>月</th>
        <th>火</th>
        <th>水</th>
        <th>木</th>
        <th>金</th>
        <th>土</th>
      </tr>
      <tr>
        <?php $cnt = 0; ?>
        <?php foreach ($calendar as $key => $value): ?>
          <td>
            <?php $cnt++; ?>
            <?php echo $value['day']; ?>
			<br>
			<?php 
			  if($event_array['date'] == $value['day']){
			  	  echo $event_array['kind'];
			  }
			?>
			<br>
          </td>
        <?php if($cnt == 7): ?>
      </tr>
      <tr>
        <?php $cnt = 0; ?>
        <?php endif; ?>
        <?php endforeach; ?>
      </tr>
    </table>
  </body>
</html>
