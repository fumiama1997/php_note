<?php
$name = '';
$comment = '';
$error = [];

use LDAP\Result;

date_default_timezone_set('Asia/Tokyo');

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWD', 'narait');
define('DB_NAME', 'user');

define('HTML_CHARACTER_SET', 'UTF-8');
define('DB_CHARACTER_SET', 'UTF8');

$board_data = [];

// データベース接続
$link = get_db_connect();

// POSTの場合
if (get_request_method() === 'POST') {

    $name = $_POST['name'];
    $comment = $_POST['comment'];
    // 「名前」の入力値をチェック
    if (check_empty($name) === true) {
        $error[] = '名前を入力してください';
    } else if (check_name($name) === false) {
        $error[] = '名前は20文字以内で入力してください';
    }
    // 「コメント」の入力値をチェック
    if (check_empty($comment) === true) {
        $error[] = 'ひとこと入力してください';
    } else if (check_comment($comment) === false) {
        $error[] = 'ひとことは100文字以内で入力してください';
    }

    // 正常処理
    if (empty($error)) {
        // テーブルへデータを挿入(INSERT)
        insert_table($name, $comment, $link);
    }
}

// テーブルからデータを取得(SELECT)
$board_data = get_table_list($link);

// データベース切断
close_db_connect($link);

// HTMLとして表示する文字をHTMLエンティティに変換する
entity_assoc_array($board_data);


function get_db_connect()
{
    // コネクション取得
    if (!$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWD, DB_NAME)) {
        die('error: ' . mysqli_connect_error());
    }

    // 文字コードセット
    mysqli_set_charset($link, DB_CHARACTER_SET);

    return $link;
}

// リクエストメソッドを取得
function get_request_method()
{
    return $_SERVER['REQUEST_METHOD'];
}

function check_name($name)
{
    if (mb_strlen($name) > 20) {
        return false;
    } else {
        return true;
    }
}

function check_comment($comment)
{
    if (mb_strlen($comment) > 100) {
        return false;
    } else {
        return true;
    }
}

function check_empty($str)
{
    if ($str === '') {
        return  true;
    } else {
        return  false;
    }
}

function insert_table($name, $comment, $link)
{

    $date = date('Y-m-d H:i:s');
    $sql = 'INSERT INTO board_table(board_name,comment,datetime) VALUES("' . $name . '","' . $comment . '","' . $date . '")';
    if (($result = mysqli_query($link, $sql)) === false) {
        $error[] = '追加失敗';
    }
}

function get_table_list($link)
{
    // SQL生成
    $sql = 'SELECT board_name,comment,datetime FROM board_table ';
    // クエリ実行
    return get_as_array($link, $sql);
}

function get_as_array($link, $sql)
{
    // 返却用配列
    $data = [];

    // クエリを実行する
    if ($result = mysqli_query($link, $sql)) {

        if (mysqli_num_rows($result) > 0) {
            // １件ずつ取り出す
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
        }
        mysqli_free_result($result);
    }
    return $data;
}


function close_db_connect($link)
{
    //接続を閉じる
    mysqli_close($link);
}

function entity_str($str)
{
    return htmlspecialchars($str, ENT_QUOTES, HTML_CHARACTER_SET);
}

function entity_assoc_array($assoc_array)
{

    foreach ($assoc_array as $key => $value) {

        foreach ($value as $keys => $values) {
            // 特殊文字をHTMLエンティティに変換
            $assoc_array[$key][$keys] = entity_str($values);
            var_dump($assoc_array[$key][$keys]);
        }
    }

    return $assoc_array;
}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ひとこと掲示板</title>
    <style type="text/css">
        table,
        td,
        th {
            border: solid black 1px;
        }

        table {
            width: 500px;
        }
    </style>
</head>

<body>
    <form method="post">
        <h1>ひとこと掲示板</h1>
        <p>名前: <input type="text" name="name"> ひとこと: <input type="text" name="comment"> <input type="submit" name="send" value="送信する"></p>
    </form>

    <?php foreach ($error as $value) { ?>
        <p><?php print $value; ?></p>
    <?php } ?>


    <p>発言一覧</p>

    <table>
        <tr>
            <th>名前</th>
            <th>コメント</th>
            <th>発言日時</th>
        </tr>
        <?php foreach ($board_data as $value) { ?>
            <tr>
                <td><?php print $value['board_name']; ?></td>
                <td><?php print $value['comment']; ?></td>
                <td> <?php print $value['datetime']; ?></td>
            </tr>
        <?php  } ?>

    </table>
</body>

</html>