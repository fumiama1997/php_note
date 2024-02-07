<!-- ひとこと掲示板にて、利用者の過去発言内容をテキストファイルではなくデータベースで管理するよう、プログラム改修をしてください。(要件の変更点は赤文字部分のみ)

ファイル名: htdocs/php16/practice_bbs_db_intermediate.php

利用者が名前とコメントを入力し、発言できる。
利用者の過去発言内容をデータベースで管理する。
全ての利用者の過去発言を一覧で見ることができ、「名前、コメント、発言日時」の最低限3つを1行ずつ表示する。
利用者の名前は最大20文字以内とし、それ以上の場合はエラーメッセージを表示し、発言できないようにする。
利用者のコメントは最大100文字以内とし、それ以上の場合はエラーメッセージを表示し、発言できないようにする。
利用者の名前、コメントのどちらか又は両方が未入力だった場合、エラーメッセージを表示し、発言できないようにする。
テーブルの詳細は自由です。適切な設定をしてください。 -->
<?php
$comment = '';
$name = '';
$nameLimit = '20';
$commentLimit = '100';
$error = [];
$board_data = [];

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

$query = 'SELECT board_id,board_name,comment,datetime FROM board';
$result = mysqli_query($link, $query);

while ($row = mysqli_fetch_array($result)) {
    $goods_alldata[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = $_POST['name'];
    $comment = $_POST['comment'];
    // 名前の入力チェック、20文字以内かをチェック
    if ($_POST['name'] === '') {
        $error['name'] = '名前を入力してください';
    } else if (strlen($_POST['name']) > 20) {
        $error['name'] = '名前は20文字以内で入力してください';
    }
    // ひとことの入力チェック、100文字以内かをチェック
    if ($_POST['comment'] === '') {
        $error['comment'] = 'ひとことを入力してください';
    } else if (mb_strlen($_POST['comment']) > 100) {
        $error['comment'] = 'ひとことは100文字以内で入力してください';
    }

    // 正常処理
    if (empty($error)) {
        // 日付を取得
        $date = date('Y-m-d H:i:s');
        // クエリを作成
        $query = "INSERT INTO board(board_name,comment,datetime) VALUES
        ('$name','$comment','$date')";

        //追加 クエリを実行します  
        $result = mysqli_query($link, $query);

        print '追加成功';

        //全レコードを取得
        $query = 'SELECT board_id,board_name,comment,datetime FROM board';
        //上記クエリを実行
        $result = mysqli_query($link, $query);

        while ($row = mysqli_fetch_array($result)) {
            $board_data[] = $row;
        }
        // 結果セットを開放します
        mysqli_free_result($result);
        // 接続を閉じます
        mysqli_close($link);
    }
}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> ひとこと掲示板</title>
</head>

<body>
    <h1>ひとこと掲示板</h1>
    <form method="POST">
        <p>名前: <input type="text" name="name"> ひとこと: <input type="text" name="comment"> <input type="submit" value="送信"></p>
    </form>

    <p>発言一覧</p>
    <?php
    if (empty($_POST)) {
        foreach ($goods_alldata as $value) {
    ?>

            <p><?php print htmlspecialchars($value['board_name'], ENT_QUOTES, 'UTF-8'); ?>
                <?php print htmlspecialchars($value['comment'], ENT_QUOTES, 'UTF-8'); ?>
                <?php print htmlspecialchars($value['datetime'], ENT_QUOTES, 'UTF-8'); ?></p>

    <?php
        }
    }
    ?>
    <?php foreach ($board_data as $value) { ?>

        <p><?php print htmlspecialchars($value['board_name'], ENT_QUOTES, 'UTF-8'); ?>: <?php print htmlspecialchars($value['comment'], ENT_QUOTES, 'UTF-8'); ?>: <?php print htmlspecialchars($value['datetime'], ENT_QUOTES, 'UTF-8'); ?> </p>

    <?php  } ?>


    <?php foreach ($error as $value) { ?>
        <p><?php print $value; ?></p>
    <?php } ?>












</body>

</html>