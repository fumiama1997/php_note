<?php
// 西暦
//先頭の数字が０以外の数字
$regexp_year   = '/^[1-9][0-9]{3}$/'; // 西暦の正規表現を入力
$check_year[0] = '1953';
$check_year[1] = '2013';
// 電話番号
// 先頭の数字が０で固定
$regexp_phone_number   = '/^0[0-9]{1,3}-[0-9]{3,4}-[0-9]{3,4}$/'; // 電話番号の正規表現を入力
$check_phone_number[0] = '03-1111-1111';
$check_phone_number[1] = '040-222-2222';
$check_phone_number[2] = '0120-000-000';
// formタグ
//<form>の文字列は必須条件、a-z-="_ は0回以上の繰り返し(form中身で使用するものを調べた)
$regexp_form   = '/^<form[a-z-="_ ]*>$/'; // formの正規表現を入力
$check_form[0] = '<form>';
$check_form[1] = '<form method="post">';
// メールアドレス
// ドメインには半角英数字と-(半角ハイフン)が使える
// メールアドレスにはa-z0～9半角 .（ピリオド）-（ハイフン）_（アンダーバー）の3種類が使える。
$regexp_mail   = '/^[a-z0-9.-_]+@[a-z0-9-]+.[a-z0-9.-]+$/'; // メールアドレスの正規表現を入力
$check_mail[0] = 'test@test.com';
$check_mail[1] = 'test_2@test.co.jp';
$check_mail[2] = 'test.3@example.ne.jp';
// URL
// URLで使用可の文字を半角英数字と「_/:%#$&?()~.=+-」としている。
$regexp_url   = '/^http[s]?:\/\/([a-z0-9_\/:%#$&?()~=+-])+.[a-z0-9_\/:%#$&?()~.=+-]+$/'; // URLの正規表現を入力
$check_url[0] = 'http://codecamp.jp';
$check_url[1] = 'https://test.com';
$check_url[2] = 'http://codecamp.jp/index.html?q=test';
////////////////////////////////////////////////////////
// これ以降の処理はソースコード変更を行わないようにしてください。
////////////////////////////////////////////////////////
$reslut_check_phone_number = check_regexp($regexp_phone_number, $check_phone_number);
$reslut_check_form      = check_regexp($regexp_form, $check_form);
$reslut_check_mail      = check_regexp($regexp_mail, $check_mail);
$reslut_check_year      = check_regexp($regexp_year, $check_year);
$reslut_check_url       = check_regexp($regexp_url, $check_url);
function check_regexp($regexp, $str_data)
{
    $msg = [];
    foreach ($str_data as $value) {
        if (preg_match($regexp, $value, $macthes) === 1) {
            if ($value === $macthes[0]) {
                $msg[] =  '完全一致: ' . $value;
            } else {
                $msg[] =  '部分一致: ' . $value . ' 【一致した文字列: ' . $macthes[0] . '】';
            }
        } else {
            $msg[] = '不一致: ' . $value;
        }
    }
    return $msg;
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>正規表現課題</title>
    <style type="text/css">
        h1 {
            font-size: 16px;
        }

        h1,
        p {
            margin: 0px;
        }
    </style>
</head>

<body>
    <section>
        <h1>西暦の正規表現チェック: <?php print htmlspecialchars($regexp_year, ENT_QUOTES, 'UTF-8'); ?></h1>
        <?php foreach ($reslut_check_year as $value) { ?>
            <p><?php print htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php } ?>
    </section>
    <section>
        <h1>電話番号の正規表現チェック: <?php print htmlspecialchars($regexp_phone_number, ENT_QUOTES, 'UTF-8'); ?></h1>
        <?php foreach ($reslut_check_phone_number as $value) { ?>
            <p><?php print htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php } ?>
    </section>
    <section>
        <h1>fromタグの正規表現チェック: <?php print htmlspecialchars($regexp_form, ENT_QUOTES, 'UTF-8'); ?></h1>
        <?php foreach ($reslut_check_form as $value) { ?>
            <p><?php print htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php } ?>
    </section>
    <section>
        <h1>メールアドレスの正規表現チェック: <?php print htmlspecialchars($regexp_mail, ENT_QUOTES, 'UTF-8'); ?></h1>
        <?php foreach ($reslut_check_mail as $value) { ?>
            <p><?php print htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php } ?>
    </section>
    <section>
        <h1>URLの正規表現チェック: <?php print htmlspecialchars($regexp_url, ENT_QUOTES, 'UTF-8'); ?></h1>
        <?php foreach ($reslut_check_url as $value) { ?>
            <p><?php print htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php } ?>
    </section>
</body>

</html>