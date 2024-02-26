<?php


$areas = [
    '北海道',
    '青森県',
    '岩手県',
    '宮城県',
    '秋田県',
    '山形県',
    '福島県',
    '茨城県',
    '栃木県',
    '群馬県',
    '埼玉県',
    '千葉県',
    '東京都',
    '神奈川県',
    '新潟県',
    '富山県',
    '石川県',
    '福井県',
    '山梨県',
    '長野県',
    '岐阜県',
    '静岡県',
    '愛知県',
    '三重県',
    '滋賀県',
    '京都府',
    '大阪府',
    '兵庫県',
    '奈良県',
    '和歌山県',
    '鳥取県',
    '島根県',
    '岡山県',
    '広島県',
    '山口県',
    '徳島県',
    '香川県',
    '愛媛県',
    '高知県',
    '福岡県',
    '佐賀県',
    '長崎県',
    '熊本県',
    '大分県',
    '宮崎県',
    '鹿児島県',
    '沖縄県',
];
$post_code = '';
$regexp_post_code =  '/^[0-9]{7}$/';
$error = [];
$post_code_data = [];
$regexp_city = '/^.+[市区町村]$/';
$result = false;
$assessment = '';
$query = '';
$prefecture = '';
$city = '';
$town = '';
$all_data = [];
$start = 0;
$view_page = [];
$page = 0;
$max_page = 0;
$contents_sum = 0;

$host = 'localhost'; // データベースのホスト名又はIPアドレス
$username = 'root';  // MySQLのユーザ名
$passwd   = 'narait';    // MySQLのパスワード
$dbname   = 'post';    // データベース名
$link = mysqli_connect($host, $username, $passwd, $dbname);
// 接続成功した場合
if ($link) {
    // 文字化け防止
    mysqli_set_charset($link, 'utf8');


    //郵便番号から都道府県・市町村を表示する仕様
    if (isset($_GET['post_code'])) {
        $post_code = $_GET['post_code'];
        $post_code = str_replace(array(" ", "　"), "", $post_code);

        //バリデーション・正規化
        if ($post_code === '') {
            $error[] = '郵便番号を入力してください<br>';
        } else if (preg_match($regexp_post_code, $post_code, $macths) === 0) {
            $error[] = '郵便番号は7桁の数値のみ入力してください';
        }
    }

    // 都道府県・市区町村から住所が検索できる仕様。
    if (isset($_GET['prefecture']) && isset($_GET['city'])) {
        $prefecture = $_GET['prefecture'];
        $city = $_GET['city'];
        $city = str_replace(array(" ", "　"), "", $city);

        if ($prefecture === '都道府県を選択') {
            $error[] = '都道府県を選択してください<br>';
        } else if ($city === '') {
            $error[] = '市区町村を入力してください';
        } else if (preg_match($regexp_city, $city, $macths) === 0) {
            $error[] = '市区町村を正しく入力ください';
        }
    }
    if (empty($error)) {

        // 1ページに表示する件数を指定
        $max = 10;

        // 必要なデータをすべて取得
        $query = 'SELECT post_code,prefecture,city,town FROM zip_data_split_3 WHERE prefecture = "' . $prefecture . '" AND city = "' . $city .
            '" OR post_code = "' . $post_code . '"';
        $result = mysqli_query($link, $query);

        // データを配列に入れる。
        while ($row = mysqli_fetch_array($result)) {
            $post_code_data[] = $row;
        }

        mysqli_free_result($result);

        $result = true;

        //件数の総数を求めて、必要なページ数を求める。
        $contents_sum = count($post_code_data);
        $max_page = ceil($contents_sum / $max);
        //現在いるページのページ番号を取得
        if (!isset($_GET['page'])) {
            $page = 1;
        } else {
            $page = $_GET['page'];
        }

        //スタートするページを取得
        $start = $max * ($page - 1);

        //取得した全データから必要な分だけ取り出す。
        $view_page = array_slice($post_code_data, $start, $max, true);

        mysqli_close($link);
    }
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>課題17-6</title>
    <style type="text/css">
        table,
        td,
        th {
            border: solid black 1px;
        }

        table {
            width: 400px;
        }
    </style>
</head>

<body>
    <h1>郵便番号検索</h1>



    <h2>郵便番号から検索</h2>

    <form method="get">
        <p><input type="text" name="post_code" placeholder="例)1010001">
            <input type="submit" value="検索">
        </p>
    </form>
    <h3>地名から検索</h3>

    <form method="get">
        <p>都道府県を選択 <select name="prefecture">
                <option>都道府県を選択</option>
                <?php
                // 都道府県の配列をループさせる
                foreach ($areas as $area) {
                ?>
                    <option value="<?php print $area; ?>" <?php if ($prefecture === $area) {
                                                                print 'selected';
                                                            } ?>><?php print $area; ?></option>
                <?php
                }
                ?>
            </select>
            市区町村 <input type="text" name="city" value="<?php print $city; ?>">
            <input type="submit" value="検索">
        </p>
    </form>
    <hr>
    <!-- //クエリの実行が可能であったことが条件($_GETの値がissetされておりかつempty($error)であるということでもある) -->
    <?php if ($result === true) { ?>
        <p>検索結果<?php print  $contents_sum; ?>件</p>
        <?php if ($contents_sum >= 1) { ?>
            <p>郵便番号検索結果</p>
            <table>
                <tr>
                    <th>郵便番号</th>
                    <th>都道府県</th>
                    <th>市区町村</th>
                    <th>町域</th>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <p>ここに検索結果が表示されます</p>
        <?php } ?>
        <?php
        foreach ($view_page as $value) {
        ?>
            <tr>
                <td><?php print htmlspecialchars($value['post_code'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php print htmlspecialchars($value['prefecture'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php print htmlspecialchars($value['city'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php print htmlspecialchars($value['town'], ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
        <?php  } ?>
            </table>
            <?php if ($page > 1) { ?>
                <a href="practice_post_code_advanced.php?prefecture=<?php print $prefecture; ?>&city=<?php print $city; ?>&page=<?php print($page - 1); ?>">前のページ</a>
            <?php }; ?>
            <?php if ($page < $max_page) { ?>
                <a href="practice_post_code_advanced.php?prefecture=<?php print $prefecture; ?>&city=<?php print $city; ?>&page=<?php print($page + 1); ?>">次のページ</a>
            <?php }; ?>

            <?php foreach ($error as $value) { ?>
                <p><?php print $value; ?></p>
            <?php } ?>
</body>

</html>