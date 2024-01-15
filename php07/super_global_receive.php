<?php 
    $my_name = '';
     if(isset($_POST['my_name'])=== TRUE && $_POST['my_name'] !==''){
        print 'お名前:'  . htmlspecialchars($_POST
        ['my_name'],ENT_QUOTES, 'UTF-8') ."\n"; 
    } else {
        print '名前が未入力です';
    }

    $gender = '';
    if(isset($_POST['gender'])=== TRUE){
      $gender = htmlspecialchars($_POST['gender'], ENT_QUOTES,'UTF-8');
      print '性別:     '  . htmlspecialchars($_POST
      ['gender'],ENT_QUOTES, 'UTF-8') ."\n";
    } else {
        print '性別が選択されていません';
    }

    $mail = '';
    if(isset($_POST['mail'])=== TRUE){
        $mail = htmlspecialchars($_POST['mail'], ENT_QUOTES,'UTF-8');
        print 'お知らせメールを送ります' ;
      } else {
        print 'お知らせメールを送りません';
      }
?>