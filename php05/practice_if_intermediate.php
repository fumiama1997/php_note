<?php

// ページにアクセスした時間の秒が

// 00だった場合は「ジャストタイム!!」
// 00を除くゾロ目だった場合は「ゾロ目!」
// 上記以外の場合は「外れ」
// という文字と秒を表示するプログラムを作成してください。

$time = time();

$date = date('H:i:s', $time);

$seconds = date('s', $time);

print 'アクセスした瞬間の秒は' . $seconds . '秒でした。<br>';

if ($seconds === '00') {
    print "ジャストタイム！";
} else if ($seconds === '11' || $seconds === '22' || $seconds === '33' || $seconds === '44' || $seconds === '55' || $seconds === '66' || $seconds === '77' || $seconds === '88' || $seconds === '99') {
    print "ゾロ目！";
} else {
    print "外れ";
}
