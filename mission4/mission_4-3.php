

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

    echo "<hr>";
?>