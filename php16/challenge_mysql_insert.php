<?php
$goods_data = [];
$error = [];
$insert = '';

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
    $price = $_POST['price'];

    if ($name === '') {
        $error[] = '名前を入力してください';
    }
    if ($price === '') {
        $error[] = '価格を入力してください';
        
    } else if (is_numeric($price) === FALSE) {
        $error[] = '価格は数字で入力してください';
    }

    if (empty($error)) {
        $query =  'INSERT INTO goods_table (goods_name, price) VALUES ("' . $name . '",' . $price . ')';

        $result = mysqli_query($link, $query);

        if ($result === TRUE) {
            $insert = '追加成功';
        }
    } else {
        $insert = '追加失敗';
    }
}

$query = 'SELECT goods_name,price FROM goods_table';
$result = mysqli_query($link, $query);

while ($row = mysqli_fetch_array($result)) {
    $goods_data[] = $row;
}
// 接続を閉じます
mysqli_close($link);
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
    <!-- 結果を表示 -->
    <p><?php print $insert; ?></p>

    <?php if (empty($_POST)) { ?>
        <p>追加したい商品と価格を入力してください</p>
    <?php
    }

    ?>

    <form method="POST">
        <p>商品名: <input type="text" name="name"> 価格:<input type="text" name="price"> <input type="submit" value="追加"></p>
    </form>

    <p>商品一覧</p>
    <table>
        <tr>
            <th>商品名</th>
            <th>価格</th>
        </tr>

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