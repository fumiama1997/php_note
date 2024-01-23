<pre>
    <?php  
    $host = 'localhost'; //データベースのホスト名又はIPアドレス
    $username = 'root';  //MySQLのユーザ名
    $passwd = 'narait';  //MySQLのパスワード
    $dbname = 'user';  //データベース名
    $link = mysqli_connect($host,$username,$passwd,$dbname);
    //接続成功した場合
    if($link){
        //文字化け防止
        mysqli_set_charset($link,'utf8');
        $query = 'SELECT goods_id , goods_name, price FROM goods_table';
        //クエリを実行します
        $result = mysqli_query($link,$query);
        //一行ずつ結果を配列で取得します
        while($row = mysqli_fetch_array($result)){
            print $row['goods_id'];
            print $row['goods_name'];
            print $row['price'];
            print "\n";
        }
        //結果セットを開放します
        mysqli_free_result($result);
        //接続を閉じます
        mysqli_close($link);
        //接続に失敗した場合
    } else {
        print 'DB接続失敗';
    }
    ?>
</pre>