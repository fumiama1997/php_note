<!-- 次回やること
指定ドリンクの在庫を確認し、もし在庫がなかった場合はエラーメッセージを表示して、ドリンクを購入することはできない（確認手順の例は以下になります）。
購入ページで、在庫がある商品を表示する。


もう１つのブラウザで管理ページを表示させて、購入予定の在庫がある商品の在庫数を0に変更する（在庫なしにする）。
別のブラウザで開いていた購入ページで、在庫数を0に変更した商品を選択して、購入するボタンを押した場合、エラーメッセージが表示されること

指定ドリンクのステータスを確認し、もしステータスが非公開の場合はエラーメッセージを表示して、ドリンクを購入することはできない（確認手順の例は以下になります）。
購入ページで、ステータスが公開の商品を表示する。
もう１つのブラウザで管理ページを表示させて、購入予定のステータスが公開である商品について、ステータスを非公開に変更する（ステータスを非公開にする）。
別のブラウザで開いていた購入ページで、ステータスを非公開に変更した商品を選択して、購入するボタンを押した場合、エラーメッセージが表示されること。
ドリンクの購入が正常に完了した場合、指定ドリンクと購入日時の情報をデータベースに保存する（※この要件は必須ではなく任意です）。 -->

<?php
$host   = 'localhost';
$user   = 'root';
$passwd = 'narait';
$dbname = 'drink';
$name = '';
$money = 0;
var_dump($_POST);
if ($link = mysqli_connect($host, $user, $passwd, $dbname)) {

    mysqli_set_charset($link, 'UTF8');
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if ((isset($_POST['information'])) === false) {
            $error[] = '商品を選択してください';
        } else if (isset($_POST['information'])) {
            $information = explode(" ", $_POST['information']);
            $drink_id = $information[0];
            $price = $information[1];

            if ($drink_id === '') {
                $error[] = 'drink_idが入力されていません';
            } else if (is_numeric($drink_id) === false) {
                $error[] = 'drink_idが不正です';
            }

            if ($price === '') {
                $error[] = '商品の価格が空です';
            } else if ((is_numeric($price)) === false) {
                $error[] = '商品の価格が不正です';
            }
        }
        if (isset($_POST['money'])) {
            $money = $_POST['money'];

            if ($money === '') {
                $error[] = 'お金を投入してください';
            } else if ((is_numeric($money)) === false) {
                $error[] = 'お金は半角数字を入力してください';
            } else if ((isset($price)) && ((intval($money)) < (intval($price)))) {
                $error[] = 'お金が足りません！';
            }
        }

        // 結果表示ページに必要な情報を取得する
        if (empty($error)) {
            $query = 'SELECT it.picture,it.name,it.price,st.stock FROM information_table AS it JOIN stock_table AS st ON  it.drink_id = st.drink_id WHERE it.drink_id = ' . $drink_id . '';
            if (($result = mysqli_query($link, $query)) !== false) {
                $row = mysqli_fetch_assoc($result);
                mysqli_free_result($result);
                if (isset($row)) {
                    $picture = $row['picture'];
                    $name = $row['name'];
                    $price = $row['price'];
                    $stock = $row['stock'];
                    $money = $money - $price;
                }
            } else {
                $error[] = 'SQL失敗:';
            }
        }

        //選択した商品の在庫を減らす
        if ((empty($error))||(intval($stock) > 0 )) {
            $query = 'UPDATE stock_table SET stock = stock - 1 WHERE drink_id = ' . $drink_id . ' ';
            if (($result = mysqli_query($link, $query)) !== true) {
                $err_msg[] = 'SQL失敗:' .   $query;
            }
        }else if(isset($error)){
            $error[] = 'SQL失敗:';
        }else if((intval($stock) === 0)){
            $error[] = '売り切れました';
        }

        mysqli_close($link);
    }
}



?>




<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>結果ページ</title>
</head>

<body>
    <h1>自動販売機結果</h1>

    <?php if ((empty($error))||(intval($stock) > 0 )) { ?>
        <img src="picture\<?php print htmlspecialchars($picture, ENT_QUOTES, 'UTF-8'); ?>">
        <p>がしゃん！[<?php print $name; ?>]が買えました！</p>
        <p>おつりは[<?php print $money; ?>円]です</p>

    <?php } else { ?>
        <?php foreach ($error as $value) { ?>
            <p><?php print $value; ?></p>
        <?php } ?>

    <?php } ?>
    <a href="index.php">戻る</a>
</body>

</html>