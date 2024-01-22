<?php
//サイコロ(1〜6)を振り、「出た数字」と「偶数か奇数か」の2つの情報を表示してください。
$rand = mt_rand(1, 6);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>サイコロ</title>
    <style type="test/css">
        .em_red {
            color: #FF0000;
        }
    </style>
</head>

<body>
    <h1>サイコロゲーム！！！！</h1>
    <p>値は!<?php print $rand; ?></p>
    <?php if (($rand % 2) == 0) { ?>
        <h2 class="em_red">偶数や！</h2>
    <?php } else { ?>
        <h2>奇数か！</h2>
    <?php } ?>

</body>

</html>