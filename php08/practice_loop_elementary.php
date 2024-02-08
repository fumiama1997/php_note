<!-- コイントスをセレクトボックスで指定した回数(10 or 100 or 1000)行い、表と裏が出た回数を表示するプログラムを作成してください。 -->
<?php

//表と裏をランダムに出す
$coin = array('t' => 0, 'b' => 0);
$number = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['number'])) {
    $number = $_POST['number'];
    for ($i = 0; $i < $number; $i++) {
        $array_rnd = array_rand($coin, 1);
        if ($array_rnd === 't') {
            $coin['t']++;
        } else {
            $coin['b']++;
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
            <p>表:<?php print $coin['t']; ?> 回</p>
            <p>裏:<?php print $coin['b']; ?> 回</p>
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