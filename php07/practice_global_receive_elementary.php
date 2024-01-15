<?php
// 受信ファイル 
// テキストボックスに名前を入力してPOSTで送信するページを作成し、
// 入力されている場合は「ようこそ◯◯さん」
// 入力されていない場合は「名前を入力してください」
// と表示するプログラムを作成してください。

// if (isset($_GET['my_name']) === TRUE && $_GET['my_name']  !== '') {
//     print 'ここに入力した名前を表示:  ' . htmlspecialchars($_GET['my_name'], ENT_QUOTES, 'UTF-8');
// } else {
//     print '名前が未入力です';
//}

if (isset($_POST['my_name']) === TRUE && $_POST['my_name'] !== '') {
    print 'ようこそ' . htmlspecialchars($_POST['my_name'], ENT_QUOTES, 'UTF-8') .'さん';
} else {
    print '名前を入力してください';
} 
