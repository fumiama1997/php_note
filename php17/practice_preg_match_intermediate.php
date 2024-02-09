<?php
$regexp_mail   = '/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9_.+-]+.[a-zA-Z0-9_.+-]+$/';
// パスワードは半角英数字記号で6文字以上18文字以下のみ可能とします
$regexp_password = '/^[a-z0-9.\/?%&=]{6,18}$/';
$msg = [];
$error = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mail = $_POST['mail'];
    $password = $_POST['password'];

    //メールのバリデーション・正規表現
    if ($mail === '') {
        $error[] = 'メールアドレスを入力してください<br>';
    } else if (preg_match($regexp_mail, $mail, $matches) === 0) {
        $error[] = 'メールアドレスの形式が正しくありません<br>';
    }

    //パスワードのバリデーション・正規表現
    if ($password === '') {
        $error[] = 'パスワードを入力してください<br>';
    } else if (preg_match($regexp_password, $password, $matches) === 0) {
        $error[] = 'パスワードは半角英数字記号6文字以上18文字以下で入力してください<br>';
    }
    if (empty($error)) {
        $success = '登録完了';
    }
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>17-5 課題</title>
</head>

<body>


    <?php if (empty($success)) { ?>
        <p>メールアドレス</p>
        <form method="POST">
            <input type="text" name="mail">
            <p>パスワード</p>
            <input type="password" name="password"><br>

            <br>
            <input type="submit" value="登録">

        <?php }; ?>

        <?php
        print $success;
        foreach ($error as $value) {
            print '<br>' . $value;
        }
        ?>
        </form>


</body>

</html>