<?php
$name = '';
$comment = '';
$error = [];

use LDAP\Result;

date_default_timezone_set('Asia/Tokyo');
// データベース接続
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWD', 'narait');
define('DB_NAME', 'user');

define('HTML_CHARACTER_SET', 'UTF-8');
define('DB_CHARACTER_SET', 'UTF8');

$board_data = [];

$link = get_db_connect();

// // リクエストメソッド取得
// $request_method = get_request_method();

// // テーブルへデータを挿入(INSERT)
// $insert_data = insert_table($name, $comment, $link);

// // テーブルからデータを取得(SELECT)
// $board_data = get_table_list($link);

// // // データベース切断
// // close_db_connect($link);

// // 「名前」の入力値をチェック
// $validate_name = check_name($name);

// // 「コメント」の入力値をチェック
// $validate_comment = check_comment($comment);

// // HTMLとして表示する文字をHTMLエンティティに変換する
// $board_data = entity_assoc_array(($board_data));

// $check_empty = check_empty($str);


// // リクエストメソッド取得
// $request_method = get_request_method();

// POSTの場合
if (get_request_method() === 'POST') {

    $name = $_POST['name'];
    $comment = $_POST['comment'];

    if (check_name($name) === false) {
        $error[] = '名前は20文字以内で入力してください';
    }

    if (check_comment($comment) === false) {
        $error[] = 'ひとことは100文字以内で入力してください';
    }

    if (check_empty($name) === true) {
        $error[] = '名前を入力してください';
    }

    if (check_empty($comment) === true) {
        $error[] = 'ひとこと入力してください';
    }
    if (empty($error)) {
        var_dump($_POST);
        insert_table($name, $comment, $link);
    }
}

$board_data = get_table_list($link);
close_db_connect($link);
entity_assoc_array($board_data);



// リクエストメソッドを取得
function get_request_method()
{
    return $_SERVER['REQUEST_METHOD'];
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
        }
    }

    return $assoc_array;
}

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

function get_table_list($link)
{
    // SQL生成
    $sql = 'SELECT board_name,comment,datetime FROM board_table ';
    // クエリ実行
    return get_as_array($link, $sql);
}

function insert_table($name, $comment, $link)
{

    $date = date('Y-m-d H:i:s');
    $sql = 'INSERT INTO board_table(board_name,comment,datetime) VALUES("' . $name . '","' . $comment . '","' . $date . '")';
    if (($result = mysqli_query($link, $sql)) === false) {
        $error[] = '追加失敗';
    }
}





function close_db_connect($link)
{
    //接続を閉じる
    mysqli_close($link);
}

function check_name($name)
{
    if (mb_strlen($name) > 20) {
        return $name = false;
    } else {
        return $name = true;
    }
}

function check_comment($comment)
{
    if (mb_strlen($comment) > 100) {
        return $comment = false;
    } else {
        return $comment = true;
    }
}

function check_empty($str)
{
    if ($str === '') {
        return $str = true;
    } else {
        return $str = false;
    }
}
?>



<!-- // 利用者が名前とコメントを入力し、発言できる。
// 利用者の過去発言内容をデータベースで管理する。
// 全ての利用者の過去発言を一覧で見ることができ、「名前、コメント、発言日時」の最低限3つを1行ずつ表示する。
// 利用者の名前は最大20文字以内とし、それ以上の場合はエラーメッセージを表示し、発言できないようにする。
// 利用者のコメントは最大100文字以内とし、それ以上の場合はエラーメッセージを表示し、発言できないようにする。
// 利用者の名前、コメントのどちらか又は両方が未入力だった場合、エラーメッセージを表示し、発言できないようにする。 -->
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