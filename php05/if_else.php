<?php
$rand = mt_rand(1, 10);
if ($rand >= 6) {
    print '当たり';
} else {
    print '外れ';
}
