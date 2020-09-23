<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>
    
    <?php
    
       //   データベースへの接続

  $dsn = 'データベース名';
  $user = 'ユーザー名';
  $password = 'パスワード';
  $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
  
  
//  データベース内にpostテーブルを作成

  $sql = "CREATE TABLE IF NOT EXISTS repost"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "password char(32),"
	. "created DATETIME"
	.");";
  $stmt = $pdo->query($sql);
  
    
       
        

        // もし名前とコメント、パスワードが入力されていて、
        if(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["password"])){

          
            // ①そしてそのときかつ、編集番号と編集パスワードが受信されていた場合以下の処理を行う
            if(!empty($_POST["flag"]) && !empty($_POST["passwordflag"])){
                
                $flag = $_POST["flag"];
                $flagpassword = $_POST["passwordflag"];
            
             
            //  まず、num_editに値するデータをデータベースから抽出する
            

	         $id = $flag ; // idがこの値のデータだけを抽出したい、とする

             $sql = 'SELECT * FROM repost WHERE id=:id ';
             $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
             $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
             $stmt->execute();                             // ←SQLを実行する。
             $results = $stmt->fetchAll(); 
             
            //  var_dump($results);
             
            //  その値におけるパスワードを取り出す
            
	           foreach ($results as $row){
		
		      $editnumber = $row['id'];
		      $editpassword = $row['password'];
		      
		      //var_dump($editpassword);
		       
	           }
	           
	       // そして、そのIDにおけるデータのパスワードと入力された$editpasswordと等しかった場合以下の処理を行う
	       
	        if($editpassword == $flagpassword){
	            
	         //  受信した番号に値するデータを編集する
	         
	         $id = $flag; //変更する投稿番号
	         $name = $_POST["name"];
	         $comment = $_POST["comment"]; 
	         $password = $_POST["password"]; //変更したい名前、変更したいコメントは自分で決めること
	         $created = date("Y/m/d H:i:s");
	         $sql = 'UPDATE repost SET name=:name,comment=:comment, password=:password, created=:created WHERE id=:id';
	         $stmt = $pdo->prepare($sql);
	         $stmt->bindParam(':name', $name, PDO::PARAM_STR);
	         $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
	         $stmt->bindParam(':password', $password, PDO::PARAM_STR);
	         $stmt->bindParam(':created', $created, PDO::PARAM_STR);
	         $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	         $stmt->execute();

            
	        }
	        
	        
	       // そしてデータ編集したデータベースのデータを改めて4－6参考にブラウザ表示するための作業をしていく
	       
	        $sql = 'SELECT * FROM repost';
        	$stmt = $pdo->query($sql);
        	$results = $stmt->fetchAll();
	           foreach ($results as $row){
		       //$rowの中にはテーブルのカラム名が入る
		       echo $row['id'].',';
		       echo $row['name'].',';
		       echo $row['comment'].',';
		       echo $row['password'].',';
		       echo $row['created'].'<br>';
               echo "<hr>";
	
	               
	                
	           }
	       
	       
	        // ②その時に編集番号や編集パスワードが空欄だった場合以下の処理を行う
            // →普通にフォームから送られてきたデータを格納する作業を行えばいいだけ
            
            
            }else{
              //   名前、コメント、パスワード、日付のデータを、データベース内に入力していく

              $sql = $pdo -> prepare("INSERT INTO repost (name, comment, password, created) VALUES (:name, :comment, :password, :created)");
	          $sql -> bindParam(':name', $name, PDO::PARAM_STR);
	          $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
              $sql -> bindParam(':password', $password, PDO::PARAM_STR);
	          $sql -> bindParam(':created', $created, PDO::PARAM_STR);
	          $name = $_POST["name"];
	          $comment = $_POST["comment"]; 
	          $password = $_POST["password"]; 
	          $created = date("Y/m/d H:i:s"); //好きな名前、好きな言葉は自分で決めること
	
        	  $sql -> execute();
        	  
        	  
        // 	  データベースに入力したうえでその値を入力する
        
             $sql = 'SELECT * FROM repost';
        	$stmt = $pdo->query($sql);
        	$results = $stmt->fetchAll();
	           foreach ($results as $row){
		       //$rowの中にはテーブルのカラム名が入る
		       echo $row['id'].',';
		       echo $row['name'].',';
		       echo $row['comment'].',';
		       echo $row['password'].',';
		       echo $row['created'].'<br>';
               echo "<hr>";
	
	  
            }
            
        }
        
        }
            
            
   // そして,入力したデリートパスワードの値が、入力したデリートナンバーのパスワードに等しい時のみ編集を可能にできる
        
        // もしデリートナンバーとデリートパスワードが入力されていた時
        
        if(!empty($_POST["num_del"]) && !empty($_POST["deletepassword"])){
            
            
            
           
            
            
    //   データベースから、IDが$num_del番目の、パスワード$deletepasswordを抽出する    
            
            
            $id = $_POST["num_del"] ; // idがこの値のデータだけを抽出したい、とする

            $sql = 'SELECT * FROM repost WHERE id=:id ';
            $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
            $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
            $stmt->execute();                             // ←SQLを実行する。
            $results = $stmt->fetchAll(); 
	           
    //  そこから得たパスワードをとりあえず何らかの変数にでも代入させておく?       
	         
	         
	          foreach ($results as $row){
		
		       
		      $deletepassword = $row['password'];
		       
	           }
            
    //  そしてそうして得たパスワードが、受信したデリートパスワードと同じだった場合以下の操作を行う
        
        if($deletepassword == $_POST["deletepassword"]){
            
    // 受信した番号に値するデータベースのデータを削除する
            
           $id = $_POST["num_del"];
	       $sql = 'delete from repost where id=:id';
	       $stmt = $pdo->prepare($sql);
	       $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	       $stmt->execute();
 
            
            
            // そして同じように削除内容が反映されるべく、4－6作業参考にブラウザ表示させていく
            
             $sql = 'SELECT * FROM repost';
        	$stmt = $pdo->query($sql);
        	$results = $stmt->fetchAll();
	           foreach ($results as $row){
		       //$rowの中にはテーブルのカラム名が入る
		       echo $row['id'].',';
		       echo $row['name'].',';
		       echo $row['comment'].',';
		       echo $row['password'].',';
		       echo $row['created'].'<br>';
               echo "<hr>";
	
	               
	                
	           }
	       }
       }
       
       
    //   編集ボタンが押された時、その番号に該当する、データを取り出し、そのデータにおける、投稿フォームに名前、コメント、パスワードを取り出し、変数に代入する
    
    if(!empty($_POST["num_edit"]) && !empty($_POST["editpassword"])){
        
        $id = $_POST["num_edit"] ; // idがこの値のデータだけを抽出したい、とする

        $sql = 'SELECT * FROM repost WHERE id=:id ';
        $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
        $stmt->execute();                             // ←SQLを実行する。
        $results = $stmt->fetchAll(); 
	        foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		     $edit_name = $row['name'];
		     $edit_comment = $row['comment'];
		     $edit_password =$row['password'];
		     
		     echo $edit_name;
		     echo $edit_comment;
		     echo $edit_password;
	       
	           }
        
            }
       
       
       
           
    ?>
    
    <!--コメント入力用フォーム-->
    <form action="<?php echo($_SERVER['PHP_SELF']) ?>" method="post">
    コメントを投稿する<br>
        <input type="hidden" name="flag" value="<?php if(!empty($_POST["num_edit"])) {echo $_POST["num_edit"] ;} ?>">
        <input type="hidden" name="passwordflag" value="<?php if(!empty($_POST["editpassword"])) {echo $_POST["editpassword"] ;}?>">
        <input type="text" name="name" placeholder="名前" value="<?php if(!empty($_POST["num_edit"])) {echo $edit_name;}?>">
        <input type="text" name="comment" placeholder="コメント" value="<?php if(!empty($_POST["num_edit"])) {echo $edit_comment;} ?>">
        <input type="text" name="password" placeholder="投稿パスワード" value="<?php if(!empty($_POST["num_edit"])) {echo $edit_password;} ; ?>">
        
        <input type="submit" value="送信">
    </form>

    <!--コメント削除用フォーム-->
    <form action="<?php echo($_SERVER['PHP_SELF']) ?>" method="post">
    投稿を削除する<br>
        <input type="number" name="num_del" placeholder="削除する投稿の番号">
        <input type="text" placeholder="削除パスワード" name="deletepassword">
        <input type="submit" value="削除">
    </form>

    <!--コメント編集用フォーム-->
    <form action="<?php echo($_SERVER['PHP_SELF']) ?>" method="post">
    投稿を編集する<br>
        <input type="number" name="num_edit" placeholder="編集する投稿の番号">
        <input type="text" placeholder="編集パスワード" name="editpassword">
        <input type="submit" value="編集">
    </form>

    </body>
    </html>