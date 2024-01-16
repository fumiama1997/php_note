<?php 

$data = '0';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>和が1000を超えるときに足される数値と、合計値を表示してください</title>
</head>
<body>

<?php 
    for($i = 1 ; $data <= 1000 ; $i = $i + 1){
        $data = $data + $i ;
    }
    print $i ."\n" ;
    print $data;
?>
</body>
</html>