<pre>
    <?php
    $score1 = mt_rand(0, 100);
    $score2 = mt_rand(0, 100);
    $sum = $score1 + $score2;

    print 'score1:' . $score1 . "\n";
    print 'score2:' . $score2 . "\n";
    print 'sum:' . $sum . "\n";

    if ($sum >= 160 || $score1 === 100 || $score2 === 100) {
        print '特待生' .  "\n";
    } else if ($sum >= 120 && $score1 >= 40 && $score2 >= 40) {
        print '合格' . "\n";
    } else if ($sum >= 110) {
        print '補欠合格' . "\n";
    } else {
        print '不合格' . "\n";
    }
    ?>
</pre>