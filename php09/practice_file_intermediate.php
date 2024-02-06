<!-- 日本郵便のページにある郵便番号のデータを利用し、「郵便番号」「住所」のデータを表示してください。 -->

<?php
$filename = './zip_data_split_1.txt';

if (is_readable($filename) == TRUE) {

    if (($fp = fopen($filename, 'r')) == TRUE) {
        while (($tmp = fgets($fp)) == TRUE) {
            $data[] = htmlspecialchars($tmp, ENT_QUOTES, 'UTF-8');
        }
        fclose($fp);
    }
} else {
    $data[] = 'ファイルがありません';
}


?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>郵便番号と住所</title>
</head>

<body>
    <?php foreach ($data as $address) {
        print $address . '<br>';
    } ?>



</body>

</html>