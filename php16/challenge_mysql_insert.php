<?php
$goods_data = [];
$error = [];
$name = '';
$price = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $host = 'localhost'; // データベースのホスト名又はIPアドレス
    $username = 'root';  // MySQLのユーザ名
    $passwd   = 'narait';    // MySQLのパスワード
    $dbname   = 'user';    // データベース名
    $link = mysqli_connect($host, $username, $passwd, $dbname);

    // 接続成功した場合
    if ($link) {
        // 文字化け防止
        mysqli_set_charset($link, 'utf8');
    }
    if ($name === '') {
        $error['name'] = '商品名を入力してください';
    }
    if ($price === '') {
        $error['price'] = '価格を入力してください';
    } else if (is_numeric($price) === FALSE) {
        $error['int'] = '全角数字・文字は入力できません。';
    }

    if (empty($error)) {

        $query =  "INSERT INTO goods_table (goods_name, price) VALUES ('$name','$price')";

        //追加 クエリを実行します  
        $result = mysqli_query($link, $query);

        print '追加成功';

        $query = 'SELECT goods_name,price FROM goods_table';

        $result = mysqli_query($link, $query);

        while ($row = mysqli_fetch_array($result)) {
            $goods_data[] = $row;
        }
        // 結果セットを開放します
        mysqli_free_result($result);
        // 接続を閉じます
        mysqli_close($link);
    } else {
        print '追加失敗';
    }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新しい商品の追加</title>
    <style type="text/css">
        table,
        td,
        th {
            border: solid black 1px;
        }

        table {
            width: 200px;
        }
    </style>
</head>

<body>

    <form method="POST">
        <h2>追加したい商品と価格を入力してください</h2>
        <p>商品名: <input type="text" name="name"> 価格:<input type="text" name="price"><input type="submit" value="追加"></p>
    </form>

    <h3>商品一覧</h3>
    <table>
        <tr>
            <th>商品名</th>
            <th>価格</th>
        </tr>


        <!-- 追加ボタンを押す前のデータベースの情報を表示したい。 -->


        <!-- エラーが出ていた場合 -->
        <?php foreach ($error as $value) { ?>
            <p><?php print $value; ?></p>
        <?php } ?>
        <!-- データベースに追加後のテーブル情報↓ -->
        <?php
        foreach ($goods_data as $value) {
        ?>
            <tr>
                <td><?php print htmlspecialchars($value['goods_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php print htmlspecialchars($value['price'], ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
        <?php
        }
        ?>
    </table>
</body>

</html>