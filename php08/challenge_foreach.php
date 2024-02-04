<?php
$class = ['ガリ勉' => '鈴木', '委員長' => '佐藤', 'セレブ' => '斎藤', 'メガネ' => '伊藤', '女神' => '杉内'];
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>全員分の名前とアダ名を表示してください</title>
</head>

<body>

    <?php
    foreach ($class as $key => $value) {
    ?>
        <p><?php print $key; ?>: <?php print $value; ?></p>
    <?php
    }
    ?>
</body>

</html>