<?php
$date = date('Y'); //現在の西暦を取得
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ループの使用例</title>
</head>

<body>
    <form action="#">
        生まれた西暦を選択してください
        <select name="born_year">
            <?php
            for ($i = 1900; $i <= $date; $i++) {
            ?>
            <option value="<?php print $i; ?>"><?php print $i; ?>年</option>
            <?php
            }
            ?>
        </select>
    </form>

</body>

</html>