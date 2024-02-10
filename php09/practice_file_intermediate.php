<!-- 日本郵便のページにある郵便番号のデータを利用し、「郵便番号」「住所」のデータを表示してください。 -->

<?php
$filename = './zip_data_split_1.csv';
$array = [];

if (is_readable($filename) === true) {

    if (($fp = fopen($filename, 'r')) !== false) {
        while (($tmp = fgets($fp)) !== false) {
            //文字列内のダブルクォーテーションを取り除き変数へ
            $trim = str_replace('"', "", $tmp);
            //カンマ区切りで文字列を配列へ入れていく
            $array[] = explode(',', $trim);
        }
        fclose($fp);
    }
} else {
    $data[] = 'ファイルがありません';
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>郵便番号と住所</title>
    <style type="text/css">
        table,
        td,
        th {
            border: solid black 1px;
            ;
            border-collapse: collapse;
        }

        th {
            border-bottom: solid black 2px;
        }

        /* 学校で課題やる際に奇数のレコードは下線が太くなっていた為 */
        tr:nth-child(2n-1) {
            border-bottom: solid black 3px;
        }

        table {
            width: 800px;

        }
    </style>
</head>

<body>

    <p>以下にファイルから読み込んだ住所データを表示</p>

    <p>住所データ</p>
    <table>
        <tr>
            <th>郵便番号</th>
            <th>都道府県</th>
            <th>市区町村</th>
            <th>町域</th>
        </tr>

        <?php
        foreach ($array as $value) { ?>
            <tr>
                <td><?php print $value[0]; ?></td>
                <td><?php print $value[4]; ?></td>
                <td><?php print $value[5]; ?></td>
                <td><?php print $value[6]; ?></td>
            </tr>
        <?php }; ?>
    </table>


</body>

</html>