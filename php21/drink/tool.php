<!-- 販売するドリンクの追加・変更を行う「管理ページ」
以下の要件を満たすように作成してください。
[管理ページ]（ファイルパス: htdocs/drink/tool.php）

1．「ドリンク名」「値段」「在庫数」「公開ステータス」を入力し、商品を追加できる。

2．商品を追加する場合、「商品画像」を指定してアップロードできる。

3．追加した商品の一覧情報として、「商品画像」、「商品名」、「値段」、「在庫数」、「公開ステータス」のデータを一覧で表示する。

4．商品一覧から指定ドリンクの在庫数を入力し、在庫数の変更ができる。

5．商品一覧から指定ドリンクの公開ステータス「公開」あるいは「非公開」の変更ができる。

6．商品の追加あるいは指定ドリンク情報（「在庫数」、「公開ステータス」）の変更が正常に完了した場合、完了のメッセージを表示する。

7．商品を追加する場合、「商品名」「値段」、「在庫数」、「公開ステータス」「商品画像」のいずれかを指定していない場合、エラーメッセージを表示して、商品を追加できない。

8．商品を追加する場合、「値段」、「在庫数」は、0以上の整数のみ可能とする。0以上の整数以外はエラーメッセージを表示して、商品を追加できない。

9．商品を追加する場合、公開ステータスは「公開」あるいは「非公開」のみ可能とする。「公開」あるいは「非公開」以外はエラーメッセージを表示して、商品を追加できない。

10．アップロードできる「商品画像」のファイル形式は「JPEG」、「PNG」のみ可能とする。「JPEG」、「PNG」以外はエラーメッセージを表示して、商品を追加できない。

11．商品一覧から指定ドリンクの在庫数を変更する場合、0以上の整数のみ可能とする。0以上の整数以外はエラーメッセージを表示して、変更できない。

補足 管理ページ要件9 入力チェックの確認方法

要件9のプルダウンの入力チェックは一見不要そうに見えますが、フォームデータは容易に改竄できてしまうため、しっかりとチェックするようにしましょう。

フォームデータの改竄は教科書の「Chrome便利機能」の「1-2 一時的な修正」で紹介しています。

なお、フォームデータを改竄して確認する方法がわからない方は、下記のように公開・非公開以外の選択項目を用意して入力チェックを確認するようにしましょう。
ユーザがドリンクを購入する「購入ページ」 -->
<?php
$change = '';
var_dump($_POST);
$goods_data = [];
// MySQL接続情報
$host   = 'localhost'; // データベースのホスト名又はIPアドレス
$user   = 'root';  // MySQLのユーザ名
$passwd = 'narait';    // MySQLのパスワード
$dbname = 'drink';    // データベース名

// コネクション取得
if ($link = mysqli_connect($host, $user, $passwd, $dbname)) {
    // 文字コードセット
    mysqli_set_charset($link, 'UTF8');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $drink_id = $_POST['drink_id'];
        if (isset($_POST['status']) && (isset($_POST['drink_id']))) {
            $status = $_POST['status'];
            if ($status === '1') {
                $status = '0';
            } else {
                $status = '1';
            }
            $query = 'UPDATE information_table set status = ' . $status . ' WHERE drink_id = ' . $drink_id . ' ';
            $result = mysqli_query($link, $query);
            if ($result === true) {
                $change = 'ステータス変更成功';
            }
        }

        if (isset($_POST['stock']) && isset($_POST['drink_id'])) {
            $stock = $_POST['stock'];
            $query = 'UPDATE stock_table set stock = ' . $stock . ' WHERE drink_id = ' . $drink_id . ' ';
            $result = mysqli_query($link, $query);
            if ($result === true) {
                $change = '在庫変更成功';
            }
        }
    }
    $query = 'SELECT information_table.drink_id,information_table.picture,information_table.name,information_table.price,stock_table.stock,information_table.status FROM information_table JOIN stock_table ON information_table.drink_id = stock_table.drink_id ';
    $result = mysqli_query($link, $query);
    // データを配列に入れる。
    while ($row = mysqli_fetch_array($result)) {
        $drink_data[] = $row;
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
    <title>自動販売機</title>
    <style type="text/css">
        table,
        td,
        th {
            border: solid black 1px;
            text-align: center
        }

        table {
            width: 600px;

        }

        img {
            width: 100px;
            height: 120px;
        }
    </style>
</head>

<body>
    <p><?php print $change; ?></p>
    <h1>新規商品追加</h1>
    <form method="post">
        <p>名前: <input type="text" name="name"></p>
        <p>値段: <input type="text" name="name"></p>
        <p>個数: <input type="text" name="name"></p>
        <input type="file" muitiple><br>
        <select name="public">
            <option value=0>非公開</option>
            <option value=1>公開</option>
        </select><br>
        <input type="submit" value="■□■□■商品追加■□■□■">
    </form>
    <hr>
    <h2>商品情報変更</h2>

    <p>商品一覧</p>

    <table>
        <tr>
            <th>商品画像</th>
            <th>商品名</th>
            <th>価格</th>
            <th>在庫数</th>
            <th>ステータス</th>
        </tr>

        <?php foreach ($drink_data as $value) { ?>

            <tr <?php if (($value['status']) === '0') {
                    print 'bgcolor=#cccccc';
                } ?>>
                <td><img src="picture\<?php print htmlspecialchars($value['picture'], ENT_QUOTES, 'UTF-8'); ?>"></td>
                <td><?php print htmlspecialchars($value['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php print htmlspecialchars($value['price'], ENT_QUOTES, 'UTF-8'); ?></td>
                <form method="post">
                    <td><input type="text" size="5" name="stock" value="<?php print $value['stock']; ?>"><br>個<br>
                        <input type="submit" value="変更">
                        <input type="hidden" name="drink_id" value="<?php print $value['drink_id']; ?>">
                    </td>
                </form>
                <form method="post">
                    <td><input type="submit" value="<?php
                                                    if (($value['status']) === '1') {
                                                        print '公開→非公開';
                                                    } else {
                                                        print '非公開→公開';
                                                    }; ?>">
                        <input type="hidden" name="status" value="<?php print $value['status']; ?>">
                        <input type="hidden" name="drink_id" value="<?php print $value['drink_id']; ?>">
                    </td>
                </form>
            </tr>

        <?php }; ?>

    </table>
</body>

</html>