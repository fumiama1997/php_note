<?php
$goods_data = [];
$order = 'ASC';
if (isset($_GET['order']) === TRUE) {
    $order = $_GET['order'];
}
$host = 'localhost'; // データベースのホスト名又はIPアドレス
$username = 'root';  // MySQLのユーザ名
$passwd   = 'narait';    // MySQLのパスワード
$dbname   = 'user';    // データベース名
$link = mysqli_connect($host, $username, $passwd, $dbname);
// 接続成功した場合
if ($link) {
   // 文字化け防止
   mysqli_set_charset($link, 'utf8');
   $query = 'SELECT goods_name, price FROM goods_table ORDER BY price ' . $order;
  
   $result = mysqli_query($link, $query);
   // 1行ずつ結果を配列で取得します
   while ($row = mysqli_fetch_array($result)) {
       $goods_data[] = $row;
   }
   // 結果セットを開放します
   mysqli_free_result($result);
   // 接続を閉じます
   mysqli_close($link);
// 接続失敗した場合
} else {
   print 'DB接続失敗';
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
   <meta charset="UTF-8">
   <title>サンプル</title>
   <style type="text/css">
       table, td, th {
           border: solid black 1px;
       }
       table {
           width: 200px;
       }
   </style>
</head>
<body>
   <h1>商品一覧</h1>
   <form>
       <input type="radio" name="order" value="ASC" <?php if ($order === 'ASC') {print 'checked';} ?>>昇順
       <input type="radio" name="order" value="DESC" <?php if ($order === 'DESC') {print 'checked';} ?>>降順
       <input type="submit" value="表示">
   </form>
   <table>
       <tr>
           <th>商品名</th>
           <th>値段</th>
       </tr>
<?php
foreach ($goods_data as $value) {
?>
       <tr>
           <td><?php print htmlspecialchars($value['goods_name'], ENT_QUOTES, 'UTF-8'); ?></td>
           <td><?php print htmlspecialchars($value['price'], ENT_QUOTES, 'UTF-8'); ?></td>
       </tr>
<?php
}
?>
   </table>
</body>
</html>







