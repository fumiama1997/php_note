<?php
// $valueの値を定義
$value =  55.5555;
 
// 小数切り捨て値の処理を記述
 floor($value);
 
// 小数切り上げの処理を記述
 ceil($value);
 
// 小数四捨五入の処理を記述
 round($value); 
 
// 小数点以下第三位四捨五入の処理を記述
 round($value,3);
 
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>課題</title>
</head>
<body>
  <p><?php   print $value ;?></p>
  <p><?php   print floor($value);?></p>
  <p><?php   print  ceil($value);?></p>
  <p><?php   print  round($value);?></p>
  <p><?php   print  round($value,3);?></p>
</body>
</html>