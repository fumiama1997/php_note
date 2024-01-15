<!DOCTYPE html>
<!-- // 送信ファイル名
// テキストボックスに名前を入力してPOSTで送信するページを作成 -->
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>7-8課題(初級)</title>
</head>

<body>
    <h1>名前を入力してください</h1>
    <form method="POST" action="practice_global_receive_elementary.php">

        <label>名前: <input type="text" name="my_name" id="my_name" value=""></label>
        <input type="submit" value="送信">
    </form>
</body>

</html>