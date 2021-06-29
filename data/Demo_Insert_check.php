<!DOCTYPE html>
<html lang ="ko">
<head>
  <meta charset= "utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style> a {text-decoration: none;} </style>
  <title> TP 전자도서관 </title>
</head>
<body>
  <h1><a href="../main.php">TP 전자도서관</a></h1>
  <?php
  ////데모용 도서 대여 페이지////
    $UserID = $_POST['id'];
    $UserPWD = $_POST['pwd'];
    $conn = oci_connect("D201702089", "baechu143", "xe", "AL32UTF8");
    $ISBN = $_POST['isbn'];
    $DATE = $_POST['datedue'];
    $res = explode("-",$DATE);
    $Fix = substr($res[0],2);
    $DATEDUE = $Fix."/".$res[1]."/".$res[2];
    $SQL = "UPDATE EBOOK SET DATEDUE = '$DATEDUE', CNO = $UserID WHERE ISBN = '$ISBN'";
    //넘겨받은 DATEDUE,CNO값을 EBOOK에 입력
    $state = oci_parse($conn,$SQL);
    $result = oci_execute($state);

  ?>
  <h3>데이터 입력이 완료되었습니다.</h3>
  <form method="post" action="./MyPage.php">
     <input type="hidden" name = "id" value= <?= $_POST['id']; ?>>
      <input type="hidden" name = "pwd" value= <?= $_POST['pwd']; ?>>
    <div class="botton">
     <button type="submit"> 마이페이지에서 확인하기 </button>
     </div>
     </form>
     <?php
     oci_free_statement($state);
     oci_close($conn);
      ?>
</body>
