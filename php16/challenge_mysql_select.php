<?php
$emp_data = [];
$host = 'localhost';
$username = 'root';
$passwd   = 'narait';
$dbname   = 'user';
$link = mysqli_connect($host, $username, $passwd, $dbname);

if ($link) {

    mysqli_set_charset($link, 'utf8');
}
$query = 'SELECT emp_id,emp_name,job,age FROM emp_table';

$result = mysqli_query($link, $query);

while ($row = mysqli_fetch_array($result)) {
    $emp_alldata[] = $row;
}

if (isset($_POST['job']) === TRUE) {

    $job = $_POST['job'];
    switch ($job) {
        case 'all':
            $query = 'SELECT emp_id,emp_name,job,age FROM emp_table';
            break;
        case 'manager':
            $query = 'SELECT emp_id,emp_name,job,age FROM emp_table WHERE job = "manager"';
            break;
        case 'analyst':
            $query = 'SELECT emp_id,emp_name,job,age FROM emp_table WHERE job = "analyst"';
            break;
        case 'clerk':
            $query = 'SELECT emp_id,emp_name,job,age FROM emp_table WHERE job = "clerk"';
            break;
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

    <form method="POST" action="./challenge_mysql_select.php">
        <select name="job">
            <option value="all">全員</option>
            <option value="manager">マネージャー</option>
            <option value="analyst">アナリスト</option>
            <option value="clerk">一般職</option>
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
        if (empty($_POST)) foreach ($emp_alldata as $value) {
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