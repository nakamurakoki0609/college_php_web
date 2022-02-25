<?php
    session_start();
    error_reporting(1);
    $buyCnt = 0;
    if($_SESSION["P$buyCnt"] != ""){
        $buyCnt = $buyCnt +1;
    }
    $i =$buyCnt;
    $buy = $_GET["buy"];
    if($buy == "clear"){
        for($i=0; $i < $buyCnt; $i++){
            $_SESSION["P$i"] = "";
        }
        $buyCnt = 0;
    }else if($buy !=""){
        $_SESSION["P$i"] = $buy;
        $i =$i+1;
        $buyCnt = $buyCnt +1;
    }
    $_SESSION["cnt"] = $buyCnt;
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <meta http-equiv="Progma">
</head>
<body>
    <div align="center">
    <?php
        if($buyCnt ==0){
            print"<h2>買い物カゴは空です</h2>\n";
            print"</body>\n</html>\n";
            exit;
        }
        print"<h2>買い物かご一覧</h2>\n<hr>\n";
        print"<table border>\n";
        print"<tr><th>商品コード</th><th>商品名</th><th>アーティスト名</th><th>価格</th></tr>\n";

        $host = "localhost";
        $user = "ScriptProg";
        $pass = "takanawa";
        $dbname = "sv9BJI1208DB";
        $db = mysqli_connect($host,$user,$pass,$dbname);
        if($db == false){
            die('接続できませんでした:' .mysqli_error());
            exit;
        }
        $sum = 0;
        for($i=0; $i<$buyCnt; $i++){
            $code = $_SESSION["P$i"];
            $str_sql = "select CD_Name, Art_Name, Price from productTable where Code = '$code'";
            $rs = mysqli_query($db,$str_sql);
            if(mysqli_affected_rows($db) == 0){
                print"<font size='5' color='red'>$code の情報が取得できませんでした。</font><br>\n";
                mysqli_close($db);
                print"</body></html>\n";
                exit;
            }
            $arr_record = mysqli_fetch_assoc($rs);
            $pName = $arr_record["CD_Name"];
            $pArtName = $arr_record["Art_Name"];
            $pPrice = $arr_record["Price"];
            print"<tr>\n";
            print"<td>$code</td>\n";
            print"<td>$pName</td>\n";
            print"<td>$pArtName</td>\n";
            print"<td>$pPrice 円</td>\n";
            print"</tr>\n";
            $sum = $sum +$pPrice;
        }
        $tax = $sum*0.1;
        $tax = floor($tax);
        mysqli_close($db);
        $sum = $tax+$sum;
    ?>
    <tr style="text-align:right"><td colspan="2"></td><td>(うち消費税)</td>
    <td><?php print"$tax"; ?>円</td></tr>
    <tr style="text-align: right"><td colspan="2"></td><td>合　計</td>
    <td><?php print "$sum";?>円</td></tr>
    </table>
    <a href='phpKadai02b.php?buy=clear' target="hyoji">買い物かごを空にする</a><br><br><hr>
    <form action="phpKadai02c.php" method="post">
        <strong>上記の商品を購入する</strong><br>
        <table>
            <tr><td>ユーザID</td><td><input type="text" name="id" size="20"></td></tr>
            <tr><td>パスワード</td><td><input type="password" name="pass" size="20"></td></tr>
        </table>
        <input type="submit" value="購入する"><br>
    </form><hr>
    ユーザIDとパスワードをお持ちでない方は、先にユーザ登録を行なってください。<br>
    「購入する」ボタンをクリックした時点で、注文が確定いたします。ご注意ください。<br>
    </div>
</body>
</html>