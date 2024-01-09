<pre>
<?php
 
$score = mt_rand(0, 100); //0以上100以下の数値をランダムで返す
 print $score . "\n";
 var_dump($score >= 80);
 var_dump($score <= 30);
if ($score >= 80) {
    print 'ヽ( ﾟ∀ﾟ)ノｷﾀ━!!';
} else if ($score <= 30) {
    print '(´･ω･`)ｼｮﾎﾞｰﾝ';
} else {
    print 'ヽ(´ー｀)ノﾏﾀｰﾘ';
}
 
?>
</pre>