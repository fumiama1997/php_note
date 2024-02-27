<?php
$regexp_mail   = '/^[a-z0-9_.-]+@[a-z0-9-]+.[a-z0-9_.-]+$/';
// パスワードは半角英数字記号で6文字以上18文字以下のみ可能とします
$regexp_password = '/^[a-z0-9!"#$%&\'\(\)\a-\^\¥@\[;:\],\.\/=~\|`\{\+\*\}<>\?_]{6,18}$/';
$error = [];
$result = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mail = $_POST['mail'];
    $password = $_POST['password'];

    //メールのバリデーション・正規表現
    if ($mail === '') {
        $error[] = 'メールアドレスが未入力です。<br>';
        // マッチしなかった場合は 0 を返します
    } else if (preg_match($regexp_mail, $mail, $matches) === 0) {
        $error[] = 'メールアドレスの形式が正しくありません<br>';
    }

    //パスワードのバリデーション・正規表現
    if ($password === '') {
        $error[] = 'パスワードが未入力です。<br>';
        // マッチしなかった場合は 0 を返します
    } else if (preg_match($regexp_password, $password, $matches) === 0) {
        $error[] = 'パスワードは半角英数字記号6文字以上18文字以下で入力してください<br>';
    }
    if (empty($error)) {
        $result = '登録完了';
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
    <?php if (empty($result)) { ?>
        <p>メールアドレス</p>
        <form method="POST">
            <input type="text" name="mail">
            <p>パスワード</p>
            <input type="password" name="password"><br>
            <?php
            foreach ($error as $value) {
                print '<br>' . $value;
            }
            ?><br>
            <input type="submit" value="登録">
        </form>
    <?php }
    print $result; ?>

</body>

</html>