<?php

$my_name = '';
$gender = '';
$mail = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 名前の入力チェック
    if ($_POST['my_name'] === '') {
        print '名前を入力してください';
    } else {
        print 'ここに入力したお名前を表示:'  . htmlspecialchars($_POST['my_name'], ENT_QUOTES, 'UTF-8');
    }

    //性別チェック
    if ($_POST['gender'] === TRUE) {
        print 'ここに選択した性別を表示:'  . htmlspecialchars($_POST['gender'], ENT_QUOTES, 'UTF-8');
    }

    //メール必要有無
    if ($_POST['mail'] === '') {
        print 'メールは受け取りません';
    } else {
        print 'お知らせメールを送ります';
    }
}
?>


<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>課題</title>
</head>

<body>
    <h1>課題</h1>
    <form method="POST">
        <p>お名前:<input type="text" name="my_name"></p>

        <input type="radio" name="gender" value="man">男
        <input type="radio" name="gender" value="woman">女 <br>
        <input type="checkbox" name="mail" value="OK">お知らせメールを受け取る
        <input type="submit" value="送信する">
    </form>
</body>

</html>