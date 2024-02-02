<?php
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
$point_gift_id = '';
$message = ''; //購入した商品を入れる変数
// コネクション取得
if ($link = mysqli_connect($host, $user, $passwd, $dbname)) {

    // 文字コードセット
    mysqli_set_charset($link, 'UTF8');

    // 現在のポイント保有情報を取得するためのSQL
    $sql = 'SELECT point FROM point_customer_table WHERE customer_id = ' . $customer_id;
    // クエリ実行
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

        //postで飛んできた情報
        $point_gift_id = $_POST['point_gift_id'];
        switch ($point_gift_id) {
            case 1:
                $message = '景品[コーラ]を購入しました。';
                break;
            case 2:
                $message = '景品[USB]を購入しました。';
                break;
            case 3:
                $message = '景品[傘]を購入しました。';
                break;
            case 4:
                $message = '景品[お茶]を購入しました。';
                break;
            case 5:
                $message = '景品[サイダー]を購入しました。';
                break;
        }

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

        //クエリの実行が失敗した場合は
        if (($result = mysqli_query($link, $point_history_sql)) === false) {
            $err_msg[] = 'SQL失敗:';
        }

        //飛んできた情報を使って商品のポイント額を取得する
        $gift_point_sql = 'SELECT point FROM point_gift_table WHERE point_gift_id = ' . $point_gift_id . '';
        // SELECT point FROM point_gift_table WHERE point_gift_id = 1;

        if ($result = mysqli_query($link, $gift_point_sql)) {
            // １件取得
            $row = mysqli_fetch_assoc($result);
            // 変数に格納
            if (isset($row['point']) === TRUE) {
                $point = $row['point'];
            }
        } else {
            $err_msg[] = 'SQL失敗:' . $gift_point_sql;
        }
        //ポイント残数としてブラウザに表示する。
        $remaining_point_sql = 'SELECT point_customer_table.point - ' . $point . ' AS remaining_Point FROM point_customer_table JOIN point_history_table ON point_customer_table.customer_id = point_history_table.customer_id JOIN point_gift_table ON point_history_table.point_gift_id = point_gift_table.point_gift_id WHERE point_customer_table.customer_id = 1';
        // クエリ実行
        if ($result = mysqli_query($link, $remaining_point_sql)) {
            // １件取得
            $row = mysqli_fetch_assoc($result);
            // 変数に格納
            if (isset($row['remaining_Point']) === TRUE) {
                $point = $row['remaining_Point'];
            }
        } else {
            $err_msg[] = 'SQL失敗:' . $remaining_point_sql;
        }
        //ポイント残数をデータベースに反映する。
        $update_point_sql = 'UPDATE point_customer_table SET point = ' . $point . ' WHERE customer_id = ' . $customer_id . '';
        //クエリの実行
        if ($result = mysqli_query($link, $update_point_sql) === FALSE) {
            $err_msg[] = 'SQL失敗:' .  $update_point_sql;
        };
        // トランザクション成否判定
        if (count($err_msg) === 0) {
            // 処理確定
            mysqli_commit($link);
        } else {
            // 処理取消
            mysqli_rollback($link);
        }
    }
    /**
     * 景品情報を取得
     */
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
    <!-- $messageに購入した景品を表示する。 -->
    <?php if (empty($message) !== TRUE) { ?>
        <p><?php print $message; ?></p>
    <?php } ?>
    <section>
        <h1>保有ポイント</h1>
        <!-- 保有ポイントを反映 -->
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
                            <button type="submit" name="point_gift_id" value="<?php print $point_gift['point_gift_id']; ?>">購入する</button>
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