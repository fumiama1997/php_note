<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>課題</title>
</head>

<body>
    <h1>課題</h1>
    <form method="POST" action="super_global_receive.php">
        <p>お名前:<input id="my_name" type="text" name="my_name" value=""></p>

        <input type="radio" name="gender" value="man">男
        <input type="radio" name="gender" value="woman">女 <br>
        <input type="checkbox" name="mail" value="OK">お知らせメールを受け取る
        <input type="submit" value="送信する">
    </form>
</body>

</html>