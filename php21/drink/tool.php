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
        if (isset($_POST['insert'])) {

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
                    $drink_id = mysqli_insert_id($link);
                    // stock_tableへの情報追加
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

        //在庫数変更部分
        if (isset($_POST['stock_change'])) {
            $drink_id = $_POST['drink_id'];
            $stock = $_POST['stock'];
            if (is_numeric($drink_id) === false) {
                $error[] = 'idの値が不正です';
            }
            if ($stock === '') {
                $error[] = '個数を入力してください';
            } else if (preg_match($regexp_half_size_number, $stock, $macths) === 0) {
                $error[] = '個数は半角数字を入力してください';
            }
            if (empty($error)) {
                mysqli_autocommit($link, false);
                $query = 'UPDATE stock_table set stock = ' . $stock . ' WHERE drink_id = ' . $drink_id . ' ';
                if (($result = mysqli_query($link, $query)) === false) {
                    $error[] = '在庫変更失敗';
                } else {
                    $date = date('y:m:d H:i:s');
                    $query = 'UPDATE stock_table set update_date = "' . $date . '" WHERE drink_id = ' . $drink_id . ' ';
                    if (($result = mysqli_query($link, $query)) === false) {
                        $error[] = '更新日付変更失敗';
                    } else if (count($error) === 0) {
                        // 処理確定
                        mysqli_commit($link);
                        $change = '在庫変更成功';
                    } else {
                        // 処理取消
                        mysqli_rollback($link);
                    }
                }
            }
        }



        //ステータス変更時
        if (isset($_POST['status_change'])) {
            $drink_id = $_POST['drink_id'];
            $status = $_POST['status'];
            if (is_numeric($drink_id) === false) {
                $error[] = 'idの値が不正です';
            }
            if ((($status === '0') || ($status === '1')) === false) {
                $error[] = '公開ステータスの値が不正です';
            }
            if (empty($error)) {
                mysqli_autocommit($link, false);
                $query = 'UPDATE information_table set status = ' . $status . ' WHERE drink_id = ' . $drink_id . ' ';
                if (($result = mysqli_query($link, $query)) === false) {
                    $error[] = 'ステータス変更失敗';
                } else {
                    $date = date('y:m:d H:i:s');
                    $query = 'UPDATE information_table set update_date = "' . $date . '" WHERE drink_id = ' . $drink_id . ' ';
                    if (($result = mysqli_query($link, $query)) === false) {
                        $error[] = '更新日付変更失敗';
                    } else if (count($error) === 0) {
                        // 処理確定
                        mysqli_commit($link);
                        $change = 'ステータス変更成功';
                    } else {
                        // 処理取消
                        mysqli_rollback($link);
                    }
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
        <input type="submit" value="■□■□■商品追加■□■□■" name="insert">
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
                        <input type="submit" value="変更" name="stock_change">
                        <input type="hidden" name="drink_id" value="<?php print $value['drink_id']; ?>">
                    </td>
                </form>
                <!-- ステータス変更部分 -->
                <form method="post">
                    <?php if ($value['status'] === '1') { ?>
                        <td><input type="submit" value="公開→非公開" name="status_change"></td>
                        <?php $value['status'] = '0'; ?>
                    <?php } else if ($value['status'] === '0') { ?>
                        <td><input type="submit" value="非公開→公開" name="status_change"></td>
                        <?php $value['status'] = '1'; ?>
                    <?php } ?>

                    <input type="hidden" name="status" value="<?php print $value['status']; ?>">
                    <input type="hidden" name="drink_id" value="<?php print $value['drink_id']; ?>">
                    </td>
                </form>
            </tr>

        <?php }; ?>

    </table>
</body>

</html>