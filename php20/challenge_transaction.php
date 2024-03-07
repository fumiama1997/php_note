<?php
date_default_timezone_set('Asia/Tokyo');

// MySQL接続情報
$host   = 'localhost'; // データベースのホスト名又はIPアドレス
$user   = 'root';  // MySQLのユーザ名
$passwd = 'narait';    // MySQLのパスワード
$dbname = 'transaction';    // データベース名

$customer_id = 1;      // 顧客は1に固定
$message     = '';     // 購入処理完了時の表示メッセージ
$point       = 0;      // 保有ポイント情報
$err_msg     = [];     // エラーメッセージ
$point_gift_list = []; // ポイントで購入できる景品

// コネクション取得
if ($link = mysqli_connect($host, $user, $passwd, $dbname)) {
    // 文字コードセット
    mysqli_set_charset($link, 'UTF8');

    // 現在のポイント保有情報を取得するためのSQL
    $sql = 'SELECT point FROM point_customer_table WHERE customer_id = ' . $customer_id;
    if ($result = mysqli_query($link, $sql)) {
        // １件取得
        $row = mysqli_fetch_assoc($result);
        // 変数に格納
        if (isset($row['point']) === TRUE) {
            $point = $row['point'];
        }
    } else {
        $err_msg[] = 'SQL失敗:' . $sql;
    }
    mysqli_free_result($result);

    // POSTの場合はポイントでの景品購入処理
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // ここに購入時の処理を記載してください
        if (isset($_POST['point_gift_id'])) {
            $point_gift_id = $_POST['point_gift_id'];

            if ($point_gift_id === '') {
                $err_msg[] = 'point_gift_idが入力されていません';
            } else if (is_numeric($point_gift_id) === false) {
                $err_msg[] = 'point_gift_idの値が不正です';
            }

            if (empty($err_msg)) {
                // 選んだ商品のnameと商品のポイント数を取得するためのSQL
                $sql = 'SELECT name,point FROM point_gift_table WHERE point_gift_id = ' . $point_gift_id . '';
                if (($result = mysqli_query($link, $sql)) !== false) {
                    $row = mysqli_fetch_assoc($result);
                    if (isset($row)) {
                        $name = $row['name'];
                        $goods_point = $row['point'];
                        //ポイント残数を取得する
                        $point =  $point - $goods_point;
                    }
                } else {
                    $err_msg[] = 'SQL失敗:';
                }
            }
            // 上記のクエリ実行が成功していれば
            if (empty($err_msg)) {
                // 更新系の処理を行う前にトランザクション開始(オートコミットをオフ）
                mysqli_autocommit($link, false);
                // 現在時刻を取得
                $date = date('Y-m-d H:i:s');
                $data = [
                    'customer_id' => $customer_id,
                    'point_gift_id' => $point_gift_id,
                    'created_at' => $date
                ];
                // point_history_tableへ購入履歴をINSERT
                $point_history_sql = 'INSERT INTO point_history_table(customer_id,point_gift_id,created_at) VALUES(\'' . implode('\',\'', $data) . '\')';
                if (($result = mysqli_query($link, $point_history_sql)) === false) {
                    $err_msg[] = 'SQL失敗:';
                }
            }
            // 上記のクエリ実行が成功していれば
            if (empty($err_msg)) {
                //ポイント残数をデータベースに反映する。
                $update_point_sql = 'UPDATE point_customer_table SET point = ' . $point . ' WHERE customer_id = ' . $customer_id . '';
                if ($result = mysqli_query($link, $update_point_sql) === FALSE) {
                    $err_msg[] = 'SQL失敗:' .  $update_point_sql;
                };
                // トランザクション成否判定
                if (count($err_msg) === 0) {
                    // 処理確定
                    mysqli_commit($link);
                    $message = '景品 [' . $name . '] を購入しました。';
                } else {
                    // 処理取消
                    mysqli_rollback($link);
                }
            }
        }
    }
    // SQL
    $sql = 'SELECT point_gift_id, name, point FROM point_gift_table';
    // クエリ実行
    if ($result = mysqli_query($link, $sql)) {
        $i = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            $point_gift_list[$i]['point_gift_id'] = htmlspecialchars($row['point_gift_id'], ENT_QUOTES, 'UTF-8');
            $point_gift_list[$i]['name']       = htmlspecialchars($row['name'],       ENT_QUOTES, 'UTF-8');
            $point_gift_list[$i]['point']      = htmlspecialchars($row['point'],      ENT_QUOTES, 'UTF-8');
            $i++;
        }
    } else {
        $err_msg[] = 'SQL失敗:' . $sql;
    }

    mysqli_free_result($result);
    mysqli_close($link);
} else {
    $err_msg[] = 'error: ' . mysqli_connect_error();
}
?>
<!DOCTYPE HTML>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>トランザクション課題</title>
</head>

<body>
    <!-- $messageの中身が空でなければ -->
    <?php if (empty($message) !== TRUE) { ?>
        <!-- 購入した景品をブラウザに表示する。 -->
        <p><?php print $message; ?></p>
    <?php } ?>
    <section>
        <h1>保有ポイント</h1>
        <!-- ポイント残数を表示 -->
        <p><?php print number_format($point); ?>ポイント</p>
    </section>
    <section>
        <h1>ポイント商品購入</h1>
        <form method="post">
            <ul>
                <?php foreach ($point_gift_list as $point_gift) { ?>
                    <li>
                        <span><?php print $point_gift['name']; ?></span>
                        <span><?php print number_format($point_gift['point']); ?>ポイント</span>
                        <?php if ($point_gift['point'] <= $point) { ?>
                            <!-- 購入ボタンを押すと購入した商品のpoint_gift_idがpostされる -->
                            <button type="submit" name="point_gift_id" value="<?php print $point_gift['point_gift_id']; ?>">購入する</button>
                            <!-- ポイント残数よりも、景品のポイントの方が大きければ購入不可になる仕様。 -->
                        <?php        } else { ?>
                            <button type="button" disabled="disabled">購入不可</button>
                        <?php        } ?>
                    </li>
                <?php    } ?>
            </ul>
        </form>
        <p>※サンプルのためポイント購入は1景品 & 1個に固定</p>
    </section>
</body>

</html>