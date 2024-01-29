<?php
//全員(all)を選択ならすべてを$emp_data[]に入れる。
//マネージャー(manager)なら emp_id 1,2のみ配列に入れる。
//アナリスト(analyst)ならemp_id 3のみ配列に入れる。
//クラーク(clerk)ならemp_id4のみ配列に入れる。

$emp_data = [];
$job = '';

if (isset($_POST['job']) === TRUE) {

    $job = $_POST['job'];
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
    if ($job === 'all') {
        $query = 'SELECT emp_id,emp_name,job,age FROM emp_table';
    } elseif ($job === 'manager') {
        $query = 'SELECT emp_id,emp_name,job,age FROM emp_table WHERE job = "manager"';
    } elseif ($job === 'analyst') {
        $query = 'SELECT emp_id,emp_name,job,age FROM emp_table WHERE job = "analyst"';
    } elseif ($job === 'clerk') {
        $query = 'SELECT emp_id,emp_name,job,age FROM emp_table WHERE job = "clerk"';
    } else {
        print 'DB接続失敗';
    }

    // クエリを実行します  
    $result = mysqli_query($link, $query);

    while ($row = mysqli_fetch_array($result)) {
        $emp_data[] = $row;
    }
    // 結果セットを開放します
    mysqli_free_result($result);
    // 接続を閉じます
    mysqli_close($link);
}
?>


<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>課題16-9</title>
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
    <h1>表示する職種を選択してください。</h1>


    <form method="POST">
        <select name="job">
            <option value="all">全員</option>
            <option value="manager">マネージャー</option>
            <option value="analyst">アナリスト</option>
            <option value="clerk">一般職</option>
        </select>
        <input type="submit" value="表示">
    </form>
    <table>
        <tr>
            <th>社員番号</th>
            <th>名前</th>
            <th>職種</th>
            <th>年齢</th>
        </tr>

        <?php
        foreach ($emp_data as $value) {
        ?>
            <tr>
                <td></td>
                <td><?php print htmlspecialchars($value['emp_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php print htmlspecialchars($value['job'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php print htmlspecialchars($value['age'], ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
        <?php
        }
        ?>
    </table>
</body>

</html>