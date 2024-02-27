ユーザがドリンクを購入した結果情報を表示する「購入結果ページ」
以下の要件を満たすように作成してください。
[購入結果ページ]（ファイル名: htdocs/drink/result.php）​

ドリンクの購入が正常に完了した場合、指定ドリンクの「画像」「商品名」「お釣りの情報」を表示する。
ドリンクの購入が正常に完了した場合、指定ドリンクの在庫を減らす（管理ページの在庫数が減っていること）。
購入ページへ戻るリンクを作成する。
購入するドリンクを指定していない場合、エラーメッセージを表示して、ドリンクを購入することはできない。
投入金額と指定ドリンクを購入するときに必要な金額を比べ、もし投入金額が足りない場合はエラーメッセージを表示して、ドリンクを購入することはできない。
投入金額は0以上の整数のみ可能である。0以上の整数以外の場合は、エラーメッセージを表示して、ドリンクを購入することはできない。
指定ドリンクの在庫を確認し、もし在庫がなかった場合はエラーメッセージを表示して、ドリンクを購入することはできない（確認手順の例は以下になります）。
購入ページで、在庫がある商品を表示する。
もう１つのブラウザで管理ページを表示させて、購入予定の在庫がある商品の在庫数を0に変更する（在庫なしにする）。
別のブラウザで開いていた購入ページで、在庫数を0に変更した商品を選択して、購入するボタンを押した場合、エラーメッセージが表示されること
指定ドリンクのステータスを確認し、もしステータスが非公開の場合はエラーメッセージを表示して、ドリンクを購入することはできない（確認手順の例は以下になります）。
購入ページで、ステータスが公開の商品を表示する。
もう１つのブラウザで管理ページを表示させて、購入予定のステータスが公開である商品について、ステータスを非公開に変更する（ステータスを非公開にする）。
別のブラウザで開いていた購入ページで、ステータスを非公開に変更した商品を選択して、購入するボタンを押した場合、エラーメッセージが表示されること。
ドリンクの購入が正常に完了した場合、指定ドリンクと購入日時の情報をデータベースに保存する（※この要件は必須ではなく任意です）。

[その他]
（ソースコード）比較演算子は、「===」や「!==」を利用すること

画像アップロード方法は以下のPHPマニュアルを参照してください。
https://www.php.net/manual/ja/features.file-upload.post-method.php

もっと詳しいことが知りたい場合、「mime-type」、「multipart」をキーワードに検索してください。