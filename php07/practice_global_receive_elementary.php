<?php
// 入力されている場合は「ようこそ◯◯さん」
// 入力されていない場合は「名前を入力してください」
// と表示するプログラムを作成してください。


if ($_POST['my_name'] == '') {
    print '名前を入力してください';
} else {
    print 'ようこそ' . htmlspecialchars($_POST['my_name'], ENT_QUOTES, 'UTF-8') . 'さん';
}
