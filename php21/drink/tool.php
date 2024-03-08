<?php
date_default_timezone_set('Asia/Tokyo');
$regexp_half_size_number =  '/^[0-9]+$/';
$regexp_file =  '/^[a-z0-9-_]+.(png|jpeg)$/';
$change = '';
$drink_data = [];
$goods_data = [];
$error = [];
// MySQL接続情報
$host   = 'localhost';
$user   = 'root';
$passwd = 'narait';
$dbname = 'drink';


if ($link = mysqli_connect($host, $user, $passwd, $dbname)) {

    mysqli_set_charset($link, 'UTF8');


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // 新規商品追加時に通るバリデーション
        if ((isset($_POST['name'])) && isset(($_POST['price'])) && isset(($_POST['piece'])) && isset(($_POST['file'])) && isset(($_POST['status']))) {

            $name = $_POST['name'];
            $price = $_POST['price'];
            $piece = $_POST['piece'];
            $file = $_POST['file'];
            $status = $_POST['status'];
            //バリデーション・正規化
            if ($name === '') {
                $error[] = '名前を入力してください';
            }
            if ($price === '') {
                $error[] = '値段を入力してください';
            } else if (preg_match($regexp_half_size_number, $price, $macths) === 0) {
                $error[] = '値段は半角数字を入力してください';
            }
            if ($piece === '') {
                $error[] = '個数を入力してください';
            } else if (preg_match($regexp_half_size_number, $piece, $macths) === 0) {
                $error[] = '個数は半角数字を入力してください';
            }
            if ($file === '') {
                $error[] = 'ファイルを選択してください';
            } else if (preg_match($regexp_file, $file, $macths) === 0) {
                $error[] = 'ファイル形式が異なります。画像ファイルはJPEG又はPNGのみ利用可能です';
            }
            if ((($status === '0') || ($status === '1')) === false) {
                $error[] = '公開ステータスの値が不正です';
            }

            if (empty($error)) {
                mysqli_autocommit($link, false);
                // information_tableへの情報追加
                $date = date('y:m:d H:i:s');
                $query = 'INSERT INTO information_table(name,price,create_date,update_date,status,picture) VALUES("' . $name . '",' . $price . ',"' . $date . '","' . $date . '","' . $status . '","' . $file . '")';
                if (($result = mysqli_query($link, $query)) === false) {
                    $error[] = 'SQL失敗:' . $sql;
                } else {
                    // 新しく追加した商品のdrink_idを取得
                    $query = 'SELECT drink_id FROM information_table ORDER BY drink_id DESC LIMIT 1 ';
                    if ($result = mysqli_query($link, $query)) {
                        // １件取得
                        $row = mysqli_fetch_assoc($result);
                        // 変数に格納
                        if (isset($row['drink_id']) === TRUE) {
                            $drink_id = $row['drink_id'];
                        }
                    } else {
                        $error[] = 'SQL失敗:' . $sql;
                    }
                }
                // stock_tableへの情報追加
                if (empty($error)) {
                    $query = 'INSERT INTO stock_table(drink_id,stock,create_date,update_date) VALUES(' . $drink_id . ',' . $piece . ',"' . $date . '","' . $date . '")';
                    if (($result = mysqli_query($link, $query)) === false) {
                        $error[] = 'SQL失敗:' . $sql;
                    }
                    // トランザクション成否判定
                    if (count($error) === 0) {
                        // 処理確定
                        mysqli_commit($link);
                        $change = '新規商品追加成功';
                    } else {
                        // 処理取消
                        mysqli_rollback($link);
                    }
                }
            }
        }
        // 在庫又はステータスの変更時に共通部分である$_POST['drink_id']をバリデーション。
        if (isset($_POST['drink_id'])) {
            $drink_id = $_POST['drink_id'];
            if (is_numeric($drink_id) === false) {
                $error[] = 'idの値が不正です';
            }

            //ステータス変更時
            if (isset($_POST['status'])) {
                $status = $_POST['status'];
                if ($status === '1') {
                    $status = '0';
                } elseif ($status === '0') {
                    $status = '1';
                } else {
                    $error[] = 'ステータスの値が不正です';
                }
                if (empty($error)) {
                    $query = 'UPDATE information_table set status = ' . $status . ' WHERE drink_id = ' . $drink_id . ' ';
                    $result = mysqli_query($link, $query);
                    if ($result === true) {
                        $change = 'ステータス変更成功';
                    }
                }
            }
            //在庫変更時
            if (isset($_POST['stock'])) {
                $stock = $_POST['stock'];
                if ($stock === '') {
                    $error[] = '個数を入力してください';
                } else if (preg_match($regexp_half_size_number, $stock, $macths) === 0) {
                    $error[] = '個数は半角数字を入力してください';
                }
                if (empty($error)) {
                    $query = 'UPDATE stock_table set stock = ' . $stock . ' WHERE drink_id = ' . $drink_id . ' ';
                    $result = mysqli_query($link, $query);
                    if ($result === true) {
                        $change = '在庫変更成功';
                    } else {
                        $error[] = '在庫変更失敗';
                    }
                }
            }
        }
    }

    $query = 'SELECT it.drink_id,it.picture,it.name,it.price,st.stock,it.status FROM information_table AS it JOIN stock_table AS st ON it.drink_id = st.drink_id ';
    $result = mysqli_query($link, $query);
    // データを配列に入れる。
    while ($row = mysqli_fetch_array($result)) {
        $drink_data[] = $row;
    }
    mysqli_free_result($result);
    mysqli_close($link);
}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>自動販売機</title>
    <style type="text/css">
        table,
        td,
        th {
            border: solid black 1px;
            text-align: center
        }

        table {
            width: 600px;

        }

        img {
            width: 100px;
            height: 120px;
        }
    </style>
</head>

<body>
    <?php foreach ($error as $value) { ?>
        <p><?php print $value; ?></p>
    <?php } ?>
    <p><?php print $change; ?></p>
    <h1>新規商品追加</h1>
    <form method="post">
        <p>名前: <input type="text" name="name"></p>
        <p>値段: <input type="text" name="price"></p>
        <p>個数: <input type="text" name="piece"></p>
        <input type="file" name="file" muitiple><br>
        <select name="status">
            <option value="0">非公開</option>
            <option value="1">公開</option>
            <option value="2">入力チェック用</option>
        </select><br>
        <input type="submit" value="■□■□■商品追加■□■□■">
    </form>
    <hr>
    <h2>商品情報変更</h2>

    <p>商品一覧</p>

    <table>
        <tr>
            <th>商品画像</th>
            <th>商品名</th>
            <th>価格</th>
            <th>在庫数</th>
            <th>ステータス</th>
        </tr>

        <?php foreach ($drink_data as $value) { ?>

            <tr <?php if (($value['status']) === '0') {
                    print 'bgcolor=#cccccc';
                } ?>>
                <td><img src="picture/<?php print htmlspecialchars($value['picture'], ENT_QUOTES, 'UTF-8'); ?>"></td>
                <td><?php print htmlspecialchars($value['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php print htmlspecialchars($value['price'], ENT_QUOTES, 'UTF-8'); ?></td>
                <!-- 在庫数変更部分 -->
                <form method="post">
                    <td><input type="text" size="5" name="stock" value="<?php print $value['stock']; ?>"><br>個<br>
                        <input type="submit" value="変更">
                        <input type="hidden" name="drink_id" value="<?php print $value['drink_id']; ?>">
                    </td>
                </form>
                <!-- ステータス変更部分 -->
                <form method="post">
                    <td><input type="submit" value="<?php
                                                    if (($value['status']) === '1') {
                                                        print '公開→非公開';
                                                    } else {
                                                        print '非公開→公開';
                                                    }; ?>">
                        <input type="hidden" name="status" value="<?php print $value['status']; ?>">
                        <input type="hidden" name="drink_id" value="<?php print $value['drink_id']; ?>">
                    </td>
                </form>
            </tr>

        <?php }; ?>

    </table>
</body>

</html>