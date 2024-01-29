<!-- メールアドレスとパスワードを入力し登録ボタンを押すと、入力された値に問題ないか確認し、問題なければ「登録完了」、それ以外はエラーメッセージを表示するページを作成してください。
パスワードは半角英数字記号で6文字以上18文字以下のみ可能とします。
また「未入力」と「入力はあるが値に問題がある」はそれぞれ異なるエラーメッセージを用意し表示してください。 -->


<?php
$regexp_mail   = '/[a-zA-Z0-9_.+-]+@[a-zA-Z0-9_.+-]+.[a-zA-Z0-9_.+-]+/';
// 数字とアルファベットを最低1文字ずつ含む6文字以上18文字以下
$regexp_password = '/(?=.*[a-zA-Z])(?=.*\d).{6,18}/';
$mail = '';
$password = '';
$msg = [];
$error = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mail = $_POST['mail'];
    $password = $_POST['password'];

    //メールのバリデーション・正規表現
    if ($_POST['mail'] === '') {
        $error[] = 'メールアドレスを入力してください<br>';
    } else if (preg_match($regexp_mail, $mail, $macthes) === 0) {
        $error[] = '正しくメールアドレスを入力してください<br>';
    }

    //パスワードのバリデーション・正規表現
    if ($_POST['password'] === '') {
        $error['password'] = 'パスワードを入力してください<br>';
    } else if (preg_match($regexp_password, $password, $macthes) === 0) {
        $error[] = 'パスワードは半角英数字記号で6文字以上18文字以下で入力ください<br>';
    }
    if (empty($error)) {
        $success = '登録が成功しました';
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
    <p>メールアドレス</p>
    <form method="POST">
        <input type="text" name="mail">
        <p>パスワード(数字とアルファベットを最低1文字ずつ含む6文字以上18文字以下)</p>
        <input type="password" name="password">
        <input type="submit" value="登録">
    </form>


    <?php
    print  $success;

    foreach ($error as $value) {
        print $value;
    }

    ?>
</body>

</html>