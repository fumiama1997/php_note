<!-- コイントスをセレクトボックスで指定した回数(10 or 100 or 1000)行い、表と裏が出た回数を表示するプログラムを作成してください。 -->
<?php

//表と裏をランダムに出す
$direction = array('1', '2');

$table_count = '0';
$inside_count = '0';

if (intval($_POST['number']) === 10) {
    
    for ($i = 0; $i < 10; $i = $i + 1) {
          $array_rnd = array_rand($direction, 1);
        if ($array_rnd == '1') {
            $table_count = $table_count + 1;
        } else {
            $inside_count = $inside_count + 1;
        }
    }
} elseif (intval($_POST['number']) === 100) {
    for ($i = 0; $i < 100; $i = $i + 1) {
        $array_rnd = array_rand($direction, 1);
        if ($array_rnd == '1') {
            $table_count = $table_count + 1;
        } else {
            $inside_count = $inside_count + 1;
        }
    }
} elseif (intval($_POST['number']) === 1000) {
    for ($i = 0; $i < 1000; $i = $i + 1) {
        $array_rnd = array_rand($direction, 1);
        if ($array_rnd == '1') {
            $table_count = $table_count + 1;
        } else {
            $inside_count = $inside_count + 1;
        }
    }
}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>コイントス</title>
</head>

<body>

    <article id="wrap">
        <section>
            <p>表:<?php print $table_count; ?> 回</p>
            <p>裏:<?php print $inside_count; ?> 回</p>
        </section>
        <form method="POST">
            <select name="number">
                <option value="">回数選択</option>
                <option value='10' name="number">10</option>
                <option value='100' name="number">100</option>
                <option value='1000' name="number">1000</option>
            </select>回
            <button type="submit">コイントス</button>
        </form>
    </article>

</body>

</html>