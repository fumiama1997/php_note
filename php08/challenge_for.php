<?php 

$data = '0';

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>3の倍数の数だけを足した合計値</title>
</head>
<body>
3の倍数の数だけを足した合計値を表示する。

<?php 

for( $i = 1 ;  $i<= 100 ; $i = $i + 1){
  if($i % 3 === 0){
    $data = $data + $i ;
  } 
}

print $data;

?>

    
</body>
</html>