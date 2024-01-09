<pre>
<?php
 
$score = mt_rand(0, 100); //0以上100以下の数値をランダムで返す
 
if ($score >= 80) {
    print 'ヽ( ﾟ∀ﾟ)ノｷﾀ━!!';
} else if ($score <= 30) {
    print '(´･ω･`)ｼｮﾎﾞｰﾝ';
} else {
    print 'ヽ(´ー｀)ノﾏﾀｰﾘ';
}
 
?>
</pre>