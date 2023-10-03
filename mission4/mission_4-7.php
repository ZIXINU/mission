

<?php
    #データベース名
    $dsn = 'mysql:dbname=データベース名;host=localhost';

    #ユーザー名
    $user = 'ユーザー名';

    #パスワード
    $password = 'パスワード';

    #MySQLのデータベースに接続する。（array以降はエラーメッセージを返す用）
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    #同じ名前のTBを作らないように、DBを作成する。
    $sql = "CREATE TABLE IF NOT EXISTS tbtest"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT"
    .");";

    #DBに要求をするときは、クエリィを投げるっていうらしい。今回は$sqlっていうクエリィ（命令）を投げてる
    $stmt = $pdo -> query($sql);

    $sql = 'SHOW TABLES';
    $result = $pdo -> query($sql);

    foreach($result as $row){
        echo $row[0];
        echo '<br>';
    }

    #水平な直線を出力する
    echo "<hr>";

    #TBのCREATE文を呼び出す（SHOW CREATE TABLE）
    $sql = 'SHOW CREATE TABLE tbtest';
    $result = $pdo -> query($sql);
    
    foreach($result as $row){
        echo $row[1];
    }
    echo "<hr>";

    #TBにデータを入力する（INSERT）     
    #入れる内容（ゆくゆくは名前とかを変数にしていく感じなんかな）
    $name = 'ひなた';
    $comment = 'とてもお腹が減った';

    #INSERT文(:name, :commentは、プレースホルダーと呼ばれる値を入れるための空箱)
    $sql = "INSERT INTO tbtest (name, comment) VALUES (:name, :comment)";

    #値が空のままのSQL文をprepare()にセットして、SQLの実行の準備を行う
    $stmt = $pdo -> prepare($sql);

    #
    $stmt -> bindParam(':name', $name, PDO::PARAM_STR);

    $stmt -> bindParam(':comment', $comment, PDO::PARAM_STR);

    $stmt -> execute();


    #入力されているデータレコードの内容の編集(UPDATE)
    
    #変更する投稿番号
    $id = 1;
    $name = "Hinata";
    $comment = "とても眠い";

    #WHEREはこの場合省略不可
    $sql = 'UPDATE tbtest SET name=:name, comment=:comment WHERE id=:id';

    $stmt = $pdo -> prepare($sql);

    $stmt -> bindParam(':name', $name, PDO::PARAM_STR);
    $stmt -> bindParam(':comment', $comment, PDO::PARAM_STR);
    $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
    $stmt -> execute();

    #入力したデータを表示する（SELECT文）
    $sql = "SELECT * FROM tbtest";

    $stmt = $pdo -> query($sql);

    $results = $stmt -> fetchAll();

    foreach($results as $row){
        echo $row['id'];
        echo $row['name'];
        echo $row['comment'].'<br>';

        echo "<hr>"; 
    }
?>