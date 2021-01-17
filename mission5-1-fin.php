<?php
 
//   ここも決して間違っているとかではないですが、画面開いた時に一々noticeでてきて邪魔だと思ったら条件分岐のところで直接$_POSTするのもありだと思います
//     また日付をここで代入する必要はないと思います
    
    
    $name = $_POST["name"];
	$comment = $_POST["comment"]; 
// 	$postat=date('Y年m月d日 H:i:s');
	$pass=$_POST["pass"];
    $delete=$_POST["deletenum"];
    $edit=$_POST["editnum"];
    $num=$_POST["num"];
    $delpass=$_POST["delpass"];
    $edpass=$_POST["edpass"];

    
    // 以下、4行を改めて自分のに書き換えてください
    
    
    	// 4-1DB接続設定
	$dsn = 'mysql:dbname=db;host=localhost';
	$user = 'ユーザー';
	$password = 'パスワード';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));


// 	日付の部分はTEXTでひょっとしたらできたかもしれませんが、普通はDATETIME等を使うみたいなので直しときました
    
    
	$sql = "CREATE TABLE IF NOT EXISTS pos"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "pass TEXT,"
	. "postat DATETIME"
	.");";
	$stmt = $pdo->query($sql);
    
   // 投稿機能
    if(!empty($name)&&!empty($comment)&&!empty($pass)&&empty($num)){
        
        //4-5投稿機能
        // 最後のカンマをとってください
    $sql = $pdo -> prepare("INSERT INTO pos (name, comment,postat,pass ) VALUES (:name, :comment,:postat,:pass)");
	$sql -> bindParam(':name', $name, PDO::PARAM_STR);
	$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
	$sql -> bindParam(':postat', $postat, PDO::PARAM_STR);
	$sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
	$name = $_POST["name"];
	$comment = $_POST["comment"]; 
	$postat=date("Y/m/d H:i:s"); 
	$pass=$_POST["pass"];
	$sql -> execute();
        
  }
      
    //   編集機能
      
    //   条件分岐のところにせっかくなら投稿機能との違いを明確にするためにも下のように一旦書き換えときましょうか
    
      if(!empty($name)&&!empty($comment)&&!empty($pass)&&!empty($num)){
          

        
        
        // 等しければ、その番号における編集処理を実行する ミッション4－7参照
          
    $id = $num; //変更する投稿番号
	$name = $_POST["name"];
	$comment = $_POST["comment"]; //変更したい名前、変更したいコメントは自分で決めること
	$pass = $_POST["pass"]; //変更したい名前、変更したいコメントは自分で決めること
	
	$postat = date("Y/m/d H:i:s"); //変更したい名前、変更したいコメントは自分で決めること
	
	$sql = 'UPDATE pos SET name=:name,comment=:comment, pass=:pass, postat=:postat WHERE id=:id';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':name', $name, PDO::PARAM_STR);
	$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
	$stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
	$stmt->bindParam(':postat', $postat, PDO::PARAM_STR);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	$stmt->execute();

          
     }
          
          
   //  削除機能
        if(!empty($delete)&&!empty($delpass)){
        
        // 編集処理と同じような流れ
        
        //   まずは、受け取った削除番号の投稿におけるパスワード取り出す ミッション4－6発展参考
        
        $sql = 'SELECT * FROM pos';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		$deleteid = $row['id'];
		$deletepassword = $row['pass'];
	
      // 次に、そのパスワードと、受信した削除パスワードが等しいか確認する ミッション3－5参照
          if($deleteid == $delete && $deletepassword == $delpass){
        // そして、等しければ、その番号における削除処理を実行する ミッション4－8参照
        
        $id = $deleteid;
	$sql = 'delete from pos where id=:id';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	$stmt->execute();
        
              
          }
        
        
        
	  }
        
        
        
        
    }
    
    
    
 
	  
	//編集機能 フォーム上に出力の作業
     $edit=$_POST["editnum"];
     $edpass=$_POST["edpass"];
     
        if(!empty($edit)){
            
             //   まずは、受け取った編集番号の投稿におけるパスワード取り出す ミッション4－6発展参考
        
        $id = $edit ; // idがこの値のデータだけを抽出したい、とする

$sql = 'SELECT * FROM pos WHERE id=:id ';
$stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
$stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
$stmt->execute();                             // ←SQLを実行する。
$results = $stmt->fetchAll(); 
	
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		$editid = $row['id'];
		$editpassword = $row['pass'];
		
		

	
	
        
     // 次に、そのパスワードと、受信した編集パスワードが等しいか確認する ミッション3－5参照
     
     if($editid == $edit && $editpassword == $edpass){
         
         // 受けとった編集番号における投稿内容(名前、パスワード、コメント)を引っ張り出せばよく、それを出力さえすればいいこと
        
        // なので、ミッション4－6参考にそれぞれ取り出していけばよい
        
        $sql = 'SELECT * FROM pos';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
	    $editname =   	 $row['name'];
		$editcomment = $row['comment'];
		$editpass = $row['pass'];
	
    
	      }
	    
	    
     	}
        
     }  
        
        
  }
	
	 
    
    
    
    
        

// 	以下ブラウザ表示
	
// 	4-6表示
    $sql = 'SELECT * FROM pos';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].',';
		echo $row['postat'].',';
		echo $row['pass'].'<br>';
	    echo "<hr>";
	}
	  

	
	
	
    
    
      
    
	
 
 
 
 
 
 

?>





<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5</title>
</head>
<body>
    
    <!--valueのところには、下のように編集番号受け取ったのみに表示というふうに記述しときましょう-->
    <!--でないと、画面開いた時にいちいちnoticeとかでたり、入力欄上に<br>がでてきてしつこいです-->
    
    
     <form action="" method="post">
        <input type="text" name="name" placeholder="名前" value=<?php if(!empty($_POST["editnum"])){if($editid == $edit && $editpassword == $edpass)
        { echo $editname;}}?>> <br>
        <input type="text" name="comment" placeholder="コメント" value=<?php if(!empty($_POST["editnum"])){if($editid == $edit && $editpassword == $edpass)
        { echo $editcomment; }}?>><br>
        <input type="text" name="pass" placeholder="パスワード"value=<?php if(!empty($_POST["editnum"])){if($editid == $edit && $editpassword == $edpass)
        { echo $editpass;}} ?>>
        <input type="hidden" name="num" value=<?php if(!empty($_POST["editnum"]))
        {echo $edit;}?>>
        <input type="submit" name="submit">
    </form>
    <br>
    
    <form action="" method="post">
        
        <input type="text" name="deletenum" placeholder="削除対象番号"><br>
        <input type="text" name="delpass" placeholder="パスワード">
        <input type="submit" name="submit" value="削除">
        

    </form>
    
    <br>
    
    <form action="" method="post">
        
        <input type="text" name="editnum" placeholder="編集対象番号"><br>
        <input type="text" name="edpass" placeholder="パスワード">
        <input type="submit" name="submit" value="編集">
        

    </form>
    
    
</body>
</html>
