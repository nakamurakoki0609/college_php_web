<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div align:center>
        <?php
            $uID = $_POST["id"];
            $uPass = $_POST["pass"];
            $host = "localhost";
            $user = "ScriptProg";
            $pass = "takanawa";
            $dbname = "sv9BJI1208DB";
            $db = mysqli_connect($host,$user,$pass,$dbname);
            if($db == false){
                die('接続できませんでした:' .mysqli_error());
                exit;
            }

            $str_sql = "select * from customerTable where CustomID='$uID' and Pass = '$uPass'";
            $rs = mysqli_query($db,$str_sql);

            if(mysqli_affected_rows($db) == 0){
                print"<font size='5' color='red'>入力されたユーザID または　パスワードが間違っています。</font><br>\n";
                print"<a href='phpKadai02b.php'>前のページへ戻る</a>\n";
                mysqli_close($db);
                print"</body></html>\n";
                exit;
            }
            $orderNo = 0;
            $str_sql = "select max(OrderNo) as maxNo from OrderTable";
            $rs = mysqli_query($db,$str_sql);
            if($rs == false){
                mysqli_close($db);
                print"注文番号取得エラー<br>\n";
                exit;
            }
            $arr_record = mysqli_fetch_assoc($rs);
            $orderNo = $arr_record['maxNo'];
            $orderNo = $orderNo +1;
            $buyCode = "";
            $buyCnt = $_SESSION["cnt"];
            for($i=0; $i < $buyCnt; $i++){
                $buyCode = $buyCode.$_SESSION["P$i"].",";
            }
            date_default_timezone_set('Asia/Tokyo');
            list($ss,$mm,$hh,$dd,$mon,$yy) = localtime();
            $yy = $yy +1900;
            $mon = $mon+1;
            if($mon < 10){
                $mon ="0$mon";
            }
            if($dd <10){
                $dd = "0$dd";
            }
            $ins = "($orderNo, '$yy-$mon-$dd', '$uID','$buyCode')";
            $str_sql = "insert into OrderTable values $ins";
            $rs = mysqli_query($db,$str_sql);

            if(mysqli_affected_rows($db) == 0){
                print"<font size='5' color='red'>追加できませんでした。</font><br>\n";
                mysqli_close($db);
                exit;
            }
            for($i=0; $i < $buyCnt; $i++){
                $_SESSION["P$i"] = "";
            }
            mysqli_close($db);
        ?>
        <div style="text-align: center">
        <h2>注文を受け付けました</h2><hr>
        注文番号は「<strong><?php print "$orderNo"; ?></strong>」です。<br>
        </div>
    </div>
    
</body>
</html>