<!-- 以下の要件を満たすように作成してください。

利用者が名前とコメントを入力し、発言できる。
利用者の過去の発言内容をテキストファイルで管理する。
全ての利用者の過去の発言内容を一覧で表示する。一覧には「名前」「コメント」「発言日時」の3つを1行ずつ表示する。
利用者の名前は最大20文字以内まで発言できる。もし20文字より多くの文字を入力して発言した場合はエラーメッセージを表示し、発言できないようにする。
利用者のコメントは最大100文字以内まで発言できる。もし100文字より多くの文字を入力して発言した場合はエラーメッセージを表示し、発言できないようにする。
利用者の名前とコメントは必ず文字が入力される。もし名前あるいはコメントが未入力で発言した場合はエラーメッセージを表示し、発言できないようにする。
（ソースコード）比較演算子は、「===」や「!==」を利用すること -->

<?php
$filename = './challenge_log.txt';
$comment = '';
$name = '';
$nameLimit = '20';
$commentLimit = '100';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (('' == ($_POST['name'])) === FALSE) {
        if (('' == ($_POST['comment'])) === FALSE) {

            $name = $_POST['name'];
            $comment = $_POST['comment'];
            $date = date('Y-m-d H:i');
        } else {
            print "名前あるいはコメントが未入力です。";
        }
    }

    if (($fp = fopen($filename, 'a')) !== FALSE) {
        $nameLength = strlen($name);
        $commentLength = strlen($comment);

        if ($nameLimit <= $nameLength || $commentLimit <= $commentLength) {
            print "名前は20文字以内、コメントは100字以内で入力ください。";
        } else {
            fwrite($fp, $name . ":" . $comment . "  " . $date . "\n");
        }
        fclose($fp);
    }
}

$data = [];

if (is_readable($filename) === TRUE) {
    if (($fp = fopen($filename, 'r')) !== FALSE) {
        while (($tmp = fgets($fp)) !== FALSE) {
            $data[] =  htmlspecialchars($tmp, ENT_QUOTES, 'UTF-8');
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
    <title> ひとこと掲示板</title>
</head>

<body>
    <h1>ひとこと掲示板</h1>
    <form method="POST">
        <p>名前: <input type="text" name="name"> ひとこと: <input type="text" name="comment"> <input type="submit" value="送信"></p>
    </form>
    <?php foreach ($data as $value) {
        print $value . '<br>';
    } ?>










</body>

</html>