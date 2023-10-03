<!--掲示板に「削除機能」を実装しよう。指定された番号の投稿だけを削除できるようにする。-->

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>mission_3-3</title>
    </head>

    <body>
        投稿フォーム<br>
        <form action="" method="post">
            
            <input type="text" name="namae">
            <input type="text" name="comment">
            
            <input type="submit" name="submit">
            <br>
        </form>
        <br>
        削除フォーム<br>
        <form action="" method="post">
            <input type="number" name="d_num">

            <input type="submit" name="d_submit">
        </form>
        
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

            if(isset($_POST["namae"]) && isset($_POST["namae"])){
              
              #【POST送信の各値の変数と「投稿番号」「投稿日時」を扱う変数を用意。更にこれらを結合して1行にするための変数も用意し、ここに結合結果を入れる】
                $namae = $_POST["namae"];
                $comment = $_POST["comment"];
                
                #投稿番号初期値
                $count = 1;
                #時間を取得
                $time = date("Y/n/j G:i:s");
                
                $file_name = "mission_3-3.txt";
                
                #ファイルの中身があるなら行番号を返す
                if(file_exists($file_name)){
                    #テキストファイルのデータを配列に格納 (ある前提で進めてるからおかしくなる、この時点でifに入れていいかも)
                    $file_lines = file($file_name, FILE_IGNORE_NEW_LINES);

                    #配列の個数を計算
                    $count  = $count + count($file_lines);
                }
                
                #保存フォーマット：「（投稿番号）<>（名前）<>（コメント）<>（投稿日時）」
                $str = "$count <> $namae <> $comment <> $time";
                
                #ファイル操作
                $fp = fopen($file_name, "a");
                fwrite($fp, $str. PHP_EOL);
                fclose($fp);

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
                
            }elseif(isset($_POST["d_num"])){
                
                #受け取った値を格納
                $d_num = $_POST["d_num"];

                #ファイルの内容を、配列に格納
                $file_name = "mission_3-3.txt";
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
                    if($file_datas[0] != $d_num){
                        fwrite($fp, $file_line.PHP_EOL);
                    }
                }
                fclose($fp);
            }
            
        ?>
    </body>
</html>