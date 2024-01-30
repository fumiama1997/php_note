<!-- 郵便番号及び住所を検索するシステムを1から作成してください。以下の要件を満たせば、ソースコード及びデータベースの詳細は自由となります。

郵便番号及び住所の情報をデータベースで管理する。
郵便番号から住所が検索できる。
検索結果は一覧で「郵便番号、住所」の最低限2つを1行ずつ表示する。
郵便番号が未入力だった場合、エラーメッセージを表示する。
郵便番号は7桁の数値のみ検索可能とし、それ以外はエラーメッセージを表示する。
都道府県と市区町村から住所が検索できる。
都道府県、市区町村のどちらか又は両方が未入力だった場合、エラーメッセージを表示する※どちらか片方だけの検索禁止
郵便番号、都道府県、市区町村の入力の前後にある全角及び半角スペースを削除する。入力値チェックや検索はこの後に行う。 例)「 1100001　」→「1100001」
検索結果が10件を超えた場合、表示結果を複数ページに分ける。 ※「前へ」「次へ」のようなリンクによりページ切り替えができる -->
<?php
// 都道府県を配列で定義
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
$regexp_post_code =  '/[0-9]{7}/';
$post_data = [];
$error = [];
$post_code_data = [];
$regexp_prefecture =  '/.*?[都道府県]/';
$regexp_city = '/.*?[市]/';
$regexp_town = '/.*?[町村]/';


var_dump($_POST);

// 都道府県・市区町村から住所が検索できる仕様。
// 都道府県・市区町村のPOSTが飛んできているかの判断
if (isset($_POST['prefecture']) && isset($_POST['city']) && isset($_POST['town'])) {

    $prefecture = $_POST['prefecture'];
    $city = $_POST['city'];
    $town = $_POST['town'];
    $host = 'localhost'; // データベースのホスト名又はIPアドレス
    $username = 'root';  // MySQLのユーザ名
    $passwd   = 'narait';    // MySQLのパスワード
    $dbname   = 'post';    // データベース名
    $link = mysqli_connect($host, $username, $passwd, $dbname);

    // 接続成功した場合
    if ($link) {

        // 文字化け防止
        mysqli_set_charset($link, 'utf8');
    }
    if ($prefecture === '') {
        $error[] = '都道府県名を入力してください<br>';
    } else if (preg_match($regexp_prefecture, $prefecture, $macths) === 0) {
        $error[] = '都道府県名を正しく入力ください';
    }

    if ($city === '') {
        $error[] = '市名を入力してください';
    } else if (preg_match($regexp_city, $city, $macths) === 0) {
        $error[] = '市名を正しく入力ください';
    }

    if ($town === '') {
        $error[] = '町村名を入力してください';
    } else if (preg_match($regexp_town, $town, $macths) === 0) {
        $error[] = '町村名を正しく入力ください';
    }
    if (empty($error)) {
        $query = 'SELECT post_code,prefecture,city,town FROM zip_data_split_3 WHERE prefecture = "' . $prefecture . '" AND city = "'. $city . '" 
        AND town = "'. $town .'"';
        echo $query;

        $result = mysqli_query($link, $query);
        while ($row = mysqli_fetch_array($result)) {
            $post_code_data[] = $row;
        }
        //結果セットを開放します
        mysqli_free_result($result);
        //接続を閉じます
        mysqli_close($link);
        //接続に失敗した場合
    }
}




//郵便番号から都道府県・市町村を選択する仕様
if (isset($_POST['post_code'])) {
    $post_code = $_POST['post_code'];
    $host = 'localhost'; // データベースのホスト名又はIPアドレス
    $username = 'root';  // MySQLのユーザ名
    $passwd   = 'narait';    // MySQLのパスワード
    $dbname   = 'post';    // データベース名
    $link = mysqli_connect($host, $username, $passwd, $dbname);

    // 接続成功した場合
    if ($link) {
        // 文字化け防止
        mysqli_set_charset($link, 'utf8');
    }
    //バリデーション・正規化
    if ($post_code === '') {
        $error[] = '郵便番号を入力してください<br>';
    } else if (preg_match($regexp_post_code, $post_code, $macths) === 0) {
        $error[] = '7桁の数値のみ入力してください';
    }
    if (empty($error)) {
        $query = 'SELECT post_code,prefecture,city,town FROM zip_data_split_3 WHERE post_code = ' . $post_code . ' ';
        // SELECT 'perfecture',city,town FROM zip_data_split_3 WHERE post_code = 6750145
        //クエリを実行します
        $result = mysqli_query($link, $query);

        while ($row = mysqli_fetch_array($result)) {
            $post_data[] = $row;
        }
        //結果セットを開放します
        mysqli_free_result($result);
        //接続を閉じます
        mysqli_close($link);
        //接続に失敗した場合
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
    <form method="POST">
        <p><input type="text" name="post_code">
            <input type="submit" value="検索">
        </p>
    </form>
    <h3>地名から検索</h3>
    <form method="POST">
        <p>都道府県を選択<br><select name="prefecture">
                <?php
                // 都道府県の配列をループさせる
                foreach ($areas as $area) {
                ?>
                    <option><?php print $area; ?></option>
                <?php
                }
                ?>
            </select>
            市 <input type="text" name="city">
            町村<input type="text" name="town"><input type="submit" value="検索"></p>
    </form>

    <!-- ここに検索結果を反映させる -->
    <?php foreach ($error as $value) { ?>
        <p><?php print $value; ?></p>
    <?php } ?>
    <table>
        <tr>
            <th>郵便番号</th>
            <th>県</th>
            <th>市</th>
            <th>町</th>
        </tr>

        <?php
        foreach ($post_data as $value) {
        ?>
            <tr>
                <td><?php print htmlspecialchars($value['post_code'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php print htmlspecialchars($value['prefecture'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php print htmlspecialchars($value['city'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php print htmlspecialchars($value['town'], ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
        <?php  } ?>




        <?php
        foreach ($post_code_data as $value) {
        ?>
            <tr>
                <td><?php print htmlspecialchars($value['post_code'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php print htmlspecialchars($value['prefecture'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php print htmlspecialchars($value['city'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php print htmlspecialchars($value['town'], ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>

        <?php  } ?>
    </table>

</body>

</html>