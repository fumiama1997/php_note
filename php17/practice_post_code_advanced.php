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

var_dump($_POST);
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
        $query = 'SELECT COL5,COL6,COL7 FROM zip_data_split_3';
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
    } else {
        print 'DB接続失敗';
    }
}


?>


<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>課題17-6</title>
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
    <p>都道府県を選択
    <form method="POST">
        <select name="prefecture">
            <?php
            // 都道府県の配列をループさせる
            foreach ($areas as $key => $area) {
            ?>
                <option value="<?php print $key; ?>"><?php print $area; ?></option>
            <?php
            }
            ?>
        </select>
        市町村 <input type="text" name="city"> <input type="submit" value="検索">
        </p>
    </form>


    <!-- ここに検索結果を反映させる -->
    <?php
    foreach ($emp_data as $value) {
    ?>
        <p><?php print htmlspecialchars($value['COL5'], ENT_QUOTES, 'UTF-8'); ?></p>
        <p><?php print htmlspecialchars($value['COL6'], ENT_QUOTES, 'UTF-8'); ?></p>
        <p><?php print htmlspecialchars($value['COL7'], ENT_QUOTES, 'UTF-8'); ?></p>
    <?php  } ?>
</body>

</html>