<?php

$data = 0;

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
    $i = 1;
    while ($data <= 1000) {
        $data = $data + $i;
        if ($data >= 1000) {
            print $i . '<br>' . $data;
        }
        $i++;
    }

    ?>
</body>

</html>