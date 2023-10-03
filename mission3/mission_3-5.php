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
        $edit_num = "";
        $form = "";
        #if文の外で定義できる
        $file_name = "mission_3-5.txt";

        if(isset($_POST["namae"]) && isset($_POST["comment"])){
            
            #【POST送信の各値の変数と「投稿番号」「投稿日時」を扱う変数を用意。更にこれらを結合して1行にするための変数も用意し、ここに結合結果を入れる】
            $namae = $_POST["namae"];
            $comment = $_POST["comment"];
            
            #時間を取得
            $time = date("Y/n/j G:i:s");
                
            $postnum = 0;
            #ファイルの中身があるなら行番号を返す
            if(file_exists($file_name)){
                #テキストファイルのデータを配列に格納 (ある前提で進めてるからおかしくなる、この時点でifに入れていいかも)
                $file_lines = file($file_name, FILE_IGNORE_NEW_LINES);

                #最後の行だけ指定できる関数があるともっと楽にできそう
                foreach($file_lines as $file_line){
                    $datas = explode("<>", $file_line);

                    $postnum = (int)$datas[0];
                }
                $postnum++;
                
                #変更前の内容を保存する
                $file_lines = file($file_name, FILE_IGNORE_NEW_LINES);
            }

            #編集モードの時はここで保存内容を差し替える
            if(!empty($_POST["edit_num"])){
                $edit_num = $_POST["edit_num"];
                #テキストファイル消去
                $fp = fopen($file_name, "w");

                foreach($file_lines as $file_line){
                    $str = $file_line;
                    $datas = explode("<>", $file_line);

                    if($edit_num == (int)$datas[0]){
                        $datas[1] = $namae;
                        $datas[2] = $comment;
                        $datas[3] = $time;

                        $str = "$datas[0] <> $datas[1] <> $datas[2] <> $datas[3] <> $datas[4](編集済)";
                    }
                    fwrite($fp, $str. PHP_EOL);
                }
                fclose($fp);
            }else{
                #パスワード格納
                $pass = (int)$_POST["pass"];

                #保存フォーマット：「（投稿番号）<>（名前）<>（コメント）<>（投稿日時）」
                $str = "$postnum <> $namae <> $comment <> $time <> $pass";
                
                #ファイル操作
                $fp = fopen($file_name, "a");
                fwrite($fp, $str. PHP_EOL);
                fclose($fp);
            }

            #変更した内容を格納
            $file_lines = file($file_name, FILE_IGNORE_NEW_LINES);
                
            #ファイルを読み込んだ配列を、配列の数（＝行数）だけループさせる
            foreach($file_lines as $file_line){
                    
                #区切り文字で分割して、それぞれの値を取得（explode関数：文字列を、指定した文字列で分割する関数）
                $datas = explode("<>", $file_line);
                    
                #出力
                echo "投稿番号：$datas[0] <br>";
                echo "名前：$datas[1]<br>";
                echo "コメント：$datas[2]<br>";
                echo "投稿日時：$datas[3]<br> <br>";
                    

            }
        #削除用条件分岐
        }elseif(isset($_POST["d_num"]) && isset($_POST["d_pass"])){


            #受け取った値を格納
            $d_num = $_POST["d_num"];
            $d_pass = (int)$_POST["d_pass"];

            #ファイルの内容を、配列に格納
            $file_lines = file($file_name, FILE_IGNORE_NEW_LINES);
            $fp = fopen($file_name, "w");
            fclose($fp);

            $fp = fopen($file_name, "a");
            
            foreach($file_lines as $file_line){
                $file_datas = explode("<>", $file_line);

                #パスワードが一致し、かつ投稿番号と削除対象番号を比較し、等しくない場合はファイルに追加で書き込みを行う
                if((int)$file_datas[0] == (int)$d_num && (int)$file_datas[4] == $d_pass){
                        #何もしない
                }else{
                    $str = "$file_datas[0] <> $file_datas[1] <> $file_datas[2] <> $file_datas[3] <> $file_datas[4]";
                    fwrite($fp, $str.PHP_EOL);
                            
                    #出力
                    echo "投稿番号：$file_datas[0] <br>";
                    echo "名前：$file_datas[1]<br>";
                    echo "コメント：$file_datas[2]<br>";
                    echo "投稿日時：$file_datas[3]<br> <br>";
                }
            
            }
            fclose($fp);

        #編集用条件分岐
        }elseif(isset($_POST["edit"]) && isset($_POST["e_pass"])){
            $edit = $_POST["edit"];
            $e_pass = (int)$_POST["e_pass"];

            #file関数で配列を格納
            $file_lines = file($file_name, FILE_IGNORE_NEW_LINES);

            #配列の数だけループ
            foreach($file_lines as $file_line){

                #explode関数で分割（投稿番号の取得）
                $file_datas = explode("<>", $file_line);

                if((int)$file_datas[0] == $edit && (int)$file_datas[4] == $e_pass){

                    #投稿番号と編集対象番号を比較して、一致する場合はその投稿の「名前」と「投稿内容」を取得
                    $e_name = $file_datas[1];
                    $e_comment = $file_datas[2];

                    #編集モードの判別用変数に、編集したい投稿番号を保存?
                    $edit_num = $edit;
                    
                    $form = '編集する投稿番号：<input type="number" name="edit_num" value="'. $edit_num. '" readonly> <br>';
                }

                #既存の投稿フォームの中に、新規登録モードと編集モードの区別をする情報の追加を行う
            }
        }
            
?>


<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>mission_3-5</title>
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
                if(isset($_POST["edit"]) && isset($_POST["e_pass"])){
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