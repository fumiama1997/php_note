<?php
$result = false;
$player = '';
$com = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hand'])) {

    $janken = array('グー', 'チョキ', 'パー');
    $player = htmlspecialchars($_POST['hand'], ENT_QUOTES, 'UTF-8');
    $com = $janken[array_rand($janken)];

    if ($player === 'グー' && $com === 'チョキ') {
        $result = '勝ち';
    } elseif ($player === 'グー' && $com === 'グー') {
        $result = 'あいこ';
    } elseif ($player === 'グー' && $com === 'パー') {
        $result = '負け';
    } elseif ($player === 'チョキ' && $com === 'チョキ') {
        $result = 'あいこ';
    } elseif ($player === 'チョキ' && $com === 'グー') {
        $result = '負け';
    } elseif ($player === 'チョキ' && $com === 'パー') {
        $result = '勝ち';
    } elseif ($player === 'パー' && $com === 'チョキ') {
        $result = '負け';
    } elseif ($player === 'パー' && $com === 'グー') {
        $result = '勝ち';
    } elseif ($player === 'パー' && $com === 'パー') {
        $result = 'あいこ';
    }
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>じゃんけん</title>
</head>

<body>
    <h1>じゃんけん勝負</h1>
    <form method="POST">
        <input type="radio" name="hand" value="グー">グー
        <input type="radio" name="hand" value="チョキ">チョキ
        <input type="radio" name="hand" value="パー">パー
        <input type="submit" value="送信">
    </form>

    <?php if ($result); ?>
    <p>結果</p>

    <p>
        自分: <?php print $player; ?><br>
        相手: <?php print $com; ?>
    </p>

    <p><?php print $result; ?>です</p>

</body>

</html>