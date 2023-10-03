

<?php
    #データベース名
    $dsn = 'mysql:dbname=データベース名;host=localhost';

    #ユーザー名
    $user = 'ユーザー名';

    #パスワード
    $password = 'パスワード';

    #エラーメッセージ返してね的な意味らしい
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    #同じ名前のTBを作らないようにする
    $sql = "CREATE TABLE IF NOT EXISTS tbtest"
    ."("
    ."id INT AUTO_INCREMENT RRIMARY KEY,"
    ."name char(32)"
    ."comment TEXT"
    .");";

    $stmt = $pdo->query($sql);

    $sql = "SHOW TABLES";
    $result = $pdo -> query(sql);

    foreach($result as $row){
        echo $row[0];
        echo "<br>";
    }

    echo "<hr>";
?>