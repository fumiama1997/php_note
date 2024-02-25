<?php
// 16章　selectの課題
// allかそれ以外かで分岐できるように　$jobを有効に使って。要件を追加されてもデータベースの追加だけで済むように。switch文は使わない。
// deforutでallを入れておけばいい←修正済み
$job = 'all';
$emp_data = [];
$host = 'localhost';
$username = 'root';
$passwd   = 'narait';
$dbname   = 'user';
$query = '';
$link = mysqli_connect($host, $username, $passwd, $dbname);
if ($link) {
    mysqli_set_charset($link, 'utf8');

    if (isset($_POST['job'])) {
        $job = $_POST['job'];
    }
    if ($job === 'all') {
        $query = 'SELECT emp_id,emp_name,job,age FROM emp_table';
    } else {
        $query = 'SELECT emp_id,emp_name,job,age FROM emp_table WHERE job = "' . $job . '"';
    }
    $result = mysqli_query($link, $query);
    while ($row = mysqli_fetch_array($result)) {
        $emp_data[] = $row;
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
    <title>課題16-9</title>
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
    <p>表示する職種を選択してください。</p>

    <form method="POST">
        <select name="job">
            <option value="all">全員</option>
            <option value="manager" <?php if ($job === 'manager') {print 'selected';} ?>>マネージャー</option>
            <option value="analyst" <?php if ($job === 'analyst') {print 'selected';} ?>>アナリスト</option>
            <option value="clerk" <?php if ($job === 'clerk') {print 'selected';} ?>>一般職</option>
        </select>
        <input type="submit" value="表示">
    </form>
    <p>社員一覧</p>
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
                <td><?php print htmlspecialchars($value['emp_id'], ENT_QUOTES, 'UTF-8'); ?></td>
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