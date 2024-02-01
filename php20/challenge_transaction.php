<?php

/**
 * 必要テーブル
 * point_gift_table: ポイントで購入可能な景品
 * point_customer_table: ユーザーのポイント保有情報
 * point_history_table:  ポイントでの購入履歴
 */
// 「購入する」ボタンを押し顧客が景品を購入した際の処理を、
// 用意したソースコードへ追記してください。
// 用意したコードを読み解いた上で変更はせず、追記のみで課題を解いて下さい。

// 購入した際に行うトランザクション処理は以下となります。
// point_history_tableへ購入履歴をINSERT
// point_customer_tableの顧客保有ポイントをUPDTE
// 購入が無事完了したら、購入した景品情報を表示してください。
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

// コネクション取得
if ($link = mysqli_connect($host, $user, $passwd, $dbname)) {

    // 文字コードセット
    mysqli_set_charset($link, 'UTF8');
    /**
     * 保有ポイント情報を取得
     */
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
        /*
        * ここに購入時の処理を記載してください
        * 既存のソースを変更したい場合、変更が必要な理由を講師に説明し、許可をとってください。
        */
        //postで飛んできた情報
        $point_gift_id = $_POST['point_gift_id'];
        //飛んできた情報を使って商品のポイント額を取得する
        $gift_point_sql = 'SELECT point_gift_table.point WHERE  point_gift_id = ' . $point_gift_id . '';

        if ($result = mysqli_query($link, $gift_point_sql)) {
            // １件取得
            $row = mysqli_fetch_assoc($result);
            // 変数に格納
            if (isset($row['point']) === TRUE) {
                $point = $row['point'];
            }
        }

        //ポイント残数としてブラウザに表示する。
        $Remaining_point_sql = 'SELECT point_customer_table.point - ' . $point . ' AS Remaining_Point FROM point_customer_table JOIN point_history_table ON point_customer_table.customer_id = point_history_table.customer_id JOIN point_gift_table ON point_history_table.point_gift_id = point_gift_table.point_gift_id';
        // クエリ実行
        if ($result = mysqli_query($link, $Remaining_point_sql)) {
            // １件取得
            $row = mysqli_fetch_assoc($result);
            // 変数に格納
            if (isset($row['point']) === TRUE) {
                $point = $row['point'];
            }
        }
        //ポイント残数をデータベースに反映する。
        $Update_point_sql = 'UPDATE point_customer_table SET point = ' . $Remaining_point . ' WHERE ';
        //クエリの実行
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
//var_dump($err_msg); // エラーの確認が必要ならばコメントを外す
?>
<!DOCTYPE HTML>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>トランザクション課題</title>
</head>

<body>
    <!-- 購入したものの表示 -->
    <!-- 残ポイントの反映を表示 -->
    <?php if (empty($message) !== TRUE) { ?>
        <p><?php print $message; ?></p>
    <?php } ?>
    <section>
        <h1>保有ポイント</h1>
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
        <p><?php print $Remaining_Point; ?></p>
    </section>
</body>

</html>