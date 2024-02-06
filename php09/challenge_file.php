<?php
$filename = './challenge_log.txt';
$comment = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['comment'])) {
        $comment = $_POST['comment'];
        $date = date('Y-m-d H:i');
    }

    if (($fp = fopen($filename, 'a')) !== FALSE) {
        if (fwrite($fp, $date . '  ' . $comment . "\n") === FALSE) {
            print 'ファイル書き込み失敗:  ' . $filename;
        }
        fclose($fp);
    }
}

$data = [];

if (is_readable($filename) === TRUE) {
    if (($fp = fopen($filename, 'r')) !== FALSE) {
        while (($tmp = fgets($fp)) !== FALSE) {
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
    <title>Document</title>
</head>

<body>
    <h1>課題</h1>
    <form method="POST">
        <p>発言:<input type="text" name="comment"><input type="submit" name="submit" value="送信"></p>
    </form>
    <p>発言一覧</p>

    <?php foreach ($data as $value) {
        print $value . '<br>';
    } ?>




</body>

</html>