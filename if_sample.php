<?php
$rand = mt_rand(1, 10);
?>


<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ifの使用例</title>
    <style type="text/css">
        .em_red {
            color: #FF0000;
        }
    </style>
</head>

<body>
    <h1>抽選システム</h1>
    <p>値は: <?php print $rand; ?></p>
    <?php if ($rand <= 3) { ?>
        <h2 class="em_red">当たり！！</h2>
    <?php } else { ?>
        <h2>残念でした・・また来年</h2>
    <?php  } ?>
</body>

</html>