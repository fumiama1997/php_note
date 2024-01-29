<pre>
    <?php
    $host = 'localhost';
    $username = 'root';
    $passwd = 'narait';
    $dbname = 'user';
    $link = mysqli_connect($host, $username, $passwd, $dbname);

    if ($link) {
        mysqli_set_charset($link, 'utf8');
        $query = 'UPDATE goods_table SET price = 60 WHERE goods_id = 5';
        // クエリを実行します↓
        if (mysqli_query($link, $query) === TRUE) {
            print '成功';
        } else {
            print '失敗';
        }

        // 接続を閉じます
        mysqli_close($link);
        // 接続失敗した場合
    } else {
        print 'DB接続失敗';
    }
    ?>
</pre>