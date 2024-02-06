<!-- 1から100までの整数に対し、

3で割り切れる場合は「Fizz」
5で割り切れる場合は「Buzz」
3でも5でも割り切れる場合は「FizzBuzz」
上記以外は数値そのまま
を表示してください。

なお、どの繰り返し処理を利用するかは自由です。 -->

<?php
$data = '1';
?>
<!DOCTYPE html>
<html lang="ja">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>課題4</title>
</head>

<body>
   <?php
   for ($i = 1; $i <= 100; $i = $i + 1) {
      if ($i % 3 === 0 && $i % 5 === 0) {
         print 'FizzBuzz' . '<br>';
      } else if ($i % 3 === 0) {
         print 'Fizz' . '<br>';
      } elseif ($i % 5 === 0) {
         print 'Buzz' . '<br>';
      } else {
         print $i . '<br>';
      }
   }
   ?>





</body>

</html>