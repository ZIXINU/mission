<!--掲示板に「編集機能」を追加しよう。指定された番号の投稿を編集できるように-->
<?php
        # テキストファイルの特定の1行を削除する。
        #→削除すべき1行以外を書き写す処理をする。（モードwで上書き保存すればできそう）
        /*
        済１．入力フォームと並べて、削除番号指定用フォームを用意する（普通にフォーム1個書き足すだけ）
        ２．POST送信で「削除対象番号」を送信。（受信する際は、if文で「削除フォームは別に書く」）
        ３．ファイル読み込み関数で、ファイルの中身を配列に格納。
        ４．投稿番号取得（explode関数）
        ５．投稿番号と削除対象番号を比較。等しくない場合は、ファイルに追加書き込みを行う。
        */

        $e_name = "";
        $e_comment = "";
        $edit_num = "";
        $form = "";

        if(isset($_POST["namae"]) && isset($_POST["comment"])){
          
          #【POST送信の各値の変数と「投稿番号」「投稿日時」を扱う変数を用意。更にこれらを結合して1行にするための変数も用意し、ここに結合結果を入れる】
            $namae = $_POST["namae"];
            $comment = $_POST["comment"];
            
            #投稿番号初期値
            $count = 1;
            #時間を取得
            $time = date("Y/n/j G:i:s");
                
            $file_name = "mission_3-4.txt";
                
            #ファイルの中身があるなら行番号を返す
            if(file_exists($file_name)){
                #テキストファイルのデータを配列に格納 (ある前提で進めてるからおかしくなる、この時点でifに入れていいかも)
                $file_lines = file($file_name, FILE_IGNORE_NEW_LINES);

                #配列の個数を計算
                $count  = $count + count($file_lines);
                
                #変更前の内容を保存する
                $file_lines = file($file_name, FILE_IGNORE_NEW_LINES);
            }

            #編集モードの時はここで保存内容を差し替える
            if(!empty($_POST["edit_num"])){
                $edit_num = $_POST["edit_num"];
                #テキストファイル消去
                $fp = fopen($file_name, "w");
                fclose($fp);

                #追加モード
                $fp = fopen($file_name, "a");

                foreach($file_lines as $file_line){
                    $str = $file_line;
                    $datas = explode("<>", $file_line);

                    if($edit_num == (int)$datas[0]){
                        $datas[1] = $namae;
                        $datas[2] = $comment;
                        $datas[3] = $time;
                        $str = "$datas[0] <> $datas[1] <> $datas[2] <> $datas[3] (編集済)";
                    }
                    fwrite($fp, $str. PHP_EOL);
                }
                fclose($fp);
            }else{
                #保存フォーマット：「（投稿番号）<>（名前）<>（コメント）<>（投稿日時）」
                $str = "$count <> $namae <> $comment <> $time";
                
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
        }elseif(isset($_POST["d_num"])){

            #受け取った値を格納
            $d_num = $_POST["d_num"];

            #ファイルの内容を、配列に格納
            $file_name = "mission_3-4.txt";
            $file_lines = file($file_name, FILE_IGNORE_NEW_LINES);
                
            #内容の保存が終わったら、ファイルの中身を削除する
            $fp = fopen($file_name, "w");
            fclose($fp);

            #追加モードでもう一度ファイルを開く
            $fp = fopen($file_name, "a");
                
            #格納した配列の全ての要素をを、更にexplode関数を用いて分解する
            foreach($file_lines as $file_line){
                $file_datas = explode("<>", $file_line);

                #投稿番号と削除対象番号を比較し、等しくない場合はファイルに追加で書き込みを行う
                if((int)$file_datas[0] != $d_num){
                    $str = "$file_datas[0] <> $file_datas[1] <> $file_datas[2] <> $file_datas[3]";
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
        }elseif(isset($_POST["edit"])){
            $edit = $_POST["edit"];

            #file関数で配列を格納（内容保存？）
            $file_name = "mission_3-4.txt";
            $file_lines = file($file_name, FILE_IGNORE_NEW_LINES);

            #配列の数だけループ
            foreach($file_lines as $file_line){

                #explode関数で分割（投稿番号の取得）
                $file_datas = explode("<>", $file_line);

                if((int)$file_datas[0] == $edit){

                    #投稿番号と編集対象番号を比較して、一致する場合はその投稿の「名前」と「投稿内容」を取得
                    $e_name = $file_datas[1];
                    $e_comment = $file_datas[2];

                    #編集モードの判別用変数に、編集したい投稿番号を保存?
                    $edit_num = $edit;
                    
                    $form = '編集する投稿番号：<input type="number" name="edit_num" value="'. $edit_num. '"> <br>';
                }

                #既存の投稿フォームの中に、新規登録モードと編集モードの区別をする情報の追加を行う
            }
        }
            
?>


<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>mission_3-4</title>
    </head>

    <body>
        投稿フォーム<br>
        <form action="" method="post">
            
            <?php 
                echo $form;
            ?>
            <input type="text" name="namae" value="<?php /*フォームに取得済みの「名前」と「コメント」を既に入っている状態で表示(初期値は空欄)*/echo $e_name; ?>">
            <input type="text" name="comment" value="<?php echo $e_comment; ?>">
            
            <input type="submit" name="submit">
            <br>
        </form>
        <br>
        削除フォーム<br>
        <form action="" method="post">
            <input type="text" name="d_num">
            <input type="submit" name="d_submit">
            <br>
        </form>
        編集番号指定フォーム<br>
        <form action="" method="post">
            <input type="number" name="edit">
            <input type="submit" name="e-submit">
        </form>
        <br>
        
        
    </body>
</html>