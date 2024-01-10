<?php
//0～2のランダムな数値を2つ取得し、「それぞれの数値」と「どちらの数値のほうが大きいか」の情報を表示してください。 ※2つの値が同じ場合は[同じ値]と表示



$rand1 = mt_rand(0,2);
$rand2 = mt_rand(0,2);

print 'rand1: ' . $rand1 . "\n";
print 'rand2: ' . $rand2 . "\n";
 

if ($rand1 == $rand2){
    print '一緒やん！';
} else if($rand1 > $rand2){
    print 'rand1の方が大きいね';
} else {
    print 'rand2の方が大きいね';
}


?>