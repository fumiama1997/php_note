<!-- ユーザがドリンクを購入する「購入ページ」

以下の要件を満たすように作成してください。
[購入ページ]（ファイル名: htdocs/drink/index.php）
ステータスが「公開」のドリンク情報（「商品画像」「商品名」「値段」）を一覧で表示する。
金額を投入するテキストボックスを作成する。
ドリンクを選択するラジオボタンを作成する。
ドリンクの在庫が0の場合、ドリンクを選択するラジオボタンは表示せず、「売り切れ」と表示する。
購入ボタンを押すと購入結果ページへ遷移する。 -->
<?php
date_default_timezone_set('Asia/Tokyo');

// MySQL接続情報
$host   = 'localhost'; // データベースのホスト名又はIPアドレス
$user   = 'root';  // MySQLのユーザ名
$passwd = 'narait';    // MySQLのパスワード
$dbname = 'drink';    // データベース名


// コネクション取得
if ($link = mysqli_connect($host, $user, $passwd, $dbname)) {
    // 文字コードセット
    mysqli_set_charset($link, 'UTF8');

    $query =  'SELECT it.drink_id,it.picture,it.name,it.price,st.stock From information_table AS it JOIN stock_table AS st ON it.drink_id = st.drink_id';
    $result = mysqli_query($link, $query);

    // データを配列に入れる。
    while ($row = mysqli_fetch_array($result)) {
        $goods_data[] = $row;
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
    <title>購入フォーム</title>
    <style>
        div {
            text-align: center;
        }

        .row {
            width: 500px;
            display: flex;
            flex-wrap: wrap;
        }

        .child {
            width: calc(100%/4);
        }

        img {
            width: 100px;
            height: 120px;
        }

        .red {
            color: red;
        }

        p {
            margin: 0px;
        }
    </style>
</head>

<body>
    <h1>自動販売機</h1>
    <form method="post" action="./result.php">
        <p>金額 <input type="text" name="money"></p>
        <div class="row">
            <?php foreach ($goods_data as $value) {  ?>
                <div class="child">
                    <div>
                        <img src="picture\<?php print htmlspecialchars($value['picture'], ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                    <div>
                        <?php print htmlspecialchars($value['name'], ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                    <div>
                        <?php print htmlspecialchars($value['price'], ENT_QUOTES, 'UTF-8'); ?>

                    </div>

                    <?php if (($value['stock']) === '0') {  ?>
                        <div>
                            <p class="red">売り切れ</p>
                        </div>
                    <?php } else { ?>
                        <div>
                            <input type="radio" name="information" value="<?php print $value['drink_id']; ?> <?php print $value['price']; ?>">
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
        <input type="submit" value="■□■□■購入■□■□■">
    </form>
    <!-- これ使ったらいいんちゃう？ -->
    <!-- <button type="submit" name="point_gift_id" value="<?php print $point_gift['point_gift_id']; ?>">購入する</button> -->


</body>

</html>