<!-- テキストボックスの値をPOSTで送信し、日時とユーザが入力した値を1行ずつファイル(challenge_log.txt)に保存し、ページ下部にファイル内容を1行ずつ表示するプログラムを作成してください。 -->
<?php

//   postされた情報を利用して日時とユーザが入力した値を1行ずつファイル(challenge_log.txt)に保存。
$filename = './challenge_log.txt';
$comment = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['comment'])) {
        $comment = $_POST['comment'];
        $date = date('Y-m-d H:i');
    }

    if (($fp = fopen($filename, 'a')) !== FALSE) {
        //$fpに$filenameが入っている。
        if (fwrite($fp, $date . $comment . "\n") === FALSE) {
            print 'ファイル書き込み失敗:  ' . $filename;
        }
        fclose($fp);
        $comment = '' ;
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