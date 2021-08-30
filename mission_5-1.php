<!DOCTYPE html>
<?php
//データベースに接続
 $dsn = 'dbname';
    $user = 'username';
    $password = 'password';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
?>
<?php
//編集機能、削除機能、投稿、表示作成
//編集の１段階目（編集番号入れて、パスワード一致したら表示される）
error_reporting(0);
$array=[NULL,NULL,NULL];
$edit=$_POST["edit"];
$e_pas=$_POST["pas3"];

    $sql = 'SELECT * FROM tbtest WHERE id=:id and pass=:pass';//テーブル内で番号とパスワードが一致した行を持ってくる
    $stmt = $pdo->prepare($sql);//$sqlを実行
    
    $stmt->bindParam(':pass', $e_pas, PDO::PARAM_STR);
    $stmt->bindParam(':id', $edit, PDO::PARAM_INT);
    $stmt->execute();//18が完了
    $results = $stmt->fetchAll();//fetchAllが$stmtが取得した値を全部持って
    $array[0]=$edit;
    $array[1]=$results[0]['name'];//二次元配列
    $array[2]=$results[0]['comment'];
  
    var_dump($results);
?>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_3-5</title>
</head>
<body>
        <form action="" method="post">
        <?php
            if($array[1]!=NULL){
        ?>
        <p>
        <input type="hidden" name="number2"placeholder="番号"value=<?php echo $array[0];?>></p>
        <p>名前：
        <input type="text" name="name2"placeholder="名前"value=<?php echo $array[1];?>></p>
        <p>コメント：
        <input type="text" name="str2"value=<?php echo $array[2];?>></p>
        <input type="submit" name="submit">
        <?php
            }else{
            ?>
        <form>
        <p>名前：
        <input type="text" name="name"placeholder="名前"></p>
        <p>コメント：
        <input type="text" name="str"></p>
        <p>パスワード：
        <input type="text" name="pas"placeholder="パスワード"></p>
        <input type="submit" name="submit">
        </form>
        <?php
            }
            ?>
        <form action="" method="post">
        <p>削除対象番号：
        <input type="number" name="delete"></p>
        <p>パスワード：
        <input type="text" name="pas2"placeholder="パスワード"></p>
        <input type="submit" name="submit"value="削除">
        </form>
        
        
        <form action="" method="post">
        <p>編集：
        <input type="number" name="edit"></p>
        <p>パスワード：
        <input type="text" name="pas3"placeholder="パスワード"></p>
        <input type="submit" name="submit"value="編集">
        </form>
<?php
    error_reporting(0);
    //編集機能
    //編集の第二段階（表示されたやつを編集して送信して編集）
    if(!empty($_POST["number2"])){
        $id=$_POST["number2"];
        $name=$_POST["name2"];
        $comment=$_POST["str2"];
        $date = date("Y年m月d日 H時i分s秒");

    $sql = 'UPDATE tbtest SET name=:name,comment=:comment, date=:date WHERE id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':date', $date, PDO::PARAM_STR);
    $stmt->execute();
    }    
    //削除機能
    if(!empty($_POST["delete"])&&!empty($_POST["pas2"])){
        $d_pas=$_POST["pas2"];//pas2は削除機能のパスワードで、$d_pasに代入している
        $id=$_POST["delete"];//deleteは削除番号
        
    $sql = 'delete from tbtest where id=:id and pass=:pass';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);//:idはテーブルに入ってる
    $stmt->bindParam(':pass', $d_pas, PDO::PARAM_STR);//INTは数字、STRは文字列型（charがキャラ型）
    $stmt->execute();
    }
    //追加機能（投稿）
    if(!empty($_POST["name"]) && !empty($_POST["str"])){
        $name = $_POST["name"];
        $comment = $_POST["str"];
        $date = date("Y年m月d日 H時i分s秒");
        $pass = $_POST["pas"];
        
        $sql = $pdo -> prepare("INSERT INTO tbtest (name, comment ,date ,pass) VALUES (:name, :comment, :date, :pass)");
    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
    $sql -> bindParam(':date', $date, PDO::PARAM_STR);
    $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
    $sql -> execute();
    };
        $sql = 'SELECT * FROM tbtest';//tbtestっていうテーブルから全行もってきてる
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        //$rowの中にはテーブルのカラム名が入る
        echo $row['id'].',';
        echo $row['name'].',';
        echo $row['comment'].',';
        echo $row['date'].',';
        echo $row['pass'].'<br>';
    echo "<hr>";
    }
    
?>