<!-- 日本郵便のページにある郵便番号のデータを利用し、「郵便番号」「住所」のデータを表示してください。 -->

<?php
<<<<<<< HEAD
// <?php
// // ファイルから読み込んだ文字列をカンマで分割して順番に出力する方法

// // 1. ファイルを読み込む（例: sample.txt）
// $filename = 'sample.txt';
// $file_contents = file_get_contents($filename);

// // 2. カンマで分割して配列に格納
// $split_array = preg_split('/,/', $file_contents);

// // 3. 配列の要素を順番に出力
// foreach ($split_array as $element) {
//     echo $element . "\n";
// }




$filename = './zip_data_split_1.csv';

=======
$filename = './zip_data_split_1.csv';
>>>>>>> ea3dd7606ffdfd56827dbc10c49696ea1b26f54e

if (is_readable($filename) == TRUE) {

    if (($fp = fopen($filename, 'r')) == TRUE) {
        while (($tmp = fgets($fp)) == TRUE) {
<<<<<<< HEAD
            $data = htmlspecialchars($tmp, ENT_QUOTES, 'UTF-8');
            $split = preg_split("/,/", $data);
            // var_dump($split);
=======
            //文字列内のダブルクォーテーションを取り除き変数へ
            $trim = str_replace('"', "", $tmp);
            //カンマ区切りで文字列を配列へ入れていく
            $array[] = explode(',', $trim);
>>>>>>> ea3dd7606ffdfd56827dbc10c49696ea1b26f54e
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

        tr:nth-child(2n-1) {
            border-bottom: solid black 2px;
        }

        table {
<<<<<<< HEAD
            width: 300px;
=======
            width: 800px;
>>>>>>> ea3dd7606ffdfd56827dbc10c49696ea1b26f54e
        }
    </style>
</head>

<body>
<<<<<<< HEAD
    <p>以下にファイルから読み込んだ情報を表示</p>
    <p>住所データ</p>
    <table>
        <tr>
            <th>郵便番号</td>
            <th>都道府県</td>
            <th>市区町村</td>
            <th>町域</td>
        </tr>

        <?php
      foreach($split as $a){
        print $a[1];
      }
=======

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







>>>>>>> ea3dd7606ffdfd56827dbc10c49696ea1b26f54e

        ?>

    </table>


</body>

</html>