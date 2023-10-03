<!--掲示板に「編集機能」を追加しよう。指定された番号の投稿を編集できるように-->
<?php
        # テキストファイルの特定の1行を削除する。
        #→削除すべき1行以外を書き写す処理をする。（モードwで上書き保存すればできそう）
        /*
        済１．新規投稿フォームにパスワードの入力欄を追加
        ２．新規投稿時にパスワードを保存するように改修する
        ３．削除フォームと編集フォームにもパスワード入力欄を作る。（できればパスワード入力欄は独立させるというか、全体一括で行いたいけど）
            保存フォーマットの最後にパスワードを保存する。
        ４．パスワードが一致したときのみ削除機能と編集機能が動作するように改修する。
            
        ５．
        */

        $e_name = "";
        $e_comment = "";
        $form = "";
        #if文の外で定義できる
        $file_name = "mission_5-1.txt";
        
        #データベース接続
        #データベース名
        $dsn = 'mysql:dbname=データベース名;host=localhost';

        #ユーザー名
        $user = 'ユーザー名';

        #パスワード
        $password = 'パスワード';

        #エラーメッセージ返してね的な意味らしい
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        
        #同じ名前のTBを作らないように、DBを作成する。
        $sql = "CREATE TABLE IF NOT EXISTS tbmission5"
        ." ("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"
        . "name char(32),"
        . "comment TEXT,"
        . "time DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,"
        . "pass INT"
        . ");";
        $stmt = $pdo->query($sql);
        

        if(isset($_POST["namae"]) && isset($_POST["comment"])){
            
            #【POST送信の各値の変数と「投稿番号」「投稿日時」を扱う変数を用意。更にこれらを結合して1行にするための変数も用意し、ここに結合結果を入れる】
            $name = $_POST["namae"];
            $comment = $_POST["comment"];
            
            #時間を取得
            $time = date("Y/n/j G:i:s");
                
            $postnum = 0;
    

            #編集モードの時はここで保存内容を差し替える
            if(!empty($_POST["edit_num"])){
                
                $edit_num = $_POST["edit_num"];
                
                $id = $edit_num; //変更する投稿番号
                $comment = $comment. "(編集済)";
                
                $sql = 'UPDATE tbmission5 SET name=:name,comment=:comment WHERE id=:id';
                
                $stmt = $pdo->prepare($sql);
                
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                
                $stmt->execute();
                
            }else{
                
                #パスワード格納
                $pass = (int)$_POST["pass"];
                
                $sql = "INSERT INTO tbmission5 (name, comment, pass) VALUES (:name, :comment, :pass)";
                $stmt = $pdo->prepare($sql);
                
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                $stmt->bindParam(':pass', $pass, PDO::PARAM_INT);
                
                $stmt->execute();
                
            }
            
        #削除用条件分岐
        }elseif(isset($_POST["d_num"]) && isset($_POST["d_pass"])){


            #受け取った値を格納
            $d_num = $_POST["d_num"];
            $d_pass = (int)$_POST["d_pass"];

            
           
                
                #削除番号をプライマリキーとして持つレコードのパスワードを抽出する
                $id = $d_num;
                
                $sql = 'SELECT pass FROM tbmission5 WHERE id=:id ';
                $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
                $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
                $stmt->execute();                             // ←SQLを実行する。

                $resluts = $stmt -> fetchAll();
                foreach($resluts as $row){
                    $echo_pass = $row['pass'];
                }
                
                #パスワードが一致した場合、削除番号をプライマリキーとして持つレコードを消去する。（DELETE文）
                if($echo_pass == $d_pass){
                    
                    $id = $d_num;
                    
                    $sql = 'DELETE FROM tbmission5 WHERE id=:id';
                    $stmt = $pdo->prepare($sql);
                    
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    
                    $stmt->execute();
                    
                }
            
            
            

        #編集用条件分岐
        }elseif(isset($_POST["edit"]) && isset($_POST["e_pass"])){
            $edit = $_POST["edit"];
            $e_pass = (int)$_POST["e_pass"];


            #編集番号をプライマリキー賭して持つレコードの「名前」と「コメント」と「パスワード」を取得
            $id = $edit;
                
            $sql = 'SELECT name, comment, pass FROM tbmission5 WHERE id=:id ';
            
            $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
            $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
            $stmt->execute();                             // ←SQLを実行する。
            
            $resluts = $stmt -> fetchAll();
            
            foreach($resluts as $row){
                
                $echo_pass = $row['pass'];
                $e_name = $row['name'];
                $e_comment = $row['comment'];
                
            }
            
            if($e_pass != $echo_pass){
                
                $e_name = "";
                $e_comment = "";
            }else{
                 #編集モードの判別用変数に、編集したい投稿番号を保存
                $edit_num = $edit;
            
                $form = '編集する投稿番号：<input type="number" name="edit_num" value="'. $edit_num. '" readonly> <br>';  
            }

            
        }
        
        $sql = 'SELECT * FROM tbmission5';
        
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        
        foreach ($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            echo "投稿番号：". $row['id']. '<br>';
            echo "名　前　：". $row['name']. '<br>';
            echo "コメント：". $row['comment']. '<br>';
            echo "投稿時間：". $row['time']. '<br>';
            echo "<br><hr><br>";
        }
            
?>


<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>mission_5-1</title>
    </head>

    <body>
        投稿フォーム<br>
        <form action="" method="post">
            
            <?php 
                echo $form;
            ?>
            名前：<input type="text" name="namae" value="<?php /*フォームに取得済みの「名前」と「コメント」を既に入っている状態で表示(初期値は空欄)*/echo $e_name; ?>">
            コメント：<input type="text" name="comment" value="<?php echo $e_comment; ?>">
            <?php
                if(isset($edit_num)){
                    echo "";
                }else{
                    echo '<br>パスワード：<input type="password" name="pass">';
                }
            ?>

            <input type="submit" name="submit">
            <br>
        </form>
        <br>
        削除フォーム<br>
        <form action="" method="post">
            投稿番号：<input type="number" name="d_num">
            パスワード：<input type="password" name="d_pass">

            <input type="submit" name="d_submit">
            <br>
        </form>
        編集番号指定フォーム<br>
        <form action="" method="post">
            投稿番号：<input type="number" name="edit">
            パスワード：<input type="password" name="e_pass">

            <input type="submit" name="e-submit">
        </form>
        <br>
        
        
    </body>
</html>