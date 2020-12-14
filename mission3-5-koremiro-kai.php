<!--　　フォームへの項目追加　＋　条件分岐　＋　ループ処理　-->
<!--【新規投稿フォームに「パスワード」の入力を追加】-->
<!--【テキストファイルに保存する文字列をに改修する】-->
<!--※ 投稿内容にパスワードの項目が加わり、つまりフォーマットが変わるため、テキストファイルも新規作成しておこう。-->
<!--「投稿番号・名前・コメント・投稿日時・パスワード」に改修する-->
<!--このとき、パスワードの後ろ(右側)にも区切り文字「<>」を付けておくと安全。文字列の最後にある「改行」も一種の文字として扱われるため、改行が付いていると文字列が一致しないと判断される。「<>」を付けることで改行を切り離すことができる。-->
<!--【「削除」と「編集」も各々フォームに「パスワード」の入力を追加する】-->
<!--【「削除」と「編集」でも、パスワードが一致した時のみ機能が動作させる】-->
<!--※パスワードなしの投稿は削除も編集もしない-->







<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_3-5</title>
</head>
<body>
    <?php
        $filename = "mission_3-5.txt";

        //受信したコメントをtxtに追記または編集する
        if(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["password"])){

            //書き込む文字列の生成
            $name = $_POST["name"];
            $comment = $_POST["comment"];
            $timestamp = date("Y/m/d G:i:s");
            $password = $_POST["password"];
            
            if (file_exists($filename)){
                $lines = file($filename,FILE_IGNORE_NEW_LINES);
                $last = count($lines);
                $words = explode("<>",$lines[$last-1]);
                $num = $words[0]+1;
            }else{
                $num = 1;
            }

            //編集するときの処理
            if(!empty($_POST["flag"]) && !empty($_POST["passwordflag"])){

                //書き込みモードで開くので先に元ファイルの文字列を控えておく
                $lines_edit = file($filename,FILE_IGNORE_NEW_LINES);
                $editpassword = $_POST["passwordflag"];

                //ファイルを書き込みモードで開く
                $fp_edit = fopen($filename,"w");
                
                
             foreach($lines_edit as $line_edit){
               $lineedit = explode("<>",$line_edit);
               $targeteditpassword = $lineedit[4];
               $targeteditpasswordnumber = $lineedit[0];
               
               if($_POST["passwordflag"] == $targeteditpassword  && $targeteditpasswordnumber == $_POST["flag"]){
                
                foreach($lines_edit as $l){
                    $words_edit = explode("<>",$l);

                    //投稿番号を照合し、一致するものだけ名前とコメントを差し替える
                    if($words_edit[0] == $_POST["flag"]){
                        $words_edit[1] = $name;
                        $words_edit[2] = $comment;
                        $words_edit[4] = $password;
                    }

                    //分解した配列を文字列にまとめ直し、txtに書き込む
                    $lines_edited = implode("<>",$words_edit);
                    fwrite($fp_edit,$lines_edited.PHP_EOL);
                }
                fclose($fp_edit);   
            }
           }
          }

            //新規投稿時の処理
            else{

                //ファイルを追記モードで開いて文字列を書き込む
                $fp_add = fopen($filename,"a");
                $str_add = $num."<>".$name."<>".$comment."<>".$timestamp."<>".$password."<>";
                fwrite($fp_add,$str_add.PHP_EOL);
                fclose($fp_add);
            }
        }

        //削除フォームに受信があった時のみその行を消したtxtファイルに書き換える  
        // そして,入力したデリートパスワードの値が、入力したデリートナンバーのパスワードに等しい時のみ編集を可能にできる
        if(!empty($_POST["num_del"]) && !empty($_POST["deletepassword"])){
            $num_del = $_POST["num_del"];
            $deletepassword = $_POST["deletepassword"];
            
            //書き込みモードで開くので先に元ファイルの文字列を控えておく
            $lines_del = file($filename,FILE_IGNORE_NEW_LINES);
               
              
              $fp_del = fopen($filename,"w");
            foreach($lines_del as $line_del){
               $linedel = explode("<>",$line_del);
               $targetpassword = $linedel[4];
               $targetpasswordnumber = $linedel[0];
              
            if(!($deletepassword == $targetpassword  && $targetpasswordnumber == $num_del)){
            //   $fp_del = fopen($filename,"w");
            //     fwrite($fp_del,$line_del.PHP_EOL);
            //   $targetpasswordline = $line_del[0]-1;
            //   unset($lines_del[$targetpasswordline]);
            //   $lines_del = array_values($lines_del);
            //   file_put_contents("mission_3-5.txt",$lines_del);
              
                // fwrite($fp_del,$line_del.PHP_EOL);
                // var_dump($line_del);
                
                // $lines_deleted = implode("<>",$line_del);
                    fwrite($fp_del,$line_del.PHP_EOL);
                    
            }
                // var_dump($line_del);
                
            }
            fclose($fp_del);
            
        }    

            //ファイルを書き込みモードで開いて削除する投稿以外の文字列を書き込む
        //     foreach($lines_del as $l){
        //         $words_del = explode("<>",$l);
        //         if($words_del[0] !== $num_del){
        //             fwrite($fp_del,$l.PHP_EOL);
        //         }
        //     }
        //     fclose($fp_del);
        // }




        //編集フォームに受信があった時のみその投稿番号を含む名前とコメントを入力用フォームに表示する
        if(!empty($_POST["num_edit"])){
            $num_edit = $_POST["num_edit"];
            $lines_edit = file($filename,FILE_IGNORE_NEW_LINES);
            foreach($lines_edit as $l){
                $words_edit = explode("<>",$l);
                if($words_edit[0] == $num_edit){
                    $name_edit = $words_edit[1];
                    $comment_edit = $words_edit[2];
                    $password_edit = $words_edit[4];
                }
            }
        }
    ?>

    <!--コメント入力用フォーム-->
    <form action="" method="post">
    コメントを投稿する<br>
        <input type="hidden" name="flag" value="<?php echo $num_edit; ?>">
        <input type="hidden" name="passwordflag" value="<?php echo $password_edit; ?>">
        <input type="text" name="name" placeholder="名前" value="<?php echo $name_edit; ?>">
        <input type="text" name="comment" placeholder="コメント" value="<?php echo $comment_edit; ?>">
        <input type="text" name="password" placeholder="投稿パスワード" value="<?php echo $password_edit; ?>">
        <input type="submit" value="送信">
    </form>

    <!--コメント削除用フォーム-->
    <form action="" method="post">
    投稿を削除する<br>
        <input type="number" name="num_del" placeholder="削除する投稿の番号">
        <input type="text" value="削除パスワード" name="deletepassword">
        <input type="submit" value="削除">
    </form>

    <!--コメント編集用フォーム-->
    <form action="" method="post">
    投稿を編集する<br>
        <input type="number" name="num_edit" placeholder="編集する投稿の番号">
        <input type="text" value="編集パスワード" name="editpassword">
        <input type="submit" value="編集">
    </form>


    <?php
        //txtをブラウザに表示
        if(file_exists($filename)){
            $lines_disp = file($filename,FILE_IGNORE_NEW_LINES);
            foreach($lines_disp as $l){
                $words_disp = explode("<>",$l);
                foreach ($words_disp as $w){
                    echo $w." ";
                }
                echo "<br>";
            }
        }
    ?>
</body>
</html>