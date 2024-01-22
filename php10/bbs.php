<?php
$filename = './challenge_log.txt';
$comment = '';
$name = '';
$nameLimit = '20';
$commentLimit = '100';
$error = [];
$data = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 名前の入力チェック、20文字以内かをチェック
    if ($_POST['name'] === '') {
        $error['name'] = '名前を入力してください';
    } else if (strlen($_POST['name']) > 20) {
        $error['name'] = '名前は20文字以内で入力してください';
    }
    // ひとことの入力チェック、100文字以内かをチェック
    if ($_POST['comment'] === '') {
        $error['comment'] = 'ひとことを入力してください';
    } else if (mb_strlen($_POST['comment']) > 100) {
        $error['comment'] = 'ひとことは100文字以内で入力してください';
    }
    // 正常処理
    if (empty($error)) {
        // 日付を取得
        $date = date('Y-m-d H:i:s');
        // 書き込むデータを作成
        $file_write_text = $_POST['name'] . "\t" . $_POST['comment'] . "\t" . $date . "\n";
        // ファイルへの書き込み
        if (($fp = fopen($filename, 'a')) !== FALSE) {
            if (fwrite($fp, $file_write_text) === FALSE) {
                print 'ファイル書き込み失敗:  ' . $filename;
            }
            fclose($fp);
        }
    }
}

if (is_readable($filename) === TRUE) {
    if (($fp = fopen($filename, 'r')) !== FALSE) {
        while (($tmp = fgets($fp)) !== FALSE) {
            $data[] =  htmlspecialchars($tmp, ENT_QUOTES, 'UTF-8');
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


    <?php foreach ($data as $element) {
        print $element . '<br>';
    } ?>










</body>

</html>