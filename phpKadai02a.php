<?php
    error_reporting(1);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>取扱商品一覧</title>
    <meta http-equiv="Pragma" content="no-cache">
</head>
<body style="text-align:center">
    <strong>取り扱い商品一覧</strong>
    <hr width="80%"><center>
    <table border>
    <tr><th>商品コード</th><th>商品名</th><th>アーティスト名</th><th>価格(円)</th><th>発売日</th>
    <th>画像ファイル名</th></tr>
    <?php
        $host = "localhost";
        $user = "ScriptProg";
        $pass = "takanawa";
        $dbname = "sv9BJI1208DB";

        //接続する
        $syubetsu = $_GET["syubetsu"];
        $keyword = $_GET["kensaku"];
        $kakaku = $_GET["kakaku"];
        $hatsubai = $_GET["hatsubai"];

        $db = mysqli_connect($host,$user,$pass,$dbname);
        if($db == false){
            die('接続できませんでした:' .mysqli_error());
            exit;
        }

        $str_sql = makeSql($syubetsu,$keyword,$kakaku,$hatsubai);
        $rs = mysqli_query($db,$str_sql);


        while($arr_record = mysqli_fetch_assoc($rs)){
            print"<tr>\n";
            print"<td>${arr_record['Code']}<br><br>\n ";
            print"<a href='phpKadai02b.php?buy=${arr_record['Code']}'>買い物かごへ</a></td>\n";
            print"<td>${arr_record['CD_Name']}</td>\n ";
            print"<td>${arr_record['Art_Name']}</td>\n ";
            print"<td>${arr_record['Price']}</td>\n ";
            print"<td>${arr_record['Release_Date']}</td>\n ";
            if($arr_record['Image'] == ''){
                $imageFile = "NOTHING.JPG";
            }else{
                $imageFile = $arr_record['Image'];
            }
            print"<td><img src=\"image/$imageFile\"></td>\n";
            print"</tr>";

        }
        mysqli_close($db);
    ?>
    </table></center>
</body>
</html>
<?php
    function makeSql($syu,$key,$kakaku,$hatsubai){
        if($syu == 0){
            $sql = "select * from productTable where productTable.Code like '%$key%'";
        }else if($syu == 1){
            $sql = "select * from productTable where productTable.CD_Name like '%$key%'";
        }else if($syu == 2){
            $sql = "select * from productTable where productTable.Art_name like '%$key%'";
        }else if($syu == 3){
            if($kakaku == 2000){
                $sql = "select * from productTable where productTable.Price <= 2000";
            }
            elseif($kakaku == 2001){
                $sql = "select * from productTable where productTable.Price <= 3000 and productTable.Price >= 2001";
            }
            elseif($kakaku == 3001){
                $sql = "select * from productTable where productTable.Price >= 3001";
            }
        }else if($syu == 4){
            if($hatsubai == 00){
                $sql = "select * from productTable where productTable.Release_Date < '2001-01-01'";
            }
            elseif($hatsubai == 01){
                $sql = "select * from productTable where productTable.Release_Date >= '2001-01-01' and productTable.Release_Date < '2010-01-01'";
            }
            elseif($hatsubai == 10){
                $sql = "select * from productTable where productTable.Release_Date >= '2010-01-01'";
            }
        }else{
            $sql = "select * from productTable";
        }
        return $sql;
    }
?>