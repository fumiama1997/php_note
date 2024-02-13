<?php
$error = [];
$board_data = [];

$host = 'localhost';
$username = 'root';
$passwd   = 'narait';
$dbname   = 'user';
$link = mysqli_connect($host, $username, $passwd, $dbname);

if ($link) {
    mysqli_set_charset($link, 'utf8');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = $_POST['name'];
    $comment = $_POST['comment'];
    // 名前の入力チェック、20文字以内かをチェック
    if ($name === '') {
        $error[] = '名前を入力してください';
    } else if (strlen($name) > 20) {
        $error[] = '名前は20文字以内で入力してください';
    }
    // ひとことの入力チェック、100文字以内かをチェック
    if ($comment === '') {
        $error[] = 'ひとことを入力してください';
    } else if (mb_strlen($comment) > 100) {
        $error[] = 'ひとことは100文字以内で入力してください';
    }

    // 正常処理
    if (empty($error)) {
        $date = date('m月d日 H:i:s');

        $query = "INSERT INTO board_table(board_name,comment,datetime) VALUES
        ('$name','$comment','$date')";
        $result = mysqli_query($link, $query);
    }
}


$query = 'SELECT board_id,board_name,comment,datetime FROM board_table';
$result = mysqli_query($link, $query);

while ($row = mysqli_fetch_array($result)) {
    $board_data[] = $row;
}
// 結果セットを開放します
mysqli_free_result($result);
// 接続を閉じます
mysqli_close($link);

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> ひとこと掲示板</title>
</head>

<body>
    <h1>ひとこと掲示板</h1>
    <form method="POST">
        <p>名前: <input type="text" name="name"> ひとこと: <input type="text" name="comment"> <input type="submit" value="送信"></p>
    </form>

    <?php foreach ($error as $value) { ?>
        <p><?php print $value; ?></p>
    <?php } ?>

    <p>発言一覧</p>

    <?php foreach ($board_data as $value) { ?>
        <p><?php print htmlspecialchars($value['board_name'], ENT_QUOTES, 'UTF-8'); ?>:
            <?php print htmlspecialchars($value['comment'], ENT_QUOTES, 'UTF-8'); ?>: <?php print htmlspecialchars($value['datetime'], ENT_QUOTES, 'UTF-8'); ?> </p>
    <?php  } ?>
</body>

</html>